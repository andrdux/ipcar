<?php

TemplateManager::RegisterHeaderFile("main.css");
TemplateManager::RegisterHeaderFile("jquery-ui-1.8.8.custom.css");
TemplateManager::RegisterHeaderFile("jquery-1.4.4.min.js");
TemplateManager::RegisterHeaderFile("jquery-ui-1.8.8.custom.min.js");
TemplateManager::RegisterHeaderFile("autoNumeric-1.5.1.js");
TemplateManager::RegisterHeaderFile("utility.js");
TemplateManager::RegisterHeaderFile("jquery.blockUI.js");
TemplateManager::RegisterHeaderFile("initblockUI.js");
TemplateManager::RegisterHeaderFile("jquery.watermark.min.js");
TemplateManager::RegisterHeaderFile("autoformactions.js");

$auto = new Auto();
$user = UserRepository::GetLoggedUser();
$useralreadyregistered = Utility::getPageParam("useralreadyregistered");


$action = Utility::getPageParam("action", "show");
switch (strtolower($action)) {
    case "show":
        //If adding of new auto
        if ($auto_id == null) {
            $auto = new Auto();
            $auto->id = -1;
        }//if editing existing auto
        else {
            $auto = AutoRepository::GetAuto($auto_id);
            //if auto not exists or auto doesn't belongs to the logged user or auto already sold
            if (($auto == null) || !AutoRepository::IsAutoBelongsToUser3($auto, UserRepository::GetLoggedUser()) || ($auto->sold == 1)) {
                Utility::redirectToUrl($errorurl);
            }
        }

        break;
    case "refuse":
        $loggedUser = UserRepository::GetLoggedUser();
        //if user is Admin
        if (($loggedUser != null) && ($loggedUser->role == UserRoles::Admin)) {
            $auto = AutoRepository::GetAuto($auto_id);
            //if auto not exists or auto doesn't belongs to the logged user
            if (($auto == null) || !AutoRepository::IsAutoBelongsToUser3($auto, $loggedUser)) {
                Utility::redirectToUrl($errorurl);
            } else {
                $auto->approved = ApprovedStatus::Refused;
                AutoRepository::SaveAuto($auto);
                Utility::redirectToUrl($successurl);
            }
        } else {
            Utility::redirectToUrl($errorurl);
        }
        break;
    case "approve":
        $loggedUser = UserRepository::GetLoggedUser();
        //if user is Admin
        if (($loggedUser != null) && ($loggedUser->role == UserRoles::Admin)) {
            $auto = AutoRepository::GetAuto($auto_id);
            //if auto not exists or auto doesn't belongs to the logged user
            if (($auto == null) || !AutoRepository::IsAutoBelongsToUser3($auto, $loggedUser)) {
                Utility::redirectToUrl($errorurl);
            } else {
                $auto->approved = ApprovedStatus::Approved;
                AutoRepository::SaveAuto($auto);
                Utility::redirectToUrl($successurl);
            }
        } else {
            Utility::redirectToUrl($errorurl);
        }
        break;
    case "delete":
        $loggedUser = UserRepository::GetLoggedUser();
        //if user is Admin
        if (($loggedUser != null) && ($loggedUser->role == UserRoles::Admin)) {
            $auto = AutoRepository::GetAuto($auto_id);
            //if auto not exists or auto doesn't belongs to the logged user
            if (($auto == null) || !AutoRepository::IsAutoBelongsToUser3($auto, $loggedUser)) {
                Utility::redirectToUrl($errorurl);
            } else {
                AutoRepository::RemoveAuto($auto);
                Utility::redirectToUrl($successurl);
            }
        } else {
            Utility::redirectToUrl($errorurl);
        }
        break;
    case "sold":
        $auto = AutoRepository::GetAuto($auto_id);
        //if auto not exists or auto doesn't belongs to the logged user
        if (($auto == null) || !AutoRepository::IsAutoBelongsToUser3($auto, UserRepository::GetLoggedUser())) {
            Utility::redirectToUrl($errorurl);
        } else {
            $auto->sold = 1;
            AutoRepository::SaveAuto($auto);
            Utility::redirectToUrl($successurl);
        }
        break;
    case "save":
        //If user is not logged in
        if ($user == null) {
            $user = new User();
            $user->id = -1;
            $user = Utility::getObjectPropertiesFromForm($user);
        }

        //Get auto info from database before save
        $auto->id = Utility::getPageParam("auto_id", -1);
        if ($auto->id > 0) {
            $auto = AutoRepository::GetAuto($auto->id);
            if ($auto == null) {
                Utility::redirectToUrl($errorurl);
            }
            $previousUserId = $auto->user_id;
        }

        $auto = Utility::getObjectPropertiesFromForm($auto, true);


        //Get equipment info from form
        $auto->equipment = Array();
        $equipment = Utility::getPageParam("equipment");
        if ($equipment) {
            foreach ($equipment as $key => $value) {
                $eq = AutoRepository::GetEquipmentById($value);
                if ($eq) {
                    $auto->equipment[] = $eq;
                }
            }
        }

        $formIsValid = true;

        $errors = Array();

        //If user is not logged in
        if ($user->id < 0) {


            //User is trying to login
            if ($useralreadyregistered) {
                $loginUser = UserRepository::GetActiveUser($user->email, $user->password);
                if ($loginUser != null) {
                    UserRepository::LoginUser($loginUser);
                    $user = UserRepository::GetLoggedUser();
                } else {
                    $errors[] = new UIErrorInfo(Utility::getlocaltext("cant_login_email_or_password_invalid"), "email");
                    $formIsValid = false;
                }
            } //User is trying to create new account
            else {
                $loginUser = UserRepository::GetUserByEmail($user->email);
                if ($loginUser != null) {
                    $errors[] = new UIErrorInfo(Utility::getlocaltext("user_email_is_already_exists"), "email");
                    $formIsValid = false;
                } else {
                    $validcode = Utility::getPageParam("validcode", "");

                    //if invalid validation code
                    if (!PhpCaptcha::Validate($validcode)) {
                        $errors[] = new UIErrorInfo(Utility::getlocaltext("validation_code_invalid"), "validcode");
                        $formIsValid = false;
                    } else {
                        $user->active = 1;
                        $user->role = UserRoles::User;
                        $user->ip = $_SERVER['REMOTE_ADDR'];
                        if (UserRepository::IsValid($user, &$errors)) {
                            //Save and login user
                            UserRepository::SaveUser($user);
                            UserRepository::LoginUser($user);
                        } else {
                            $formIsValid = false;
                        }
                    }
                }
            }
        }

        if ($formIsValid) {
            if ($auto->id > 0) {
                //If edit auto
                $auto->user_id = $previousUserId;
            } else {
                //If add new auto
                $auto->user_id = $user->id;
            }

            //if approve auto automatically
            if ($configuration->autoApproveAuto) {
                $auto->approved = 1;
            } else {
                $auto->approved = 0;
            }

            $auto->sold = 0;

            //Save user's auto
            if (AutoRepository::IsValid($auto, &$errors)) {

                //if auto not exists or auto doesn't belongs to the logged user or auto already sold
                if (($auto->id > 0) && !AutoRepository::IsAutoBelongsToUser3($auto, UserRepository::GetLoggedUser()) || ($auto->sold == 1)) {
                    Utility::redirectToUrl($errorurl);
                }

                AutoRepository::SaveAuto($auto);
                Utility::redirectToUrl($successurl);
            } else {
                $formIsValid = false;
            }
        }

        if (!$formIsValid) {
            TemplateManager::Assign("errors", $errors);
        }
        break;
    default:
        Utility::redirectToUrl($errorurl);
        break;
}

TemplateManager::Assign("useralreadyregistered", $useralreadyregistered);

TemplateManager::Assign("user", $user);
TemplateManager::Assign("auto", $auto);

$marks = AutoRepository::GetAutoMarks();
TemplateManager::Assign("marks", $marks);

$qsmarks = AutoRepository::GetAutoMarksWithLogo();
TemplateManager::Assign("qsmarks", $qsmarks);

$models = AutoRepository::GetAutoModels($auto->id_mark);
TemplateManager::Assign("models", $models);

$transmissions = AutoRepository::GetTransmission();
TemplateManager::Assign("transmissions", $transmissions);

$carcases = AutoRepository::GetCarCases();
TemplateManager::Assign("carcases", $carcases);

$fuel = AutoRepository::GetFuel();
TemplateManager::Assign("fuel", $fuel);

$fuelsupply = AutoRepository::GetFuelSupply();
TemplateManager::Assign("fuelsupply", $fuelsupply);

$kpp = AutoRepository::GetKpp();
TemplateManager::Assign("kpp", $kpp);

$color = AutoRepository::GetColors();
TemplateManager::Assign("color", $color);

$countries = LocationRepository::GetCountries();
TemplateManager::Assign("countries", $countries);

$regions = LocationRepository::GetRegions($auto->country_id);
TemplateManager::Assign("regions", $regions);

$cities = LocationRepository::GetCities($auto->region_id);
TemplateManager::Assign("cities", $cities);

$autostates = AutoRepository::GetAutoStates();
TemplateManager::Assign("autostates", $autostates);

$allequipment = AutoRepository::GetEquipment();
TemplateManager::Assign("allequipment", $allequipment);

TemplateManager::SetPageTitle(Utility::getlocaltext("sell_auto"));
TemplateManager::SetPageDescription(Utility::getlocaltext("sell_auto"));
TemplateManager::SetPageKeywords(Utility::getlocaltext("sell_auto"));

TemplateManager::DisplayLayout("EditAuto");
?>
