<?php

require_once 'init.php';
require_once 'includes/functions/userrepository.php';
require_once 'includes/functions/mailsender.php';

TemplateManager::RegisterHeaderFile("jquery-ui-1.8.8.custom.css");
TemplateManager::RegisterHeaderFile("main.css");
TemplateManager::RegisterHeaderFile("jquery-1.4.4.min.js");
TemplateManager::RegisterHeaderFile("utility.js");

$action = Utility::getPageParam("action", "show");
switch (strtolower($action)) {
    case "show":
        break;
    case "get":
        $errors = Array();
        $email = strtolower(trim(Utility::getPageParam("email", "")));

        if ($email == "") {
            $errors[] = new UIErrorInfo(Utility::getlocaltext("email_not_selected"), "email");
        } else if (!Utility::isCorrectEmail($email)) {
            $errors[] = new UIErrorInfo(Utility::getlocaltext("email_invalid"), "email");
        } else {
            $user = UserRepository::GetUserByEmail($email);
            if ($user != null) {

                //Generate and save new password
                $user->password = Utility::getUniqueId(8);
                TemplateManager::Assign("newpassword", $user->password);

                UserRepository::SaveUser($user);

                //Send new password to email
                $mailSender = new MailSender(
                                $configuration->supportEmail,
                                $email,
                                Utility::getlocaltext("new_password"),
                                TemplateManager::Fetch("mail/sendnewpassword.txt"));
                if ($mailSender->Send()) {
                    $email = "";
                    TemplateManager::Assign("successmsg", Utility::getlocaltext("new_password_successfully_sent"));
                } else {
                    $errors[] = new UIErrorInfo(Utility::getlocaltext("cant_send_email_due_to_error_on_the_server"), null);
                }
            } else {
                $errors[] = new UIErrorInfo(Utility::getlocaltext("no_user_with_such_email_address"), "email");
            }
        }
        TemplateManager::Assign("email", $email);
        TemplateManager::Assign("errors", $errors);
        break;
}

$qsmarks = AutoRepository::GetAutoMarksWithLogo();
TemplateManager::Assign("qsmarks", $qsmarks);

TemplateManager::DisplayLayout("RestorePassword");
?>
