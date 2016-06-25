<?php

require_once 'includes/functions/coreclasses.php';

//Start the session
session_start();
$Session = new SiteSession;

//Expiration and cache constants
$exp_date = gmdate("r", time() + (30));
$exp_date_already = gmdate("r", time() - (20 * 60));
define("PAGE_EXPIRES_STRING", "Expires: $exp_date");
define("PAGE_CASH_STRING", "Cache-Control: store, cache, must-revalidate");
define("PAGE_ALREADY_EXPIRED_STRING", "Expires: $exp_date_already");
define("PAGE_NO_CASH_STRING", "Cache-Control: no-store, no-cache, must-revalidate");

//Directory calculation
$path_parts = pathinfo(__FILE__);
$dir_name = str_replace("\\", "/", $path_parts["dirname"]);

//Main configuration constants
$configuration->site_dir = $dir_name;
$configuration->cars_dir = $configuration->site_dir . '/pictures/uploaded/cars';
$configuration->upload_temp_dir = $configuration->site_dir . '/pictures/uploaded/temp';
$configuration->standard_photos_dir = $configuration->site_dir . '/pictures/standard';
$configuration->max_size_of_autocomplete = 15;
$configuration->directorypermissions = 00777;
$configuration->theme = "default";
$configuration->lang = "eng";
$configuration->wwwroot = 'http://www.siteroot.com';
$configuration->siteName = 'siteroot.com';
$configuration->supportEmail = 'support@siteroot.com';
$configuration->contactEmail = 'contact@siteroot.com';
$configuration->cssroot = $configuration->wwwroot . '/themes/' . $configuration->theme . '/' . $configuration->lang;
$configuration->bigPhotoSize = new Size(800, 600);
$configuration->mediumPhotoSize = new Size(400, 300);
$configuration->smallPhotoSize = new Size(120, 90);
$configuration->allowedFileExtensions = Array(".jpg", ".jpeg", ".png");
$configuration->maxAllowedNumberOfPhotos = 16;
$configuration->currency = "$";
$configuration->searchPageSize = 10;
$configuration->managementPageSize = 10;
$configuration->numOfPagesToDisplay = 20;
$configuration->smallImagePrefix = "small_";
$configuration->bigImagePrefix = "big_";
$configuration->maxNumOfNotApprovedAuto = 5;
$configuration->autoApproveAuto = true;
$configuration->enableAdvertisement = true;
$configuration->numberOfNewAutoToDisplayOnAutoMarketPage = 5;

//Smarty initialization
require_once 'includes/smarty/Smarty.class.php';

$smarty = new Smarty;
$smarty->compile_check = true;
$smarty->force_compile = true;
$smarty->caching = false;
$smarty->debugging = false;
$smarty->template_dir = $configuration->site_dir . '/themes/' . $configuration->theme . '/' . $configuration->lang . '/templates';
$smarty->compile_dir = $smarty->template_dir . '/compile';
$smarty->config_dir = $smarty->template_dir . '/configs';
$smarty->cache_dir = $smarty->template_dir . '/cache';
$smarty->plugins_dir = $configuration->site_dir . '/includes/smarty/plugins';

//Assign smarty variables
$smarty->assign("configuration", $configuration);

//Load smarty configuration files
$smarty->config_load('common.conf');
$smarty->config_load('labels.conf');
$smarty->config_load('messages.conf');


//Database provider initialization
require_once 'includes/functions/dbi.class.php';
$db = array(
    "type" => 'mysql',
    "host" => 'localhost',
    "port" => "",
    "name" => 'dbname',
    "user" => 'dbuser',
    "pass" => 'dbpassword'
);

$dbi = new DBI;
$dbi->init($db);

//Include other files
require_once 'includes/functions/utility.php';
require_once 'includes/functions/templatemanager.php';

TemplateManager::$MasterPageName = "main.html";
TemplateManager::Assign("LoggedUser", $Session->LoggedUser);
?>
