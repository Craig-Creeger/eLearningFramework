<?php
//This page is called via AJAX POST.
require_once '../includes/common.php';
require_once INCLUDE_ROOT . 'classes/Assignment.php';
session_start();
makeDbConnection();

$pid = (array_key_exists('pid', $_POST) ? sanitizeString($_POST['pid']) : false);
$learnerId = (array_key_exists('learnerId', $_POST) ? sanitizeString($_POST['learnerId']) : false);
$courseId = (array_key_exists('courseId', $_POST) ? sanitizeString($_POST['courseId']) : false);
$score = (array_key_exists('score', $_POST) ? sanitizeString($_POST['score']) : false);
$pass = (array_key_exists('pass', $_POST) ? sanitizeString($_POST['pass']) : 0);
if ($pass === 'true') {
    $pass = 1;
} else {
    $pass = 0;
}

header('Content-Type: application/json');
if ($pid == $_SESSION['PID'] && $learnerId == $_SESSION['learnerId']) {
    //If you made it this far then it is unlikely a hacker is messing with this POST.
	$ass = new Assignment($learnerId, $courseId);
    //Only update if the new score is higher than the old score.
    if ($score > $ass->score) {
        $ass->score = $score;
        $ass->pass = $pass;
        $return = $ass->save();
    } else {
        $return = true;
    }
    if ($return) {
        echo json_encode(array("response"=>"OK"));
    } else {
        echo json_encode(array("response"=>"Save failed"));
    }
} else {
    echo json_encode(array("response"=>"Session mismatch"));
}