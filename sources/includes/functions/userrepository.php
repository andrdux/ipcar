<?php

require_once 'coreclasses.php';
require_once 'autorepository.php';

/**
 * Repository of methods designed for processing users
 */
class UserRepository {

    /**
     * Get all users
     * @return User[]
     */
    public static function GetUsers() {
        return Utility::getObjectCollectionFromSQL("select * from user;", 'User');
    }

    /**
     * Get all users by criteria
     * @param UserFilter $userFilter
     * @param int $numOfPages
     * @param int $page
     * @return User[]
     */
    public static function GetUsersWithFilter($userFilter, &$numOfPages, &$page = 0) {
        global $configuration;

        $email = isset($userFilter->email) ? " and (email like '" . addslashes($userFilter->email) . "%')" : "";
        $role = isset($userFilter->role) ? " and (role = '" . addslashes($userFilter->role) . "')" : "";

        $criteriaStr = " where (1 = 1) " . $email . $role;

        //Get number of users which are satisfied the search criteria
        $sql = "select count(*) from user " . $criteriaStr;
        $numOfUsers = Utility::getScalarSQL($sql);

        //Calculate and return number of pages
        $numOfPages = ceil($numOfUsers / $configuration->managementPageSize);

        //Fix invalid page number
        if ($page >= $numOfPages) {
            $page = 0;
        }
        //Calculate offset of the auto record for the current page number
        $offset = $page * $configuration->managementPageSize;

        $pageStr = " LIMIT " . $offset . ", " . $configuration->managementPageSize;

        $orderExpression = " ORDER BY updated DESC, email ASC";

        return Utility::getObjectCollectionFromSQL("select * from user " . $criteriaStr . $orderExpression . $pageStr . " ;", 'User');
    }

    /**
     * Get user by id
     * @param int $user_id
     * @return User
     */
    public static function GetUser($user_id) {
        return Utility::getObjectFromSQL("select * from user where id = '" . addslashes($user_id) . "' limit 1;", 'User');
    }

    /**
     * Get active user by email and password (for login)
     * @param string $email
     * @param string $password
     * @return User
     */
    public static function GetActiveUser($email, $password) {
        $email = strtolower(trim($email));
        return Utility::getObjectFromSQL("select * from user where (email = '" . addslashes($email) . "') and (password = '" . addslashes(Utility::getPasswordHash($password)) . "') and (active = 1) limit 1;", 'User');
    }

    /**
     * Get user by email
     * @param string $email
     * @return User 
     */
    public static function GetUserByEmail($email) {
        $email = strtolower(trim($email));
        return Utility::getObjectFromSQL("select * from user where (email = '" . addslashes($email) . "') limit 1;", 'User');
    }

    /**
     * Returns logged user
     * @return User
     */
    public static function GetLoggedUser() {
        global $Session;
        return $Session->LoggedUser;
    }

    /**
     * Do user login
     * @param User $user 
     */
    public static function LoginUser($user) {
        global $Session;
        $Session->LoggedUser = $user;
    }

    /**
     * Do user logout
     * @param User $user
     */
    public static function LogoutUser() {
        global $Session;
        $Session->LoggedUser = null;
    }

    /**
     * Save new or edited user (actual action depends on User.id value)
     * @param User $user
     * @param bool $createHashForPassword
     */
    public static function SaveUser($user, $createHashForPassword = true) {
        global $configuration;
        $user->email = strtolower(trim($user->email));
        $user->password = $createHashForPassword ? Utility::getPasswordHash($user->password) : $user->password;

        if ($user->id < 0) {
            $sql = "insert into user (`id`, `fio`, `email`, `password`, `phone1`, `phone2`, `active`, `role`, `ip`, `updated`) VALUES
                   (NULL, '" . addslashes($user->fio) . "', '" . addslashes($user->email) . "', '" .
                    addslashes($user->password) . "', '" . addslashes($user->phone1) . "', '" .
                    addslashes($user->phone2) . "', '" . addslashes($user->active) . "', '". addslashes($user->role) ."', '" . addslashes($user->ip) . "', now());";
            $insertedUserId = Utility::executeInsertSQL($sql);

            $user->id = $insertedUserId;

            Utility::createDir(UserRepository::GetUserDirectory($user->id));
        } else {
            $sql = "update user SET fio = '" . addslashes($user->fio) . "', email = '" . addslashes($user->email) .
                    "', phone1 = '" . addslashes($user->phone1) . "', phone2 = '" . addslashes($user->phone2) .
                    "', active = '" . addslashes($user->active) . "', password =  '" . addslashes($user->password) .
                    "', ip = '" . addslashes($user->ip) . "', updated = now() where id = '" . addslashes($user->id) . "';";
            Utility::executeSQL($sql);
        }
    }

    /**
     * Determines whether user is valid
     * @param User $user
     * @param UIErrorInfo[] $errors
     */
    public static function IsValid($user, &$errors) {

        $errors = UserRepository::ValidateUserInfo($user);
        return (count($errors) <= 0);
    }

    /**
     * Validate user's fields
     * @param User $user
     * @return UIErrorInfo[]
     */
    private static function ValidateUserInfo($user) {
        $errors = Array();

        if (!is_numeric($user->id)) {
            $errors[] = new UIErrorInfo(Utility::getlocaltext("user_not_selected"), "id");
        }

        $user->fio = trim($user->fio);
        if (strlen($user->fio) <= 0) {
            $errors[] = new UIErrorInfo(Utility::getlocaltext("fullname_not_selected"), "fio");
        } else if (strlen($user->fio) > 100) {
            $errors[] = new UIErrorInfo(Utility::getlocaltext("fullname") . ": " .
                            str_replace("{0}", "100", Utility::getlocaltext("str_maximum_length"))
                            , "fio");
        }


        $user->email = strtolower(trim($user->email));
        if (strlen($user->email) <= 0) {
            $errors[] = new UIErrorInfo(Utility::getlocaltext("email_not_selected"), "email");
        } else if (!Utility::isCorrectEmail($user->email)) {
            $errors[] = new UIErrorInfo(Utility::getlocaltext("email_invalid"), "email");
        }


        if (strlen($user->password) <= 0) {
            $errors[] = new UIErrorInfo(Utility::getlocaltext("password_not_selected"), "password");
        } else if (strlen($user->password) > 55) {
            $errors[] = new UIErrorInfo(Utility::getlocaltext("password") . ": " .
                            str_replace("{0}", "55", Utility::getlocaltext("str_maximum_length"))
                            , "password");
        }

        if (strlen($user->phone1) <= 0) {
            $errors[] = new UIErrorInfo(Utility::getlocaltext("phone1_not_selected"), "phone1");
        } else if (strlen($user->phone1) > 55) {
            $errors[] = new UIErrorInfo(Utility::getlocaltext("phone") . " 1: " .
                            str_replace("{0}", "55", Utility::getlocaltext("str_maximum_length"))
                            , "phone1");
        }

        if ($user->phone2) {
            if (strlen($user->phone2) > 55) {
                $errors[] = new UIErrorInfo(Utility::getlocaltext("phone") . " 2: " .
                                str_replace("{0}", "55", Utility::getlocaltext("str_maximum_length"))
                                , "phone2");
            }
        }

        if ($user->active == null) {
            $user->active = 0;
        }

        if (($user->active != 0) && ($user->active != 1)) {
            $errors[] = new UIErrorInfo(Utility::getlocaltext("active_status_invalid"), "active");
        }

        if ($user->role == null) {
            $user->role = 0;
        }

        if (($user->role != 0) && ($user->role != 1)) {
            $errors[] = new UIErrorInfo(Utility::getlocaltext("role_invalid"), "role");
        }

        if (strlen($user->ip) > 50) {
            $user->ip = "";
        }

        return $errors;
    }

    /**
     * Determines whether format of user's credentials is valid
     * @param string $email
     * @param string $password
     * @param UIErrorInfo[] $errors
     */
    public static function IsValidCredentialsFormat($email, $password, &$errors) {
        $errors = UserRepository::ValidateUserCredentialsFormat($email, $password);
        return (count($errors) <= 0);
    }

    /**
     * Validate format of user's credentials
     * @param string $email
     * @param string $password
     * @return UIErrorInfo
     */
    private static function ValidateUserCredentialsFormat($email, $password) {
        $errors = Array();

        $email = strtolower(trim($email));
        if (strlen($email) <= 0) {
            $errors[] = new UIErrorInfo(Utility::getlocaltext("email_not_selected"), "email");
        } else if (!Utility::isCorrectEmail($email)) {
            $errors[] = new UIErrorInfo(Utility::getlocaltext("email_invalid"), "email");
        }


        if (strlen($password) <= 0) {
            $errors[] = new UIErrorInfo(Utility::getlocaltext("password_not_selected"), "password");
        } else if (strlen($password) > 55) {
            $errors[] = new UIErrorInfo(Utility::getlocaltext("password") . ": " .
                            str_replace("{0}", "55", Utility::getlocaltext("str_maximum_length"))
                            , "password");
        }

        return $errors;
    }

    /**
     * Returns path to user's directory
     * @param int $user_id
     * @param bool $getforwww
     * @return string 
     */
    public static function GetUserDirectory($user_id, $getforwww = false) {
        global $configuration;
        if ($getforwww) {
            $dir = "/pictures/uploaded/cars/user" . $user_id;
        } else {
            $dir = $configuration->cars_dir . "/user" . $user_id;
        }
        return $dir;
    }

    /**
     * Set user's "active" status
     * @param int $user_id
     * @param 0 or 1 $active
     */
    public static function SetUserActive($user_id, $active) {
        $sql = "update user set active = '" . addslashes($active) . "' where id = '" . addslashes($user_id) . "';";
        Utility::executeSQL($sql);
    }

    /**
     * Remove user
     * @param int $user_id
     */
    public static function RemoveUser($user_id) {
        global $configuration;
        $userauto = AutoRepository::GetUserAuto($user_id);
        if ($userauto) {
            foreach ($userauto as $key => $auto) {
                AutoRepository::RemoveAuto($auto);
            }
        }
        Utility::executeSQL("delete from user where id = '" . addslashes($user_id) . "' limit 1;");
        Utility::deleteDir(UserRepository::GetUserDirectory($user_id));
    }

}

?>
