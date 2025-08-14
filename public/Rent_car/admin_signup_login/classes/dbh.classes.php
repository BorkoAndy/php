<?php

class Dbh {
    protected function connect () {
        try {
            //change db name, username and password
            $username = 'root';
            $password = '';
            $dbh = new PDO('mysql:host=localhost;dbname=car_rent', $username, $password);            
            return $dbh;

        } catch (PDOException $e) {
            print "Error!: " . $e->getMessage() . "<br/>";
            die();
        }
    }
}
