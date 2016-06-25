<?php

require_once 'init.php';
require_once 'includes/functions/autorepository.php';
require_once 'includes/functions/userrepository.php';
require_once 'includes/functions/locationrepository.php';
require_once 'includes/functions/php-captcha.inc.php';

//Assign variables for control
$auto_id = Utility::getPageParam("autoid", null);
$successurl = $configuration->wwwroot;
$errorurl = $configuration->wwwroot;

$isAdminUser = false;

$loggedUser = UserRepository::GetLoggedUser();
if (($loggedUser != null) && ($loggedUser->role == UserRoles::Admin)) {
    $isAdminUser = true;
}

$action = Utility::getPageParam("action", "show");
switch (strtolower($action)) {
    case "show":
        if ($isAdminUser) {
            $successurl = $configuration->wwwroot . "/management/listofauto.php";
            $errorurl = $configuration->wwwroot . "/management/listofauto.php";
        } else {
            $successurl = "myauto.php";
            $errorurl = $configuration->wwwroot;
        }
        break;
    case "refuse":
        $successurl = "";
        $errorurl = "";
        break;
    case "approve":
        $successurl = "";
        $errorurl = "";
        break;
    case "delete":
        $successurl = "";
        $errorurl = "";
        break;
    case "sold":
        $successurl = "";
        $errorurl = "";
        break;
    case "save":
        if ($isAdminUser) {
            $successurl = $configuration->wwwroot . "/management/listofauto.php";
            $errorurl = $configuration->wwwroot . "/management/listofauto.php";
        } else {
            $successurl = "myauto.php";
            $errorurl = $configuration->wwwroot;
        }
        break;
}

TemplateManager::Assign("formaction", "editauto.php?action=save&autoid=" . $auto_id);

//Validation of page parameters
if ($auto_id == null) {
    Utility::redirectToUrl($errorurl);
}
//Include edit auto control
require_once 'includes/controls/editauto.php';
?>
