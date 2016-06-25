<?php
require_once 'coreclasses.php';

/**
 * Repository of methods designed for processing location entities
 */
class LocationRepository {

    /**
     * Get all countries
     * @param bool $onlyActive
     * @return Country[]
     */
    public static function GetCountries($onlyActive = true) {
        global $configuration;

        if($configuration->lang == "rus"){

            $criteria = ($onlyActive == true) ? " where (active = 1) " : "";

            return Utility::getObjectCollectionFromSQL("select * from country " . $criteria . " order by id asc;", 'Country');
        }

        if($configuration->lang == "eng"){

            $criteria = ($onlyActive == true) ? " where (active = 1) " : "";

            return Utility::getObjectCollectionFromSQL("SELECT code as id, name, active FROM country " . $criteria . " order by id asc;", 'Country');
        }
    }

    /**
     * Get country by id
     * @param int $country_id
     * @return Country
     */
    public static function GetCountryById($country_id) {
        global $configuration;

        if($configuration->lang == "rus"){
            return Utility::getObjectFromSQL("select * from country where id = '" . addslashes($country_id) . "' limit 1;", 'Country');
        }

        if($configuration->lang == "eng"){
            return Utility::getObjectFromSQL("SELECT code as id, name, active FROM country  where code = '" . addslashes($country_id) . "' limit 1;", 'Country');
        }
    }

    /**
     * Get regions for country
     * @param int $country_id
     * @return Region[]
     */
    public static function GetRegions($country_id) {
        global $configuration;

        if($configuration->lang == "rus"){
            return Utility::getObjectCollectionFromSQL("select * from region where country_id = '" . addslashes($country_id) . "' order by name asc;", 'Region');
        }

        if($configuration->lang == "eng"){
            return Utility::getObjectCollectionFromSQL("SELECT distinct district as id, countrycode as country_id, district as name FROM city where countrycode = '" . addslashes($country_id) . "' order by district asc;", 'Region');
        }
    }

    /**
     * Get region for country by id
     * @param int $country_id
     * @param int $region_id
     * @return Region
     */
    public static function GetRegionById($country_id, $region_id) {
        global $configuration;

        if($configuration->lang == "rus"){
            return Utility::getObjectFromSQL("select * from region where (country_id = '" . addslashes($country_id) . "') and (id = '" . addslashes($region_id) . "') limit 1;", 'Region');
        }

        if($configuration->lang == "eng"){
            return Utility::getObjectFromSQL("SELECT distinct district as id, countrycode as country_id, district as name FROM city where (countrycode = '" . addslashes($country_id) . "') and (district = '" . addslashes($region_id) . "') limit 1;", 'Region');
        }        
    }

    /**
     * Get cities for region
     * @param int $region_id
     * @return City[]
     */
    public static function GetCities($region_id) {
        global $configuration;

        if($configuration->lang == "rus"){
            return Utility::getObjectCollectionFromSQL("select * from city where region_id = '" . addslashes($region_id) . "' order by name asc;", 'City');
        }

        if($configuration->lang == "eng"){
            return Utility::getObjectCollectionFromSQL("SELECT distinct id, district as region_id, name FROM city where district = '" . addslashes($region_id) . "' order by name asc;", 'City');
        }        
    }

    /**
     * Get city for region by id
     * @param int $region_id
     * @param int $city_id
     * @return City
     */
    public static function GetCityById($region_id, $city_id) {
        global $configuration;

        if($configuration->lang == "rus"){
            return Utility::getObjectFromSQL("select * from city where (region_id = '" . addslashes($region_id) . "') and (id = '" . addslashes($city_id) . "') limit 1;", 'City');
        }

        if($configuration->lang == "eng"){
            return Utility::getObjectFromSQL("SELECT id, district as region_id, name FROM city where id = '" . addslashes($city_id) . "' limit 1;", 'City');
        }        
    }

}
?>
