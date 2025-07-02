<?php

class Signup extends Dbh {

    protected function setUser($uid, $pwd, $email) {
        $stmt = $this->connect()->prepare('INSERT INTO admins (username, users_pwd, email) VALUES (?,?,?);');


        $hashedPwd = password_hash($pwd, PASSWORD_DEFAULT);
        if (!$stmt->execute(array($uid, $hashedPwd, $email))) {
            $stmt = null;
            header("location: ../index.php?error=stmtfailed");
            exit();
        }

        $stmt = null;
       
    }
        


    protected function checkUser($uid, $email) {
        $stmt = $this->connect()->prepare('SELECT username FROM admins WHERE username = ? OR email = ?;');
        print_r($this->connect());
        if (!$stmt->execute(array($uid, $email))) {
            $stmt = null;
            header("location: ../index.php?error=stmtfailed");
            exit();
        }

        if ($stmt->rowCount() > 0) {
            return false;
        }
        return true;
    }


}
