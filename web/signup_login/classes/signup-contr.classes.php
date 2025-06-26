<?php

class SignupContr extends Signup{

    private $uid;        
    private $pwd;
    private $pwdrepeat;
    private $email;

    public function __construct($uid, $pwd, $pwdrepeat, $email)
    {
        $this->uid = $uid;
        $this->pwd = $pwd;
        $this->pwdrepeat = $pwdrepeat;
        $this->email = $email;

    }

    public function signupUser(){
        if ($this->emptyInput() == false) {
            header("location: ../index.php?error=emptyinput");
            exit();
        }
        if ($this->invalidUid() == false) {
            header("location: ../index.php?error=username");
            exit();
        }
        if ($this->invalidEmail() == false) {
            header("location: ../index.php?error=email");
            exit();
        }
        if ($this->pwdMatch() == false) {
            header("location: ../index.php?error=passwordMatch");
            exit();
        }
        if ($this->uidTakenCheck() == false) {
            header("location: ../index.php?error=useroremailtaken");
            exit();
        }

        $this->setUser($this->uid, $this->pwd, $this->email);
    }
    //Validation of fields, checking pwd and repeatpwd etc

    private function emptyInput(){
        if(empty($this->uid) || empty($this->pwd) || empty($this->pwdrepeat) || empty($this->email)){
            return false;        
        }
        return true;
    }

    private function invalidUid(){
        if(!preg_match("/^[a-zA-Z0-9]*$/", $this->uid)){
            return false;        
        }
        return true;
    }

    private function invalidEmail(){
        if(!filter_var($this->email, FILTER_VALIDATE_EMAIL)){
            return false;        
        }
        return true;
    }
    
    private function pwdMatch(){
        if($this->pwd !== $this->pwdrepeat){
            return false;        
        }
        return true;
    }

     private function uidTakenCheck(){
        if(!$this->checkUser($this->uid, $this->email)){
            return false;        
        }
        return true;
    }
}
