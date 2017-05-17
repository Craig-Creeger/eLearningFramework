<?php
class Course {
    public $courseId;
    public $pid;
    public $courseName;
    public $passingScore;

    public function __construct($courseId = false) {
        if ($courseId) {
            $this->load($courseId);
        }
    }

    public function load($courseId) {
        global $db;
        $this->courseId = $courseId;
        try {
            $stmt = $db->prepare("Select * From courses Where courseId=?;");
            $stmt->execute(array($courseId));
            if ($row = $stmt->fetchObject()) {
                $this->pid = $row->pid;
                $this->courseName = $row->courseName;
                $this->passingScore = $row->passingScore;
                $stmt->closeCursor();
                return $this->courseId;
            } else {
                return false;
            }
        } catch(PDOException $e) {
            crcDebug('Course / Load() / ' . $e->getMessage());
        }
    }

    public function save() {
        global $db;
        if ($this->courseId) {
            //Already exists so do an update.
            try {
                $stmt = $db->prepare("Update courses Set pid = ?, courseName = ?, passingScore = ? Where courseId = ?;");
                $stmt->execute(array($this->pid, $this->courseName, $this->passingScore, $this->courseId));
                $stmt->closeCursor();
                return $this->courseId;
            } catch(PDOException $e) {
                crcDebug('Course / save() update / '. $e->getMessage());
                return false;
            }
        } else {
            //New row so do an insert.
            try {
                $stmt = $db->prepare('Insert courses (pid, email, courseName, passingScore) Values (?, ?, ?, ?);');
                if ($stmt->execute(array($this->pid, $this->email, $this->courseName, $this->passingScore))) {
                    $this->courseId = $db->lastInsertId();
                }
                $stmt->closeCursor();
                return $this->courseId;
            } catch(PDOException $e) {
                crcDebug('Course / save() insert / ' . $e->getMessage());
                return false;
            }
        }
    }
    
    static function getAllByPromotion($pid) {
        //Returns an array of courses for a given promotion
        global $db;
        try {
            $stmt = $db->prepare("Select courseId From courses Where pid = ?;");
            $stmt->execute(array($pid));
            $allRows = array();
            while ($row = $stmt->fetchObject()) {
                $allRows[] = new Course($row->courseId);
            }
            $stmt->closeCursor();
            return $allRows;
        } catch(PDOException $e) {
            crcDebug('Course / getAllByPromotion() / ' . $e->getMessage());
            return array(); //pass back an empty array.
        }
    }
}
