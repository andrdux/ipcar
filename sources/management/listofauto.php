<?php

require_once 'init.php';
require_once '../includes/functions/autorepository.php';

TemplateManager::RegisterHeaderFile("listofautoactions.js");

$page = Utility::getPageParam("page", 0);
$numOfPages = 0;

$listingTypes = array("all" =>Utility::getlocaltext("filter_all"),
                      "new" => Utility::getlocaltext("filter_new"),
                      "approved" => Utility::getlocaltext("filter_approved"),
                      "refused" =>Utility::getlocaltext("filter_refused"));

$autoTypes = array("all" => Utility::getlocaltext("filter_all"),
                   "notsold" => Utility::getlocaltext("filter_notsold"),
                   "sold" => Utility::getlocaltext("filter_sold"));

$listingType = Utility::getPageParam("listingType", "new");
$autoType = Utility::getPageParam("autoType", "all");

$action = Utility::getPageParam("action", "show");
switch (strtolower($action)) {
    case "show":
        $autoFilter = new AutoFilter();

        switch($listingType){
            case "all":
                break;
            case "new":
                $autoFilter->approved = ApprovedStatus::Validating;
                break;
            case "approved":
                $autoFilter->approved = ApprovedStatus::Approved;
                break;
            case "refused":
                $autoFilter->approved = ApprovedStatus::Refused;
                break;
        }

        switch($autoType){
            case "all":
                break;
            case "notsold":
                $autoFilter->sold = 0;
                break;
            case "sold":
                $autoFilter->sold = 1;
                break;
        }

        $listOfAuto = AutoRepository::GetAutoWithFilter($autoFilter, &$numOfPages, $page);

        $pagesFrom = (floor($page / $configuration->numOfPagesToDisplay) * $configuration->numOfPagesToDisplay) + 1;
        $pagesTo = (($pagesFrom + $configuration->numOfPagesToDisplay) > ($numOfPages + 1)) ? $numOfPages + 1 : $pagesFrom + $configuration->numOfPagesToDisplay;

        TemplateManager::Assign("scriptName", "listofauto.php");
        TemplateManager::Assign("numOfPages", $numOfPages);
        TemplateManager::Assign("page", $page);
        TemplateManager::Assign("pagesFrom", $pagesFrom);
        TemplateManager::Assign("pagesTo", $pagesTo);
        TemplateManager::Assign("listOfAuto", $listOfAuto);
        TemplateManager::Assign("listingTypes", $listingTypes);
        TemplateManager::Assign("autoTypes", $autoTypes);
        TemplateManager::Assign("listingType", $listingType);
        TemplateManager::Assign("autoType", $autoType);
        TemplateManager::Assign("searchparams", "listingType=".$listingType."&autoType=".$autoType);

        TemplateManager::DisplayLayout("ListOfAuto");
        break;
}

?>



