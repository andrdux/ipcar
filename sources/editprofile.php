<?php

require_once 'security.php';
require_once 'includes/functions/userrepository.php';

TemplateManager::RegisterHeaderFile("jquery-ui-1.8.8.custom.css");
TemplateManager::RegisterHeaderFile("main.css");
TemplateManager::RegisterHeaderFile("jquery-1.4.4.min.js");
TemplateManager::RegisterHeaderFile("utility.js");

$user = clone UserRepository::GetLoggedUser();

$action = Utility::getPageParam("action", "show");
switch (strtolower($action)) {
    case "show":
        $email = $user->email;
        break;
    case "save":
        $errors = Array();

        $email = strtolower(trim(Utility::getPageParam("email", "")));

        //if email is empty
        if ($email == "") {
            $errors[] = new UIErrorInfo(Utility::getlocaltext("email_not_selected"), "email");
        } else if (!Utility::isCorrectEmail($email)) {//if invalid email
            $errors[] = new UIErrorInfo(Utility::getlocaltext("email_invalid"), "email");
        } else {
            $validateUser = UserRepository::GetUserByEmail($email);

            //if email is already exists in the database
            if (($validateUser != null) && ($validateUser->id != $user->id)) {
                $errors[] = new UIErrorInfo(Utility::getlocaltext("user_email_is_already_registered"), "email");
            } else {
                $user = Utility::getObjectPropertiesFromForm($user);

                $loggedUser = UserRepository::GetLoggedUser();

                $newpassword = Utility::getPageParam("newpassword");
                
                //Change password
                if ($newpassword) {
                    $user->password = $newpassword;
                } else {
                    $user->password = $loggedUser->password;
                }
                $user->active = $loggedUser->active;
                $user->role = $loggedUser->role;
                $user->ip = $_SERVER['REMOTE_ADDR'];
                
                if (UserRepository::IsValid($user, &$errors)) {
                    //Save save user profile and relogin user
                    UserRepository::SaveUser($user, $newpassword);
                    UserRepository::LoginUser($user);
                    TemplateManager::Assign("successmsg", Utility::getlocaltext("profile_successfully_saved"));
                }
            }
        }
        break;
}

TemplateManager::Assign("email", $email);
TemplateManager::Assign("user", $user);
TemplateManager::Assign("errors", $errors);

$qsmarks = AutoRepository::GetAutoMarksWithLogo();
TemplateManager::Assign("qsmarks", $qsmarks);

TemplateManager::DisplayLayout("EditProfile");
?>
