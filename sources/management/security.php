<?php

require_once '../init.php';
require_once '../includes/functions/userrepository.php';

$loggedUser = UserRepository::GetLoggedUser();

if (($loggedUser == null) || ($loggedUser->role != UserRoles::Admin)) {
    $Session->RedirectFromLoginPage = Utility::getCurrentPageURL();
    Utility::redirectToLocalUrl("login.php");
}
?>



