<?php
//Route processor

    use RoboPaul;
    use RoboPaul\Utility;

    ini_set('display_errors','1');

    require_once __DIR__.'/vendor/autoload.php';

    $file = __DIR__ . "/config.json";

    $main = RoboPaul\Initialize::obtain($file);

    $config = $main->config;

Flight::route('GET /predict/@team1/@team2', function($team1, $team2) {
    header("Access-Control-Allow-Origin: http://prediction.ninja");
    $result = Robopaul\Utility\Twitter::Predict($team1,$team2);
    Flight::json($result);
});

Flight::route('GET /test/@team1/@team2', function($team1, $team2) {
    $result = RoboPaul\Utility\Twitter::Test($team1,$team2);
    Flight::json($result);
});

Flight::route('GET /myIP', function() {
    $ip = $_SERVER['REMOTE_ADDR'];
    Flight::json($ip);
});

Flight::start();

?>
