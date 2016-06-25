<?php

require_once 'init.php';
require_once 'includes/functions/autorepository.php';
require_once 'includes/functions/userrepository.php';
require_once 'includes/functions/locationrepository.php';


TemplateManager::RegisterHeaderFile("main.css");
TemplateManager::RegisterHeaderFile("jquery-1.4.4.min.js");
TemplateManager::RegisterHeaderFile("utility.js");

$qsmarks = AutoRepository::GetAutoMarksWithLogo();
TemplateManager::Assign("qsmarks", $qsmarks);

TemplateManager::SetPageTitle(Utility::getlocaltext("auto_marks2"));
TemplateManager::SetPageDescription(Utility::getlocaltext("auto_marks2"));
TemplateManager::SetPageKeywords(Utility::getlocaltext("auto_marks").",".Utility::getlocaltext("auto_marks2"));

TemplateManager::DisplayLayout("AutoBrands");
?>
