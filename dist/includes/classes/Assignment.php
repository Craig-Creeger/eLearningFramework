<?php
require_once INCLUDE_ROOT . 'classes/Course.php';
require_once INCLUDE_ROOT . 'classes/Learner.php';

class Assignment {
    public $learnerId;
    public $courseId;
    public $score;
    public $pass;

    public function __construct($learnerId = false, $courseId = false) {
        if ($courseId && $learnerId) {
            $this->load($learnerId, $courseId);
        } else {
            $this->score = null;
            $this->pass = 0;
        }
    }

    public function load($learnerId, $courseId) {
        global $db;
        $this->learnerId = $learnerId;
        $this->courseId = $courseId;
        try {
            $stmt = $db->prepare("Select * From assignments Where learnerId=? and courseId=?;");
            $stmt->execute(array($learnerId, $courseId));
            if ($row = $stmt->fetchObject()) {
                $this->score = $row->score;
                $this->pass = $row->pass;
                $stmt->closeCursor();
                return $this->courseId;
            } else {
                return false;
            }
        } catch(PDOException $e) {
            crcDebug('Assignment / Load() / ' . $e->getMessage());
        }
    }

    public function save() {
        global $db;
        try {
            //The Replace statement is safe because when it does a Delete there is no Cascade delete.
            if ($this->score > 0) {
                $completionDate = date('Y-m-d H:i:s');
            } else {
                $completionDate = null;
            }
            $stmt = $db->prepare('Replace assignments (learnerId, courseId, score, pass, completionDate) Values (?, ?, ?, ?, ?)');
            $stmt->execute(array($this->learnerId, $this->courseId, $this->score, $this->pass, $completionDate));
            $stmt->closeCursor();
            return true;
        } catch(PDOException $e) {
            crcDebug('Assignment / save() / ' . $e->getMessage());
            return false;
        }

    }
    
    static function getAllByLearner($learnerId) {
        //Returns an array of assignments for a given learner
        global $db;
        try {
            $stmt = $db->prepare("Select courseId From assignments Where learnerId = ?;");
            $stmt->execute(array($learnerId));
            $allRows = array();
            while ($row = $stmt->fetchObject()) {
                $assignCourse = new stdClass();
                $assignCourse->assignment = new Assignment($learnerId, $row->courseId);
                $assignCourse->course = new Course($row->courseId);
                $allRows[] = $assignCourse;
            }
            $stmt->closeCursor();
            return $allRows;
        } catch(PDOException $e) {
            crcDebug('Assignment / getAllByLearner() / ' . $e->getMessage());
            return array(); //pass back an empty array.
        }
    }
    
    static function assignCourses($learnerId) {
        global $db;
        $learner = new Learner($learnerId);
        $allCourses = Course::getAllByPromotion($learner->pid);
        for ($i=0; $i<count($allCourses); $i++) {
            $assignment = new Assignment();
            $assignment->learnerId = $learner->learnerId;
            $assignment->courseId = $allCourses[$i]->courseId;
            $assignment->score = null;
            $assignment->pass = 0;
            $assignment->save();
        }
    }
}
