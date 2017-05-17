<?php
require_once INCLUDE_ROOT . 'classes/Assignment.php';

/** If this framework is used multiple times for various promotions, then it is very likely that the same 
  * person will be duplicated in the "learners" table. However, they are made unique via the Promotion ID
  * (learners.pid).
  */
class Learner {
    public $learnerId; //Primary Key
    public $pid;       //Unique Key (pid/email)
    public $email;     //Unique Key (pid/email)
    public $fullName;

    public function __construct($learnerId = false) {
        if ($learnerId) {
            $this->load($learnerId);
        }
    }

    public function load($learnerId) {
        global $db;
        $this->learnerId = $learnerId;
        try {
            $stmt = $db->prepare("Select * From learners Where learnerId=?;");
            $stmt->execute(array($learnerId));
            if ($row = $stmt->fetchObject()) {
                $this->pid = $row->pid;
                $this->email = $row->email;
                $this->fullName = $row->fullName;
                $stmt->closeCursor();
                return $this->learnerId;
            } else {
                return false;
            }
        } catch(PDOException $e) {
            crcDebug('Learner / Load() / ' . $e->getMessage());
        }
    }

    public function save() {
        global $db;
        if ($this->learnerId) {
            //Already exists so do an update.
            try {
                $stmt = $db->prepare("Update learners Set email = ?, fullName = ?, pid = ? Where learnerId = ?;");
                $stmt->execute(array($this->email, $this->fullName, $this->learnerId, $this->pid));
                $stmt->closeCursor();
                return $this->learnerId;
            } catch(PDOException $e) {
                crcDebug('Learner / save() update / '. $e->getMessage());
                return false;
            }
        } else {
            //New row so do an insert.
            try {
                $stmt = $db->prepare('Insert learners (pid, email, fullName) Values (?, ?, ?);');
                if ($stmt->execute(array($this->pid, $this->email, $this->fullName))) {
                    $this->learnerId = $db->lastInsertId();
                }
                $stmt->closeCursor();
                
                //Whenever a new Learner has been created they will automatically get assigned to the 
                //courses in the current promotion.
                Assignment::assignCourses($this->learnerId);
                
                return $this->learnerId;
            } catch(PDOException $e) {
                crcDebug('Learner / save() insert / ' . $e->getMessage());
                return false;
            }
        }
    }
    
    static function lookup($pid, $email) {
        global $db;
        try {
            $stmt = $db->prepare("Select * From learners Where pid = ? and email = ?;");
            $stmt->execute(array($pid, $email));
            if ($row = $stmt->fetchObject()) {
                $stmt->closeCursor();
                return new Learner($row->learnerId);
            } else {
                $stmt->closeCursor();
                return false;
            }
        } catch(PDOException $e) {
            crcDebug('Learner / lookup() ' . $e->getMessage());
            return false;
        }
    }
}
