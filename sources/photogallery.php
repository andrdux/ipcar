<?php

require_once 'init.php';
require_once 'includes/functions/autorepository.php';
require_once 'includes/functions/userrepository.php';

TemplateManager::RegisterHeaderFile("photogallery.css");
TemplateManager::RegisterHeaderFile("jquery-1.4.4.min.js");
TemplateManager::RegisterHeaderFile("utility.js");
TemplateManager::RegisterHeaderFile("photogalleryactions.js");

$loggedUser = UserRepository::GetLoggedUser();

$action = Utility::getPageParam("action", "show");
switch (strtolower($action)) {
    case "show":
        $auto_id = Utility::getPageParam("autoid", null);
        $auto = AutoRepository::GetAuto($auto_id);

        //if auto not exists or is not approved
        if (($auto == null) || (($auto->approved == 0) && (($loggedUser == null) || ($loggedUser->role != UserRoles::Admin)))) {
            Utility::redirectToUrl("/");
        }

        $user = UserRepository::GetUser($auto->user_id);

        //if user is not exists
        if ($user == null) {
            Utility::redirectToUrl("/");
        }

        TemplateManager::Assign("auto", $auto);
        TemplateManager::Assign("user", $user);
        TemplateManager::Assign("showbigphotos", true);

        $titleText = Utility::getlocaltext("sell") . " " . $auto->mark_name . " " . $auto->model_name . " " . $auto->modification . ", " .
                Utility::getlocaltext("photo") . " " . $auto->mark_name . " " . $auto->model_name . " " . $auto->modification . ", " .
                Utility::getlocaltext("year_of_creation") . " " . $auto->year . ", " . Utility::getlocaltext("auto_market2") . " " . $auto->city_name;

        $keyWords = Utility::getlocaltext("auto_market") . " " . $auto->city_name . "," .
                Utility::getlocaltext("sell") . " " . $auto->mark_name . "," .
                Utility::getlocaltext("photo") . " " . $auto->mark_name . " " . $auto->model_name . "," .
                Utility::getlocaltext("photo") . " " . $auto->mark_name . " " . $auto->model_name . " " . $auto->modification . "," .
                Utility::getlocaltext("photo") . " " . $auto->mark_name . " " . Utility::getlocaltext("year_of_creation") . " " . $auto->year;

        TemplateManager::SetPageTitle($titleText);
        TemplateManager::SetPageDescription($titleText);
        TemplateManager::SetPageEntryText($titleText);
        TemplateManager::SetPageKeywords($keyWords);
        break;
}

TemplateManager::DisplayLayout("PhotoGallery");
?>
