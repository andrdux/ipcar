<?php

require_once 'init.php';
require_once 'includes/functions/userrepository.php';

if (UserRepository::GetLoggedUser() == null) {
    $Session->RedirectFromLoginPage = Utility::getCurrentPageURL();
    Utility::redirectToLocalUrl("login.php");
}
?>



