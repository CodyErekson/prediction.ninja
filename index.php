<?php
//Social media bracket calculator

require_once "template/head.php";

require_once "array.php";

$team_data = sortData($south);
$team_data = array_merge($team_data, sortData($west));
$team_data = array_merge($team_data, sortData($east));
$team_data = array_merge($team_data, sortData($midwest));

$options = '<optgroup label="South">' . buildOptions($south) . '</optgroup>';
$options .= '<optgroup label="East">' . buildOptions($east) . '</optgroup>';
$options .= '<optgroup label="Midwest">' . buildOptions($midwest) . '</optgroup>';
$options .= '<optgroup label="West">' . buildOptions($west) . '</optgroup>';

//$options = buildOptions($south);

// Find a better place for this
function buildOptions($array)
{
	$out = '';
	foreach($array as $id => $team){
		$out .= "/n" . '<option data-img-src="images/teams/' . $team['team'] . '.png" value="'. $team['team'] .'">' . $team['full'] . '</option>';
	}
	return $out;
}

function sortData($array)
{
	foreach($array as $id => $team){
		unset($array[$id]);
		$array[$team['team']] = $team;
	}
	return $array;
}

?>

        <!-- Banner Wrapper -->
            <div id="banner-wrapper">
                <div class="container">
                    <div class="row">
                        <div class="12u">

                            <!-- Banner -->
                                <div id="banner" class="box">

                                    <div>
                                        <div class="row">
                                            <div class="6u">
												<select multiple="multiple" name="teams" id="teams" class="team_select" data-placeholder="Choose 2 Teams">
													<?php echo $options; ?>
												</select>
                                            </div>
											<div class="1u">
												<button type="button" class="run-button" disabled="disabled">Go!</button>
											</div>
											<div class="5u results">
												<img id="loader" src="images/ajax-loader.gif" />
												<img id="winner-logo" src="images/teams/arizona.png" style="display:none;" />
												<p id="winner-name"></p>
											</div>
                                        </div>
                                    </div>

                                </div>

                        </div>
                    </div>
                </div>
            </div>



<?

    $footer_js .= "
	var team_data = " . json_encode($team_data) . ";
	var tcount = 0;
	$(function() {
	
    	$('.team_select').chosen({ 
    		width:\"100%\",
    		max_selected_options: 2 
    	})
    	.change(function() {
    		var val = $('.team_select').val();
      		if ( val == null ){
      			val = [];
      		}
      		console.log(val);
    		if ( val.length != 2 ){
    			$( \".run-button\" ).button(\"disable\");
    		} else {
    			console.log('enabling');
    			$( \".run-button\" ).button(\"enable\");
    		}
    	});
	
		$('.team_select').bind(\"chosen:maxselected\", function() {
			console.log(\"Choose only 2 teams.\");
		});
		
		$( \".run-button\" ).button();
    	$( \".run-button\" ).click( function( event ) {
      		event.preventDefault();
      		$(\"#winner-name\").hide();
      		$(\"#winner-logo\").hide();
      		$(\"#loader\").show();
      		var val = $('.team_select').val();
      		if ( val == null ){
      			val = [];
      		}
      		if ( val.length >= 2 ){
      			$(this).button(\"disable\");
      			$(\"#teams\").prop('disabled', true);
      			$(\"#teams\").trigger(\"chosen:updated\");
      			// Run the lookup
      			runMatchup(val[0], val[1]);
      		}
    	});
	
        $(\"#winner_dialog\").dialog({
            autoOpen: false,
            buttons: {
                Okay: function() {
                    $(this).dialog(\"close\");
					reload();
                }
            }
        });
		$(\"#error_dialog\").dialog({
			autoOpen: false,
			buttons: {
				Okay: function() {
					$(this).dialog(\"close\");
				}
			}
		});
        $(\"#ajax_dialog\").dialog({
            autoOpen: false,
            buttons: {
                Okay: function() {
                    $(this).dialog(\"close\");
                }
            }
        });
		$(\".teams\").draggable({ revert: \"valid\" });
		$(\"#grabber\").droppable({
			drop: function( event, ui ){
				tcount++;
				if ( tcount > 2 ){
					return;
				}
				var full = '<h3>' + $(ui.draggable).data('full') + '</h3>';
				if ( tcount == 1 ){
					$(\"#team_one\").html(full);
					$(ui.draggable).draggable( \"option\", \"disabled\", true );
				} else if ( tcount == 2 ){
					$(\"#team_two\").html(full);
					$(\".teams\").draggable( \"option\", \"disabled\", true );
					var one = $(\"#team_one\").find(\"h3\").html();
					var two = $(ui.draggable).data('full');
					runMatchup(one, two);
				}
			}
		});
    });
    ";
	

require_once "template/foot.php";

?>
