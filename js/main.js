function reload(){
	window.location.reload();
}

function runMatchup(one, two){

	$.post('processors/worker.php', { "action": "runMatchup", "one": one, "two": two }, function(ret) {
		if ( ret['status'] == true ){
			console.log(ret);
			var team = team_data[ret['winner']];
			$("#winner-logo").attr('src', 'images/teams/' + team['team'] + '.png');
			$("#winner-logo").show();
			$("#winner-name").text(team['full']);
			$("#winner-name").show();
		} else {
			$("#ajax_error").html(ret['message']);
			$("#ajax_dialog").dialog("open");
		}
		$("#loader").hide();
	}, 'json')
	.fail(function() {
		$("#error_dialog").dialog("open");
	});
}
