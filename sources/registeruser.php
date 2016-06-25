<?php

require_once 'init.php';
require_once 'includes/functions/userrepository.php';
require_once 'includes/functions/php-captcha.inc.php';

TemplateManager::RegisterHeaderFile("jquery-ui-1.8.8.custom.css");
TemplateManager::RegisterHeaderFile("main.css");
TemplateManager::RegisterHeaderFile("jquery-1.4.4.min.js");
TemplateManager::RegisterHeaderFile("utility.js");

$action = Utility::getPageParam("action", "show");
switch (strtolower($action)) {
    case "show":
        break;
    case "save":
        $errors = Array();

        $user = new User();
        $user->id = -1;
        $user = Utility::getObjectPropertiesFromForm($user);

        $loginUser = UserRepository::GetUserByEmail($user->email);
        if ($loginUser != null) {
            $errors[] = new UIErrorInfo(Utility::getlocaltext("user_email_is_already_exists"), "email");
        } else {
            $validcode = Utility::getPageParam("validcode", "");

            //if invalid validation code
            if (!PhpCaptcha::Validate($validcode)) {
                $errors[] = new UIErrorInfo(Utility::getlocaltext("validation_code_invalid"), "validcode");
            } else {
                $user->active = 1;
                $user->role = UserRoles::User;
                $user->ip = $_SERVER['REMOTE_ADDR'];
                if (UserRepository::IsValid($user, &$errors)) {
                    //Save and login user
                    UserRepository::SaveUser($user);
                    UserRepository::LoginUser($user);
                    Utility::redirectToUrl("usermenu.php");
                }
            }
        }

        TemplateManager::Assign("user", $user);
        TemplateManager::Assign("errors", $errors);
        break;
}

$qsmarks = AutoRepository::GetAutoMarksWithLogo();
TemplateManager::Assign("qsmarks", $qsmarks);

TemplateManager::DisplayLayout("RegisterUser");
?>
