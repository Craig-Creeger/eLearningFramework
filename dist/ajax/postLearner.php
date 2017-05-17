<?php
require_once '../includes/common.php';
require_once INCLUDE_ROOT . 'classes/Learner.php';
session_start();
makeDbConnection();

$email = (array_key_exists('email', $_POST) ? sanitizeString($_POST['email']) : false);
$fullName = (array_key_exists('fullName', $_POST) ? sanitizeString($_POST['fullName']) : false);
$pid = (array_key_exists('pid', $_POST) ? sanitizeString($_POST['pid']) : false);

$learner = Learner::lookup($pid, $email); //returns FALSE or a learner object.
if (!$learner) {
	//Create a new Learner
	$learner = new Learner();
	$learner->email = $email;
	$learner->fullName = $fullName;
	$learner->pid = $pid;
	$learnerId = $learner->save();
	if (!$learnerId) {
		//The save did not work.
		$learner = false;
	}
}
if ($learner) {
    $_SESSION['learnerId'] = $learner->learnerId;
}

header('Content-Type: application/json');
//Returns "false" on error, or a JSON string representing the learner.
echo json_encode($learner);
