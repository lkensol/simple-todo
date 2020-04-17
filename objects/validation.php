<?php

require_once "db.php";

class Validation {

    public function check_empty($data, $fields)
    {
        $msg = null;
        foreach ($fields as $value) {
            if (empty(trim($data[$value]))) {
                $msg .= "$value the field is empty <br />";
            }
        } 
        return $msg;
    }
    
    public function isNameValid($name) {
        $valid = TRUE;
        $len = mb_strlen($name);
        if($len < 3 || $len>16) {
            $valid = FALSE;
        }
        return $valid;
    }

    public function isPasswordValid($pwd) {
        $valid = TRUE;
        $len = mb_strlen($pwd);
        if($len < 6 || $len>16) {
            $valid = FALSE;
        }
        return $valid;
    }
}