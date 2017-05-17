<?php
require_once '../../includes/common.php';
makeDbConnection();
session_start();

//DON'T FORGET TO UPDATE THE FOR-LOOP COUNTER BELOW.
$stmt = $db->query("Select p.promotion, l.learnerId, l.email, l.fullName, c.courseName, a.score, if(a.pass, 'Yes', 'No') As passed, a.completionDate
From learners l
Inner Join assignments a On a.learnerId = l.learnerId
Inner Join courses c On c.courseId = a.courseId
Inner Join promotions p On p.pid = l.pid
Where l.pid = " . $_SESSION['PID'] . "
Order by p.promotion, l.learnerId, c.courseName;");

$out = ''; 

// Put the name of all fields to $out. 
$out = 'Promotion, Learner Id, Email, Full Name, Course Name, Score, Passed, Date Passed';
$out .="\n";

// Add all values in the table to $out. 
while ($l = $stmt->fetch()) {
	for ($i = 0; $i < 8; $i++) {
		$curValue = $l[$i];
		$out .='"'.$curValue.'",';
	}
	$out .="\n";
}

$fileResult = file_put_contents ('lms.csv', $out);
if ($fileResult > 0) {	
	header('Content-type: application/csv');
	header('Content-Disposition: attachment; filename="lms.csv"');
	readfile('lms.csv');
} else {
	exit('Unable to write file.');
}
?>