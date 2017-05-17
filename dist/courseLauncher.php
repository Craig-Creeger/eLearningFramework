<?php
require_once 'includes/common.php';
require_once INCLUDE_ROOT . 'classes/Assignment.php';
session_start();
makeDbConnection();

$allRows = Assignment::getAllByLearner((array_key_exists('learnerId', $_SESSION)) ? $_SESSION['learnerId'] : 0);

function courseStatusMessage($score, $pass) {
    if (is_null($score)) {
        return '';
    } elseif ($pass) {
        return '<img src="img/greenCheckmark.svg" width=16 height=16>';
    } else {
        return '<span style="color:darkRed; font-size:larger; font-weight:bold;">∅</span>';
    }
}

?>
<!doctype html>
<html lang=en>

<head>
    <meta charset="utf-8">
    <meta http-equiv="x-ua-compatible" content="ie=edge">
    <title>Intel Training Course</title>
    <meta name="description" content="">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, minimum-scale=1.0, user-scalable=yes">
        <link href='https://fonts.googleapis.com/css?family=Alegreya+Sans:400,400italic,700' rel='stylesheet' type='text/css'>
        <link href='https://fonts.googleapis.com/css?family=Roboto:900italic' rel='stylesheet' type='text/css'>
    <link href="css/main.css" rel="stylesheet">
    <style>
        .layoutTable td {
            padding-bottom: 1em;
        }
    </style>
</head>

<body>
    <div id="footerStickWrap">
            <header>
                <div class="title">Racking Up Wins</div>
                <div class="subTitle">Craig Creeger</div>
            </header>
        <div id="contentPadding">
            <p>Choose a course that you would like to take.</p>
            <table class="layoutTable">
                <?php
for ($i=0; $i<count($allRows); $i++) {
echo '<tr>' . PHP_EOL .
    '<td style="width:3em;" class="center" data-courseId=' . $allRows[$i]->course->courseId . '>' .
    courseStatusMessage($allRows[$i]->assignment->score, $allRows[$i]->assignment->pass) . '</td>' . PHP_EOL .
    '<td><a href="course.php?courseId=' . $allRows[$i]->course->courseId . '" class="subtleXX">' . 
    htmlspecialchars ($allRows[$i]->course->courseName) . '</a></td>' . PHP_EOL . 
    '</tr>';
}
    ?>
            </table>
            <h2>Source Code</h2>
<p>All the source code for this application can be <a href="eLearningFramework.zip">downloaded from this page.</a></p>
        </div>
    </div>
        <footer>Demo Course — All rights reserved / Craig Creeger / 2015
        </footer> 
    <script>
        <?php
            echo 'var MSGCONTACT = "' . MSGCONTACT . '";' . PHP_EOL;
        ?>
    </script>
    <script src="vendor/jquery.min.js"></script>
</body>

</html>