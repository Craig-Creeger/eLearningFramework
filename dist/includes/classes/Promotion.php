<?php

class Promotion {
    public $pid;
    public $promotion;
    public $buildDate;

    public function __construct($pid = false) {
        if ($pid) {
            $this->load($pid);
        } else {
            $this->promotion = null;
            $this->buildDate = null;
        }
    }

    public function load($pid) {
        global $db;
        try {
            $stmt = $db->prepare("Select * From promotions Where pid=?;");
            $stmt->execute(array($pid));
            if ($row = $stmt->fetchObject()) {
                $this->promotion = $row->promotion;
                $this->buildDate = $row->buildDate;
                $stmt->closeCursor();
                return $this->pid;
            } else {
                return false;
            }
        } catch(PDOException $e) {
            crcDebug('Promotion / Load() / ' . $e->getMessage());
        }
    }

}
