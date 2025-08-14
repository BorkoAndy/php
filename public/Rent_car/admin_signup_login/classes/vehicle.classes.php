<?php
namespace Classes;
class Vehicle extends Dbh{
    private $brand;
    private $model;
    private $year;
    private $license_plate;
    private $status;
    private $price_per_day;
    private $category;
    private $car_photo; 
    
    public function __construct($brand, $model, $year, $license_plate, $status, $price_per_day, $category, $car_photo){
        $this->brand = $brand;
        $this->model = $model;
        $this->year = $year;
        $this->license_plate = $license_plate;
        $this->status = $status;
        $this->price_per_day = $price_per_day;
        $this->category = $category;
        $this->car_photo = $car_photo; 
        }

    public function add_car(){
        $stmt = $this->connect()->prepare
        ('INSERT INTO cars (brand, model, year, license_plate, status, price_per_day, category, car_photo) 
                    VALUES (?,?,?,?,?,?,?,?);');

        if (!$stmt->execute(array($this->brand, $this->model, $this->year, $this->license_plate, $this->status, $this->price_per_day, $this->category, $this->car_photo))) {
            $stmt = null;
            header("location: ../index.php?error=stmtfailed");
            exit();
        }

        $stmt = null;
    }
}
