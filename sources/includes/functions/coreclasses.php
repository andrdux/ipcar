<?php

/**
 * represents auto table
 */
class Auto {

    //Table fields
    public $id;
    public $id_mark;
    public $id_model;
    public $modification;
    public $user_id;
    public $autostate_id;
    public $carcase_id;
    public $color_id;
    public $fuel_id;
    public $fuelsupply_id;
    public $kpp_id;
    public $transmission_id;
    public $country_id;
    public $region_id;
    public $city_id;
    public $price;
    public $year;
    public $mileage;
    public $volume;
    public $power;
    public $consumption;
    public $acceleration;
    public $cylinders;
    public $description;
    public $exchange;
    public $tuning;
    public $tuningdesc;
    public $notcustoms;
    public $urgent;
    public $sold;
    public $updated;
    public $approved;
    public $premiumstatus;
    //Fields to save in related tables
    public $photos;
    public $equipment;
    //Additional fields for easy visualisation
    public $mark_name;
    public $model_name;
    public $user_fio;
    public $user_email;
    public $user_phone1;
    public $user_phone2;
    public $autostate_name;
    public $carcase_name;
    public $color_name;
    public $fuel_name;
    public $fuelsupply_name;
    public $kpp_name;
    public $transmission_name;
    public $city_name;
    public $photoname;
    public $smallphotoname;
    public $bigphotoname;
    public $photodir;

}

/**
 * represents automark table
 */
class AutoMark {

    public $id_mark;
    public $mark_name;
    public $mark_logo;
    public $name;
    public $adress;
    public $info;

}

/**
 * represents automodel table
 */
class AutoModel {

    public $id_model;
    public $id_mark;
    public $model_name;

}

/**
 * represents automodif table
 */
class AutoModif {

    public $id_modif;
    public $id_model;
    public $id_photo;
    public $id_mark;
    public $name;
    public $details;
    public $volume;
    public $nmbrdoors;
    public $power;
    public $tank;
    public $time;
    public $maxspeed;
    public $start;
    public $stop;

}

/**
 * represents autostate table
 */
class AutoState {

    public $id;
    public $parent_id;
    public $name;
    public $index;

}

/**
 * represents auto_equipment table
 */
class AutoEquipment {

    public $auto_id;
    public $equipment_id;

}

/**
 * represents carcase table
 */
class CarCase {

    public $id;
    public $parent_id;
    public $name;
    public $index;

}

/**
 * represents city table
 */
class City {

    public $id;
    public $region_id;
    public $name;

}

/**
 * represents color table
 */
class Color {

    public $id;
    public $name;
    public $index;

}

/**
 * represents country table
 */
class Country {

    public $id;
    public $name;
    public $active;

}

/**
 * represents equipment table
 */
class Equipment {

    public $id;
    public $name;
    public $index;

}

/**
 * represents fuel table
 */
class Fuel {

    public $id;
    public $parent_id1;
    public $parent_id2;
    public $name;
    public $index;

}

/**
 * represents fuelsupply table
 */
class FuelSupply {

    public $id;
    public $parent_id;
    public $name;
    public $index;

}

/**
 * represents kpp table
 */
class Kpp {

    public $id;
    public $parent_id;
    public $name;
    public $index;

}

/**
 * represents photo table
 */
class Photo {

    public $id;
    public $auto_id;
    public $name;
    public $nameoriginal;
    public $pathtodir;
    public $index;
    public $default;
    //additional fields for easy visualization
    public $namesmall;
    public $namebig;
    public $namesmalloriginal;
    public $namebigoriginal;
}

/**
 * represents region table
 */
class Region {

    public $id;
    public $country_id;
    public $name;

}

/**
 * represents transmission table
 */
class Transmission {

    public $id;
    public $name;
    public $index;

}

/**
 * User roles definition
 */
class UserRoles {
    const User = 0;
    const Admin = 1;
}

/**
 * Approved status definition
 */
class ApprovedStatus {
    const Validating = 0;
    const Approved = 1;
    const Refused = 2;
}

/**
 * represents user table
 */
class User {

    public $id;
    public $fio;
    public $email;
    public $password;
    public $phone1;
    public $phone2;
    public $active;
    public $role;
    public $ip;
    public $updated;

}

/**
 * Contains all needed data in PHP session
 */
class SiteSession {

    public function __set($name, $value) {
        $_SESSION[$name] = $value;
    }

    public function __get($name) {
        return $_SESSION[$name];
    }

}

/**
 * Error description
 */
class ErrorInfo {

    public $errortype;  //For example: critical, minor, validation or something else
    public $errorcode;  //Unique code of the error

}

/**
 * UI error description
 */
class UIErrorInfo extends ErrorInfo {

    public $msg;         //error message
    public $control_id;  //id of the contol - source of the error

    function __construct($msg, $control_id) {
        $this->msg = $msg;
        $this->control_id = $control_id;
        $this->errortype = "DataValidation";
    }

}

/**
 * Size of the item
 */
class Size {

    public $width;
    public $height;

    function __construct($width, $height) {
        $this->width = $width;
        $this->height = $height;
    }

}

/**
 * Contains params for search auto
 */
class SearchAutoCriteria {

    public $id_mark;         //Brand
    public $id_model;        //Model
    public $country_id;      //Country
    public $region_id;       //Region
    public $city_id;         //City
    public $priceFrom;       //From price
    public $priceTo;         //To price
    public $yearFrom;        //From year
    public $yearTo;          //To year
    public $volumeFrom;      //From volume
    public $volumeTo;        //To volume
    public $transmission_id; //Transmission
    public $fuel_id;         //Fuel
    public $carcase_id;      //Car case
    public $color_id;        //Color
    public $kpp_id;          //Kpp
    public $autostate_id;    //State of auto
    public $exchangerequired; //With possible exchange
    public $tuningrequired;  //With tuning only
    public $customsrequired; //Only without customs
    public $urgentrequired;  //Only for urgent sell
    public $showsold;        //Show sold
    public $photorequired;   //Auto with photo only

}

/**
 * Contains filter criteria for viewing auto
 */
class AutoFilter {

    public $approved;
    public $sold;
    public $premiumstatus;

}

/**
 * Contains filter criteria for list of users
 */
class UserFilter {

    public $email;
    public $role;

}

?>
