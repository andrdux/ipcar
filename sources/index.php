<?php

require_once 'init.php';
require_once 'includes/functions/autorepository.php';
require_once 'includes/functions/userrepository.php';
require_once 'includes/functions/locationrepository.php';


TemplateManager::RegisterHeaderFile("main.css");
TemplateManager::RegisterHeaderFile("jquery-1.4.4.min.js");
TemplateManager::RegisterHeaderFile("autoNumeric-1.5.1.js");
TemplateManager::RegisterHeaderFile("utility.js");
TemplateManager::RegisterHeaderFile("jquery.blockUI.js");
TemplateManager::RegisterHeaderFile("initblockUI.js");
TemplateManager::RegisterHeaderFile("automarketactions.js");

$searchFilter = new SearchAutoCriteria();
$searchFilter = AutoRepository::ValidateAndFixSearchCriteria($searchFilter);
$searchFilter->showsold = 1;

$listOfAuto = null;

$page = Utility::getPageParam("page", 0);

$action = Utility::getPageParam("action", "show");
switch (strtolower($action)) {
    case "show":
        $marks = AutoRepository::GetAutoMarks();
        TemplateManager::Assign("marks", $marks);

        $qsmarks = AutoRepository::GetAutoMarksWithLogo();
        TemplateManager::Assign("qsmarks", $qsmarks);

        $models = AutoRepository::GetAutoModels($searchFilter->id_mark);
        TemplateManager::Assign("models", $models);

        $transmissions = AutoRepository::GetTransmission();
        TemplateManager::Assign("transmissions", $transmissions);

        $carcases = AutoRepository::GetCarCases();
        TemplateManager::Assign("carcases", $carcases);

        $fuel = AutoRepository::GetFuel();
        TemplateManager::Assign("fuel", $fuel);

        $kpp = AutoRepository::GetKpp();
        TemplateManager::Assign("kpp", $kpp);

        $color = AutoRepository::GetColors();
        TemplateManager::Assign("color", $color);

        $autostates = AutoRepository::GetAutoStates();
        TemplateManager::Assign("autostates", $autostates);

        $countries = LocationRepository::GetCountries();
        TemplateManager::Assign("countries", $countries);

        $regions = LocationRepository::GetRegions($searchFilter->country_id);
        TemplateManager::Assign("regions", $regions);

        $cities = LocationRepository::GetCities($searchFilter->region_id);
        TemplateManager::Assign("cities", $cities);

        $listOfAuto = AutoRepository::GetNewAddedAuto($configuration->numberOfNewAutoToDisplayOnAutoMarketPage);
        TemplateManager::Assign("listOfAuto", $listOfAuto);
        
        TemplateManager::Assign("searchfilter", $searchFilter);
        TemplateManager::DisplayLayout("AutoMarket");
        break;
    case "searchcheaperauto":

        $autoid = Utility::getPageParam("autoid", 0);
        $auto = AutoRepository::GetAuto($autoid);

        if($auto == null){
            Utility::redirectToUrl("/");
        }

        $searchFilter = new SearchAutoCriteria();
        $searchFilter->id_mark = $auto->id_mark;
        $searchFilter->id_model = $auto->id_model;
        $searchFilter->country_id = $auto->country_id;
        $searchFilter->region_id = $auto->region_id;
        $searchFilter->city_id = $auto->city_id;
        $searchFilter->priceTo = $auto->price - 1;
        $searchFilter = AutoRepository::ValidateAndFixSearchCriteria($searchFilter);

        $numOfPages = 0;

        $listOfAuto = AutoRepository::SearchAuto($searchFilter, &$numOfPages, $page);

        $pagesFrom = (floor($page / $configuration->numOfPagesToDisplay) * $configuration->numOfPagesToDisplay) + 1;
        $pagesTo = (($pagesFrom + $configuration->numOfPagesToDisplay) > ($numOfPages + 1)) ? $numOfPages + 1 : $pagesFrom + $configuration->numOfPagesToDisplay;

        $searchparams = Utility::objectToUrlParamsString($searchFilter);

        TemplateManager::Assign("searchfilter", $searchFilter);
        TemplateManager::Assign("listOfAuto", $listOfAuto);

        TemplateManager::Assign("scriptName", "/");
        TemplateManager::Assign("numOfPages", $numOfPages);
        TemplateManager::Assign("page", $page);
        TemplateManager::Assign("pagesFrom", $pagesFrom);
        TemplateManager::Assign("pagesTo", $pagesTo);
        TemplateManager::Assign("searchparams", $searchparams);

        if ($searchFilter->id_mark > 0) {
            $qsselectedmark = AutoRepository::GetAutoMarkById($searchFilter->id_mark);
            TemplateManager::Assign("qsselectedmark", $qsselectedmark);

            $qsmodels = AutoRepository::GetAutoModels($searchFilter->id_mark);
            TemplateManager::Assign("qsmodels", $qsmodels);
        }

        $qsmarks = AutoRepository::GetAutoMarksWithLogo();
        TemplateManager::Assign("qsmarks", $qsmarks);

        TemplateManager::DisplayLayout("SearchResult");
        break;
    case "search":
        $searchFilter = Utility::getObjectPropertiesFromForm($searchFilter);
        $searchFilter = AutoRepository::ValidateAndFixSearchCriteria($searchFilter);

        $numOfPages = 0;

        $listOfAuto = AutoRepository::SearchAuto($searchFilter, &$numOfPages, $page);

        $pagesFrom = (floor($page / $configuration->numOfPagesToDisplay) * $configuration->numOfPagesToDisplay) + 1;
        $pagesTo = (($pagesFrom + $configuration->numOfPagesToDisplay) > ($numOfPages + 1)) ? $numOfPages + 1 : $pagesFrom + $configuration->numOfPagesToDisplay;

        $searchparams = Utility::objectToUrlParamsString($searchFilter);

        TemplateManager::Assign("searchfilter", $searchFilter);
        TemplateManager::Assign("listOfAuto", $listOfAuto);

        TemplateManager::Assign("scriptName", "/");
        TemplateManager::Assign("numOfPages", $numOfPages);
        TemplateManager::Assign("page", $page);
        TemplateManager::Assign("pagesFrom", $pagesFrom);
        TemplateManager::Assign("pagesTo", $pagesTo);
        TemplateManager::Assign("searchparams", $searchparams);

        if ($searchFilter->id_mark > 0) {
            $qsselectedmark = AutoRepository::GetAutoMarkById($searchFilter->id_mark);
            TemplateManager::Assign("qsselectedmark", $qsselectedmark);

            $qsmodels = AutoRepository::GetAutoModels($searchFilter->id_mark);
            TemplateManager::Assign("qsmodels", $qsmodels);
        }

        $qsmarks = AutoRepository::GetAutoMarksWithLogo();
        TemplateManager::Assign("qsmarks", $qsmarks);

        TemplateManager::DisplayLayout("SearchResult");

        break;
}
?>
