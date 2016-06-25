<?php

require_once 'init.php';
require_once 'includes/functions/autorepository.php';
require_once 'includes/functions/userrepository.php';
require_once 'includes/functions/locationrepository.php';
require_once 'includes/functions/php-captcha.inc.php';

//Assign variables for control
$auto_id = null;
$successurl = "myauto.php";
$errorurl = $configuration->wwwroot;

$loggedUser = UserRepository::GetLoggedUser();
if ($loggedUser != null) {
    $numOfNotApprovedAuto = AutoRepository::GetNumOfNotApprovedUserAuto($loggedUser->id);
    if ($numOfNotApprovedAuto >= $configuration->maxNumOfNotApprovedAuto) {
        TemplateManager::Assign("maxNumOfNotApprovedAutoReached", true);
        $errors = Array();
        $errors[] = new UIErrorInfo(str_replace("{0}", $configuration->maxNumOfNotApprovedAuto, Utility::getlocaltext("maxNumOfNotApprovedAutoReached")), "");
        TemplateManager::Assign("errors", $errors);
    }
}

TemplateManager::Assign("formaction", "addauto.php?action=save");

//Include edit auto control
require_once 'includes/controls/editauto.php';
?>
