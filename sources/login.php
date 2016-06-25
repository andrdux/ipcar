<?php

require_once 'init.php';
require_once 'includes/functions/userrepository.php';

TemplateManager::RegisterHeaderFile("jquery-ui-1.8.8.custom.css");
TemplateManager::RegisterHeaderFile("main.css");
TemplateManager::RegisterHeaderFile("jquery-1.4.4.min.js");
TemplateManager::RegisterHeaderFile("utility.js");

$email = Utility::getPageParam("email", "");
$password = Utility::getPageParam("password", "");

$action = Utility::getPageParam("action", "show");
switch (strtolower($action)) {
    case "show":
        if (UserRepository::GetLoggedUser() != null) {
            Utility::redirectToLocalUrl("usermenu.php");
        }
        break;
    case "login":
        if (UserRepository::IsValidCredentialsFormat($email, $password, &$errors)) {
            $user = UserRepository::GetActiveUser($email, $password);
            if ($user != null) {
                UserRepository::LoginUser($user);
                if ($Session->RedirectFromLoginPage) {
                    Utility::redirectToUrl($Session->RedirectFromLoginPage);
                } else {
                    Utility::redirectToLocalUrl("usermenu.php");
                }
            } else {
                TemplateManager::Assign("errors", Array(new UIErrorInfo(Utility::getlocaltext("cant_login_email_or_password_invalid"), "email")));
            }
        } else {
            TemplateManager::Assign("errors", $errors);
        }
        break;
    case "logout":
        UserRepository::LogoutUser();
        Utility::redirectToLocalUrl("index.php");
        break;
}

TemplateManager::Assign("email", $email);
TemplateManager::Assign("password", $password);

$qsmarks = AutoRepository::GetAutoMarksWithLogo();
TemplateManager::Assign("qsmarks", $qsmarks);

TemplateManager::DisplayLayout("Login");
?>



