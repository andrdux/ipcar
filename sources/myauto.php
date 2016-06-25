<?php

require_once 'security.php';
require_once 'includes/functions/autorepository.php';
require_once 'includes/functions/userrepository.php';

TemplateManager::RegisterHeaderFile("main.css");
TemplateManager::RegisterHeaderFile("jquery-ui-1.8.8.custom.css");
TemplateManager::RegisterHeaderFile("jquery-1.4.4.min.js");
TemplateManager::RegisterHeaderFile("jquery-ui-1.8.8.custom.min.js");
TemplateManager::RegisterHeaderFile("jquery.jqupload.min.js");
TemplateManager::RegisterHeaderFile("jquery.blockUI.js");
TemplateManager::RegisterHeaderFile("initblockUI.js");
TemplateManager::RegisterHeaderFile("myautoactions.js");


$user = UserRepository::GetLoggedUser();
$userauto = AutoRepository::GetUserAuto($user->id, true);

TemplateManager::Assign("userauto", $userauto);
TemplateManager::Assign("allowedExtensions", $configuration->allowedFileExtensions);

$qsmarks = AutoRepository::GetAutoMarksWithLogo();
TemplateManager::Assign("qsmarks", $qsmarks);

TemplateManager::DisplayLayout("MyAuto");
?>
