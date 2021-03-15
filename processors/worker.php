<?php
//Ajax API script

ini_set('display_errors', '0');
ini_set('log_errors', '1');
ini_set('error_log', '/home/predict/public_html/log/php_error.log');

if ( !isset($_REQUEST['action']) ){
    $return['status'] = false;
    $return['message'] = "Action required";
    echo json_encode($return);
    exit;
}

//require_once "../config/load.php";

header('Content-Type: application/json');

switch ( $_REQUEST['action'] ){

    case "runMatchup":
		//actually do the match up; search Twitter for the school and team names and compare the results according to our whacky metrics
		if ( !isset($_REQUEST['one']) ){
			$return['status'] = false;
			$return['message'] = "Team one is required.";
			echo json_encode($return);
			exit;
		} else if ( !isset($_REQUEST['two']) ){
			$return['status'] = false;
			$return['message'] = "Team two is required.";
			echo json_encode($return);
			exit;
		}

		//let's get the search terms
		$_REQUEST['one'] = str_replace("<h3>", "", $_REQUEST['one']);
		$_REQUEST['one'] = str_replace("</h3>", "", $_REQUEST['one']);
		$_REQUEST['two'] = str_replace("<h3>", "", $_REQUEST['two']);
		$_REQUEST['two'] = str_replace("</h3>", "", $_REQUEST['two']);
		$one = $_REQUEST['one'];
		$two = $_REQUEST['two'];

        $fh = fopen("/home/predict/public_html/log/debug.log", "a");
        $url = "http://api.prediction.ninja/predict/" . urlencode($one) . "/" . urlencode($two);
        $curl = curl_init();
        curl_setopt ($curl, CURLOPT_URL, $url);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($curl, CURLOPT_HEADER, 0);

        $result = curl_exec($curl);
        curl_close($curl);

        if ( json_decode($result) ){ //we can decode it, it's valid
            echo json_decode(json_encode($result),true);
            fwrite($fh, $result . "\n");
            fclose($fh);
        } else {
            $return['status'] = false;
            $return['message'] = "Error has occurred.";
            echo json_encode($return);
        }

		break;
}

?>
