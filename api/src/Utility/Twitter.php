<?php
//static class containing all twitter functions
//call a function like so:  StringUtil::limitString($string, 300);
//to do so within the class:  self::limitString($string, 300);

namespace RoboPaul\Utility;

class Twitter {

    public static function Predict($one=null, $two=null, $additional=null){
        if ( $one === null ){
            $return['status'] = false;
            $return['message'] = "Team one is required.";
            return $return;
        } else if ( $two === null ){
            $return['status'] = false;
            $return['message'] = "Team two is required.";
            return $return;
        }

        //setup some logging
        $main = \RoboPaul\Initialize::obtain();

        //$main->log->addDebug("Team one: " . $one . ", Team two: " . $two);

        //let's get the search terms
        $one = str_replace("<h3>", "", $one);
        $one = str_replace("</h3>", "", $one);
        $two = str_replace("<h3>", "", $two);
        $two = str_replace("</h3>", "", $two);
        $search = array();
        $search["one"] = explode(" ", strtolower($one));
        $search["two"] = explode(" ", strtolower($two));


        //$main->log->addDebug("Search array: " . print_r($search,true));

        $root = $main->config->get('directories.root');
    
        require $root . "/array.php";

        $score = array();
        foreach($search as $k => $s){
            //determine the language
            $lang = "en";
            $dict = "en_GB";
            foreach($teams as $tk => $td){
                if ( strpos($td['team'], $s[0]) !== false ){
                    $lang = $td['lang'];
                    $dict = $td['dict'];
                }
            }

            $result = self::searchString($s, $lang, $additional);
            $result = json_decode($result, true);
            $tweets = array();
            foreach($result['statuses'] as $arr){
                if ( !in_array($arr['text'], $tweets) ){
                    $tweets[] = $arr['text'];
                }
            }
      
            //load the dictionary
            $pspell = pspell_new($dict);

            //now we'll go through the tweets and check spelling
            $gs = 0;
            $ts = 0;
            foreach($tweets as $key => $tweet){
                $words = explode(" ", $tweet);
                $good = array();
                foreach($words as $word){
                    //let's make sure it only has letters
                    if ( ( ( !preg_match('/[^A-Za-z]/', $word) ) && !empty($word) ) && ( strlen($word) >= 3 ) ){
                        $good[] = $word;
                    }
                }
                $max = count($good);
                $w = array();
                if ( $max >= 10 ){
                    $run = 10;
                } else {
                    $run = $max;
                }
                while ( $run ){
                    if ( !array_key_exists($c=mt_rand(0,$max), $w) ){
                        if ( pspell_check($pspell, $good[$c]) ){
                            $w[$c] = true;
                            $gs++;
                        } else {
                            $w[$c] = false;
                        }
                        $ts++;
                        $run--;
                    }
                }
                $tweets[$key] = array(
                    "tweet" => $tweet,
                    "spelling" => $w,
                );
            }
            //$main->doError(print_r($tweets,1));
            if ( $ts != 0 ){
                $score[$k]['spelling'] = ceil( ( $gs / $ts ) * 100 );
            } else {
                $score[$k]['spelling'] = 0;
            }

            //now we'll go through the tweets and simply count those from the last 30 days that are happy
            $s[] = ":)";
            $result = self::searchString($s, $lang, $additional, $type="recent");
            $result = json_decode($result, true);
            $tweets = array();
            $compare = array();
            $rbl = $main->config->get('twitter.blacklist');
            $blacklist = explode(",", $rbl);
            foreach($result['statuses'] as $arr){
                //check tweet against blacklist
                foreach($blacklist as $bl){
                    if ( stripos($arr['text'], $bl) !== false ){
                        //$main->doError("Blacklisted string " . $bl . " was found in the tweet: \n" . $arr['text']);
                        continue 2;
                    }
                }
                if ( !in_array($arr['text'], $compare) ){
                    $compare[] = $arr['text'];
                } else {
                    continue;
                }
                $co = strtotime($arr['created_at']);
                $diff = ( time() - (int)$co );
                if ( $diff > ( 86400 * 7 ) ){ //let's go 7 days
                    continue;
                }
                $tweets[] = date("M d Y", $co);
            }
            $score[$k]['happy'] = count($tweets);
        }

        $score['one']['points'] = $score['two']['points'] = 0;
        //now let's figure out the winner
        $sflag = null;
        if ( $score['one']['spelling'] > $score['two']['spelling'] ){
            $score['one']['points']++;
            $sdiff = $score['one']['spelling'] - $score['two']['spelling'];
            $sflag = "one";
        } else if ( $score['two']['spelling'] > $score['one']['spelling'] ){
            $score['two']['points']++;
            $sdiff = $score['two']['spelling'] - $score['one']['spelling'];
            $sflag = "two";
        }  // else it's a tie, no points awarded

        //now let's figure out the winner
        $hflag = null;
        if ( $score['one']['happy'] > $score['two']['happy'] ){
            $score['one']['points']++;
            $hdiff = $score['one']['happy'] - $score['two']['happy'];
            $hflag = "one";
        } else if ( $score['two']['happy'] > $score['one']['happy'] ){
            $score['two']['points']++;
            $hdiff = $score['two']['happy'] - $score['one']['happy'];
            $hflag = "two";
        }  // else it's a tie, no points awarded

        if ( $score['one']['points'] > $score['two']['points'] ){
            $winner = $one;
        } else if ( $score['two']['points'] > $score['one']['points'] ){
            $winner = $two;
        } else { //it's an absolute tie -- let's sort out the difference in point accounting, then fall back on randomness

            if ( $score['one']['points'] != 0 ){
                if ( $sflag == "one" ){ //team one got the spelling point
                    $t1 = ( $sdiff / 3 );
                } else if ( $sflag == "two" ){
                    $t2 = ( $sdiff / 3 );
                }
                //happiness is fundamentally more important than intelligibility
                if ( $hflag == "one" ){ //team one got the happy point
                    $t1 = ( $hdiff * 4 );
                } else if ( $hflag == "two" ){
                    $t2 = ( $hdiff * 4 );
                }
                if ( $t1 > $t2 ){
                    $winner = $one;
                } else {
                    $winner = $two;
                }
            } else {
                //we'll call it a draw
                //$winner = "draw";
                //TODO -- remove this hard-coding, we need to have different API's per sport
                if ( mt_rand(0,1) == 0 ) {
                    $winner = $one;
                } else {
                    $winner = $two;
                }
            }
        }

        $return = array("status" => true, "score" => $score, "winner" => $winner);
        return $return;
    }


	public static function searchString($term, $lang, $additional=null, $type="mixed") {
		$main = \RoboPaul\Initialize::obtain();
		//$main->finishInit();
		$settings = array(
  			'oauth_access_token' => $main->config['twitter']['oauth_access_token'],
    		'oauth_access_token_secret' => $main->config['twitter']['oauth_access_token_secret'],
    		'consumer_key' => $main->config['twitter']['consumer_key'],
    		'consumer_secret' => $main->config['twitter']['consumer_secret']
        );
        //require_once $main->meta['includes']['twitter'];
		$root = $main->config->get('directories.root');
		$url = 'https://api.twitter.com/1.1/search/tweets.json';
		$search = "";
		if ( is_array($term) ){
			foreach($term as $word){
				$search .= urlencode(utf8_encode($word)) . "%20";
			}
			$search = substr($search, 0, -3);
		} else {
			$search = urlencode(utf8_encode($term));
		}
        if ( $additional !== null ){
            $search .= "%20" . urlencode(utf8_encode($additional));
        }
		$getfield = '?q=' . $search . "&lang=" . $lang . "&count=100&result_type=" . $type;
		$requestMethod = 'GET';

		$twitter = new \TwitterAPIExchange($settings);
		$ret = $twitter->setGetfield($getfield)->buildOauth($url, $requestMethod)->performRequest();
		return $ret;
	}

}

?>
