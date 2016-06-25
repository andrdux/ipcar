<?php

require_once '../init.php';
require_once '../includes/functions/autorepository.php';
require_once '../includes/functions/userrepository.php';
require_once '../includes/functions/locationrepository.php';


$action = Utility::getPageParam("action");

switch ($action) {
    case "loadAutoModels":
        $idmark = Utility::getPageParam("idmark", -1);
        echo Utility::getJSON(AutoRepository::GetAutoModels($idmark));
        break;
    case "loadRegions":
        $idcountry = Utility::getPageParam("idcountry", -1);
        echo Utility::getJSON(LocationRepository::GetRegions($idcountry));
        break;
    case "loadCities":
        $idregion = Utility::getPageParam("idregion", -1);
        echo Utility::getJSON(LocationRepository::GetCities($idregion));
        break;
    case "loadModifications":
        $modelid = Utility::getPageParam("modelid", -1);
        echo Utility::getJSON(AutoRepository::GetUserAutoModifications($modelid, $configuration->max_size_of_autocomplete));
        break;
}

//TODO: Add autocomplete for modifications
?>
