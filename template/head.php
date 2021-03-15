<?php
$footer_js = "";

?>
<!DOCTYPE HTML>
<!--
    Verti 2.5 by HTML5 UP
    html5up.net | @n33co
    Free for personal and commercial use under the CCA 3.0 license (html5up.net/license)
-->
<html>
    <head>
        <title>RoboPaul: <?php echo date('Y'); ?> NCAA Tournament Bracket Calculator</title>
        <meta http-equiv="content-type" content="text/html; charset=utf-8" />
        <meta name="description" content="Social media bracket predictor." />
        <meta name="keywords" content="social media sports bracket prediction" />
		<link rel="shortcut icon" type="image/x-icon" href="images/favicon.ico">
        <link href="http://fonts.googleapis.com/css?family=Open+Sans:300,800" rel="stylesheet" type="text/css" />
        <link href="http://fonts.googleapis.com/css?family=Oleo+Script:400" rel="stylesheet" type="text/css" />
		<link href="css/flick/jquery-ui-1.10.4.custom.css" rel="stylesheet">
		<link href="css/chosen.css" rel="stylesheet">
		<link rel="stylesheet" href="css/style.css" />
		<script
				src="https://code.jquery.com/jquery-1.12.4.min.js"
				integrity="sha256-ZosEbRLbNQzLpnKIkEdrPv7lOy9C27hHQ+Xp8a4MxAQ="
				crossorigin="anonymous"></script>
        <script src="js/config.js"></script>
		<script src="js/main.js"></script>
		<script src="js/chosen.jquery.js"></script>
		<script src="js/ImageSelect.jquery.js"></script>
        <script src="js/skel.min.js"></script>
        <script src="js/skel-panels.min.js"></script>
		<script src="js/jquery-ui-1.10.4.custom.js"></script>
        <noscript>
            <link rel="stylesheet" href="css/skel-noscript.css" />

            <link rel="stylesheet" href="css/style-desktop.css" />
        </noscript>
        <!--[if lte IE 8]><script src="js/html5shiv.js"></script><link rel="stylesheet" href="css/ie8.css" /><![endif]-->
        <!--[if lte IE 7]><link rel="stylesheet" href="css/ie7.css" /><![endif]-->
    </head>
    <body class="homepage">

    <div id="error_dialog" title="Error">
        <p>
            <span class="ui-icon ui-icon-circle-check" style="float:left; margin:0 7px 50px 0;"></span>
            An error has occurred.  Please try again.
        </p>
    </div>
    <div id="ajax_dialog" title="Error">
        <p>
            <span class="ui-icon ui-icon-circle-check" style="float:left; margin:0 7px 50px 0;"></span>
            <span id="ajax_error"><span>  Please try again.
        </p>
    </div>
    <div id="winner_dialog" title="Predicted Winner:">
        <p>
            <span class="ui-icon ui-icon-info" style="float:left; margin:0 7px 50px 0;"></span>
            <span id="winner_spot"><span>
        </p>
    </div>

        <!-- Header Wrapper -->
            <div id="header-wrapper">
                <div class="container">
                    <div class="row">
                        <div class="12u">

                            <!-- Header -->
                                <header id="header">

                                    <!-- Logo -->
                                        <div id="logo">
                                            <h1><a href="javascript:void(0);" id="logo">RoboPaul: <?php echo date('Y'); ?> NCAA Bracket Calculator</a></h1>
                                        </div>

                                    <!-- Nav -->
                                        <nav id="nav">
                                            <ul>
                                                <li class="current_page_item"><a href="javascript:reload();">Reset</a></li>
                                            </ul>
                                        </nav>

                                </header>

                        </div>
                    </div>
                </div>
            </div>
