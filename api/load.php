<?php

    namespace RoboPaul;
    use RoboPaul as rp;
    use RoboPaul\Utility as util;
    use RoboPaul\Database as db;
    use RoboPaul\Exception as exc;
    use RoboPaul\System as sys;

    ini_set('display_errors','1');

    require_once __DIR__.'/vendor/autoload.php';

    $file = __DIR__ . "/config.json";

    $main = Initialize::obtain($file);

    $config = $main->config;
?>
