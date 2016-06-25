<?php

require_once 'init.php';
require_once 'includes/functions/autorepository.php';
require_once 'includes/functions/php-captcha.inc.php';
require_once 'includes/functions/mailsender.php';

TemplateManager::RegisterHeaderFile("main.css");
TemplateManager::RegisterHeaderFile("jquery-ui-1.8.8.custom.css");
TemplateManager::RegisterHeaderFile("jquery-1.4.4.min.js");
TemplateManager::RegisterHeaderFile("utility.js");

$action = Utility::getPageParam("action", "show");
switch (strtolower($action)) {
    case "show":
        break;
    case "send":
        $errors = Array();
        
        $fio = trim(Utility::getPageParam("fio", ""));
        $email = strtolower(trim(Utility::getPageParam("email", "")));
        $message = trim(Utility::getPageParam("message", ""));
        $validcode = Utility::getPageParam("validcode", "");

        TemplateManager::Assign("fio", $fio);
        TemplateManager::Assign("email", $email);
        TemplateManager::Assign("message", $message);

        $formIsValid = true;

        if (strlen($fio) <= 0) {
            $errors[] = new UIErrorInfo(Utility::getlocaltext("fullname_not_selected"), "fio");
        } else if (strlen($fio) > 100) {
            $errors[] = new UIErrorInfo(Utility::getlocaltext("fullname") . ": " .
                            str_replace("{0}", "100", Utility::getlocaltext("str_maximum_length"))
                            , "fio");
        }

        if (strlen($email) <= 0) {
            $errors[] = new UIErrorInfo(Utility::getlocaltext("email_not_selected"), "email");
        } else if (!Utility::isCorrectEmail($email)) {
            $errors[] = new UIErrorInfo(Utility::getlocaltext("email_invalid"), "email");
        }


        if (strlen($message) <= 0) {
            $errors[] = new UIErrorInfo(Utility::getlocaltext("message_not_selected"), "message");
        } else if (strlen($message) > 1000) {
            $errors[] = new UIErrorInfo(Utility::getlocaltext("message") . ": " .
                            str_replace("{0}", "1000", Utility::getlocaltext("str_maximum_length"))
                            , "message");
        }

        //if invalid validation code
        if (!PhpCaptcha::Validate($validcode)) {
            $errors[] = new UIErrorInfo(Utility::getlocaltext("validation_code_invalid"), "validcode");
        } 

        if(count($errors) <= 0){
            //Send the message to email
            $mailSender = new MailSender(
                            $configuration->supportEmail,
                            $configuration->contactEmail,
                            Utility::getlocaltext("message"),
                            TemplateManager::Fetch("mail/sendcontactmessage.txt"));
            if ($mailSender->Send()) {
                TemplateManager::Assign("fio", "");
                TemplateManager::Assign("email", "");
                TemplateManager::Assign("message", "");
                TemplateManager::Assign("successmsg", Utility::getlocaltext("message_successfully_sent"));
            } else {
                $errors[] = new UIErrorInfo(Utility::getlocaltext("cant_send_email_due_to_error_on_the_server"), null);
            }
        }

        if(count($errors) > 0)
        {
            TemplateManager::Assign("errors", $errors);
        }
        break;
}

$qsmarks = AutoRepository::GetAutoMarksWithLogo();
TemplateManager::Assign("qsmarks", $qsmarks);

TemplateManager::DisplayLayout("Contacts");
?>
