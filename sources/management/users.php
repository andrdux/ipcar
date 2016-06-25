<?php

require_once 'init.php';
require_once '../includes/functions/userrepository.php';

TemplateManager::RegisterHeaderFile("usersactions.js");

$page = Utility::getPageParam("page", 0);
$numOfPages = 0;

$action = Utility::getPageParam("action", "show");
switch (strtolower($action)) {
    case "remove":
        $userid = Utility::getPageParam("userid", -1);
        UserRepository::RemoveUser($userid);
        break;
    case "show":
        $email = Utility::getPageParam("email", "");

        $userFilter = new UserFilter();
        if($email != ""){
            $userFilter->email = $email;
        }
        $userFilter->role = UserRoles::User;

        $listOfUsers = UserRepository::GetUsersWithFilter($userFilter, &$numOfPages, $page);

        $pagesFrom = (floor($page / $configuration->numOfPagesToDisplay) * $configuration->numOfPagesToDisplay) + 1;
        $pagesTo = (($pagesFrom + $configuration->numOfPagesToDisplay) > ($numOfPages + 1)) ? $numOfPages + 1 : $pagesFrom + $configuration->numOfPagesToDisplay;

        TemplateManager::Assign("scriptName", "users.php");
        TemplateManager::Assign("numOfPages", $numOfPages);
        TemplateManager::Assign("page", $page);
        TemplateManager::Assign("pagesFrom", $pagesFrom);
        TemplateManager::Assign("pagesTo", $pagesTo);

        TemplateManager::Assign("listOfUsers", $listOfUsers);
        TemplateManager::Assign("searchparams", "email=".$email);
        TemplateManager::Assign("email", $email);

        TemplateManager::DisplayLayout("Users");
        break;
}

?>



