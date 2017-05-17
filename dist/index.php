<?php
require_once 'includes/common.php';
session_start();
$_SESSION['PID'] = 3;
if (array_key_exists('debug',$_GET)) {
    $_SESSION['DEBUG'] = true;
} else {
    $_SESSION['DEBUG'] = false;
}
?>
    <!doctype html>
    <html lang=en>

    <head>
        <meta charset="utf-8">
        <meta http-equiv="x-ua-compatible" content="ie=edge">
        <title>Demo Course - Craig Creeger</title>
        <meta name="description" content="">
        <meta name="viewport" content="width=device-width, initial-scale=1.0, minimum-scale=1.0, user-scalable=yes">
        <link href='https://fonts.googleapis.com/css?family=Alegreya+Sans:400,400italic,700' rel='stylesheet' type='text/css'>
        <link href='https://fonts.googleapis.com/css?family=Roboto:900italic' rel='stylesheet' type='text/css'>
        <link href="css/main.css" rel="stylesheet">
        <style>
            /*
            #begin {
                visibility: hidden;
            }
*/
        </style>
    </head>

    <body>
        <div id="footerStickWrap">
            <header>
                <div class="title">Racking Up Wins</div>
                <div class="subTitle">Craig Creeger</div>
            </header>
            <div id="contentPadding">
                <!--[if lt IE 9]>
<p class="callout">You are using an <strong>outdated</strong> browser. Please <a href="http://browsehappy.com/">upgrade your browser</a> to improve your experience.</p>
<![endif]-->
                <p>Enter your email address and name so that you receive credit for taking the course.</p>
                <form action="" method="">
                    <p>
                        <label for=email>Email address</label>
                        <br>
                        <input type=email id=email size=30 value="demo@domain.com">
                    </p>
                    <p>
                        <label for=fullName>Your full name</label>
                        <br>
                        <input type=text id=fullName size=30 value="Demo User">
                    </p>
                    <p>
                        <input type=submit value=Begin id=begin>
                    </p>
                    <output></output>
                </form>
            </div>
        </div>
        <footer>Demo Course — All rights reserved / Craig Creeger / 2015
        </footer>
        <script>
            <?php
            echo 'var PID=' . $_SESSION['PID'] . ';' . PHP_EOL;
            echo 'var MSGCONTACT="' . MSGCONTACT . '";' . PHP_EOL;
        ?>
            var courseLauncher = 'courseLauncher.php';
        </script>
        <script src="vendor/jquery.min.js"></script>
        <script src="js/index.js"></script>
    </body>

    </html>