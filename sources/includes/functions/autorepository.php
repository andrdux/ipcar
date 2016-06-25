<?php

require_once 'coreclasses.php';
require_once 'userrepository.php';

/**
 * Repository of methods designed for processing auto
 */
class AutoRepository {
    const SQL_GET_AUTO_FIELDS = "select distinct a.*, amk.mark_name, amd.model_name, u.fio as user_fio, 
                                        u.email as user_email, u.phone1 as user_phone1, u.phone2 as user_phone2,
                                        ast.name as autostate_name, cc.name as carcase_name, c.name as color_name,
                                        f.name as fuel_name, fs.name as fuelsupply_name, k.name as kpp_name, t.name as transmission_name,
                                        cy.name as city_name, ph.name as photoname, ph.pathtodir as photodir
                                        from auto a
                                        inner join automark as amk on a.id_mark = amk.id_mark
                                        inner join automodel as amd on a.id_model = amd.id_model
                                        inner join user as u on a.user_id = u.id
                                        inner join autostate as ast on a.autostate_id = ast.id
                                        inner join carcase as cc on a.carcase_id = cc.id
                                        inner join color as c on a.color_id = c.id
                                        inner join fuel as f on a.fuel_id = f.id
                                        inner join fuelsupply as fs on a.fuelsupply_id = fs.id
                                        inner join kpp as k on a.kpp_id = k.id
                                        inner join transmission as t on a.transmission_id = t.id
                                        inner join city as cy on a.city_id = cy.id
                                        left outer join photo as ph on (ph.auto_id = a.id) and (ph.default = 1) ";

    const SQL_GET_NUM_OF_AUTO_FIELDS = "select count(*)
                                        from auto a
                                        inner join automark as amk on a.id_mark = amk.id_mark
                                        inner join automodel as amd on a.id_model = amd.id_model
                                        inner join user as u on a.user_id = u.id
                                        inner join autostate as ast on a.autostate_id = ast.id
                                        inner join carcase as cc on a.carcase_id = cc.id
                                        inner join color as c on a.color_id = c.id
                                        inner join fuel as f on a.fuel_id = f.id
                                        inner join fuelsupply as fs on a.fuelsupply_id = fs.id
                                        inner join kpp as k on a.kpp_id = k.id
                                        inner join transmission as t on a.transmission_id = t.id
                                        inner join city as cy on a.city_id = cy.id
                                        left outer join photo as ph on (ph.auto_id = a.id) and (ph.default = 1) ";

    /**
     * Get auto by id
     * @param int $auto_id
     * @return Auto
     */
    public static function GetAuto($auto_id) {
        global $configuration;

        $sql = AutoRepository::SQL_GET_AUTO_FIELDS . " where a.id = '" . addslashes($auto_id) . "'";
        $auto = Utility::getObjectFromSQL($sql, 'Auto');

        if ($auto) {
            $auto->photos = AutoRepository::GetPhoto($auto->id);
            $auto->equipment = AutoRepository::GetAutoEquipment($auto->id);
            if ($auto->photoname != null) {
                $auto->smallphotoname = $configuration->smallImagePrefix . $auto->photoname;
                $auto->bigphotoname = $configuration->bigImagePrefix . $auto->photoname;
            }
        }
        return $auto;
    }

    /**
     * Save new or edited auto (actual action depends on Auto.id value)
     * @param Auto $auto
     * @return id of saved auto
     */
    public static function SaveAuto($auto) {
        if ($auto->id < 0) {
            AutoRepository::AddNewAuto($auto);
        } else {
            AutoRepository::UpdateAuto($auto);
        }
    }

    /**
     * Add new auto
     * @param Auto $auto 
     */
    private static function AddNewAuto($auto) {
        global $configuration;
        $sql = "insert into auto (`id`, `id_mark`, `id_model`, `modification`, `user_id`,
                                `autostate_id`, `carcase_id`, `color_id`, `fuel_id`,
                                `fuelsupply_id`, `kpp_id`, `transmission_id`, `country_id`, `region_id`, `city_id`,
                                `price`, `year`, `mileage`, `volume`, `power`, `consumption`,
                                `acceleration`, `cylinders`, `description`, `exchange`,
                                `tuning`, `tuningdesc`, `notcustoms`, `urgent`, `sold`, `updated`, `approved`, `premiumstatus`) values
                                (NULL, '" . addslashes($auto->id_mark) . "', '" . addslashes($auto->id_model) . "', '" . addslashes($auto->modification) . "', '" . addslashes($auto->user_id) . "',
                                '" . addslashes($auto->autostate_id) . "', '" . addslashes($auto->carcase_id) . "', '" . addslashes($auto->color_id) . "', '" . addslashes($auto->fuel_id) . "',
                                '" . addslashes($auto->fuelsupply_id) . "', '" . addslashes($auto->kpp_id) . "', '" . addslashes($auto->transmission_id) . "', '" .
                addslashes($auto->country_id) . "', '" . addslashes($auto->region_id) . "', '" . addslashes($auto->city_id) . "', '" . addslashes($auto->price) . "', '" . addslashes($auto->year) . "', '" . addslashes($auto->mileage) .
                "', '" . addslashes($auto->volume) . "', '" . addslashes($auto->power) . "', '" . addslashes($auto->consumption) . "', '" . addslashes($auto->acceleration) .
                "', '" . addslashes($auto->cylinders) . "', '" . addslashes($auto->description) . "', '" . addslashes($auto->exchange) . "',
                                '" . addslashes($auto->tuning) . "', '" . addslashes($auto->tuningdesc) . "', '" . addslashes($auto->notcustoms) . "', '" .
                addslashes($auto->urgent) . "', '" . addslashes($auto->sold) .
                "', date(now()), '" . addslashes($auto->approved) . "', '" . addslashes($auto->premiumstatus) . "');";

        $insertedId = Utility::executeInsertSQL($sql);
        $auto->id = $insertedId;

        if ($auto->equipment) {
            foreach ($auto->equipment as $equipment) {
                AutoRepository::AddAutoEquipment($auto, $equipment);
            }
        }

        Utility::createDir(AutoRepository::GetAutoDirectory($auto));
    }

    /**
     * Update existing auto
     * @param Auto $auto 
     */
    private static function UpdateAuto($auto) {

        $sql = "update auto set id_mark = '" . addslashes($auto->id_mark) . "', id_model = '" . addslashes($auto->id_model) .
                "', user_id = '" . addslashes($auto->user_id) . "', autostate_id = '" . addslashes($auto->autostate_id) . "',
                carcase_id = '" . addslashes($auto->carcase_id) . "', color_id = '" . addslashes($auto->color_id) .
                "', fuel_id = '" . addslashes($auto->fuel_id) . "', fuelsupply_id = '" . addslashes($auto->fuelsupply_id) . "',
                kpp_id = '" . addslashes($auto->kpp_id) . "', transmission_id = '" . addslashes($auto->transmission_id) .
                "', city_id = '" . addslashes($auto->city_id) . "', price = '" . addslashes($auto->price) . "',
                year = '" . addslashes($auto->year) . "', mileage = '" . addslashes($auto->mileage) . "', volume = '" .
                addslashes($auto->volume) . "', power = '" . addslashes($auto->power) . "', consumption = '" .
                addslashes($auto->consumption) . "', acceleration = '" . addslashes($auto->acceleration) .
                "', cylinders = '" . addslashes($auto->cylinders) . "', description = '" . addslashes($auto->description) .
                "', exchange = '" . addslashes($auto->exchange) . "', tuning = '" . addslashes($auto->tuning) .
                "', tuningdesc = '" . addslashes($auto->tuningdesc) . "', notcustoms = '" . addslashes($auto->notcustoms) .
                "', urgent = '" . addslashes($auto->urgent) . "', sold = '" . addslashes($auto->sold) . "',
                modification = '" . addslashes($auto->modification) . "', country_id = '" . addslashes($auto->country_id) . "',
                region_id = '" . addslashes($auto->region_id) . "', updated = date(now()), approved = '" . addslashes($auto->approved) . "', premiumstatus = '" . addslashes($auto->premiumstatus) . "' where id = '" . addslashes($auto->id) . "';";
        Utility::executeSQL($sql);

        AutoRepository::RemoveAutoEquipment($auto);
        if ($auto->equipment) {
            foreach ($auto->equipment as $equipment) {
                AutoRepository::AddAutoEquipment($auto, $equipment);
            }
        }
    }

    /**
     * Returns path to auto's directory
     * @param Auto auto
     * @param bool $getforwww
     * @return string
     */
    public static function GetAutoDirectory($auto, $getforwww = false) {
        global $configuration;
        $dir = UserRepository::GetUserDirectory($auto->user_id, $getforwww) . "/auto" . $auto->id;
        return $dir;
    }

    /**
     * Search auto
     * @param SearchAutoCriteria $criteria
     * @param int $numOfPages
     * @param int $page
     * @return Auto[]
     */
    public static function SearchAuto($criteria, &$numOfPages, &$page = 0) {
        global $configuration;

        $criteriaList = Array();
        $criteriaStr = "";

        if ($criteria->id_mark > 0) {
            $criteriaList[] = "(a.id_mark = '" . addslashes($criteria->id_mark) . "')";
        }

        if ($criteria->id_model > 0) {
            $criteriaList[] = "(a.id_model = '" . addslashes($criteria->id_model) . "')";
        }

        if ($criteria->country_id > 0) {
            $criteriaList[] = "(a.country_id = '" . addslashes($criteria->country_id) . "')";
        }

        if ($criteria->region_id > 0) {
            $criteriaList[] = "(a.region_id = '" . addslashes($criteria->region_id) . "')";
        }

        if ($criteria->city_id > 0) {
            $criteriaList[] = "(a.city_id = '" . addslashes($criteria->city_id) . "')";
        }

        if ($criteria->priceFrom != "") {
            $criteriaList[] = "(a.price >= " . addslashes($criteria->priceFrom) . ")";
        }

        if ($criteria->priceTo != "") {
            $criteriaList[] = "(a.price <= " . addslashes($criteria->priceTo) . ")";
        }

        if ($criteria->yearFrom != "") {
            $criteriaList[] = "(a.year >= " . addslashes($criteria->yearFrom) . ")";
        }

        if ($criteria->yearTo != "") {
            $criteriaList[] = "(a.year <= " . addslashes($criteria->yearTo) . ")";
        }

        if ($criteria->volumeFrom != "") {
            $criteriaList[] = "(a.volume >= " . addslashes($criteria->volumeFrom) . ")";
        }

        if ($criteria->volumeTo != "") {
            $criteriaList[] = "(a.volume <= " . addslashes($criteria->volumeTo) . ")";
        }

        if ($criteria->transmission_id > 0) {
            $criteriaList[] = "(a.transmission_id = '" . addslashes($criteria->transmission_id) . "')";
        }

        if ($criteria->fuel_id > 0) {
            $criteriaList[] = "((f.parent_id2 = '" . addslashes($criteria->fuel_id) . "') OR (f.parent_id1 = '" . addslashes($criteria->fuel_id) . "') OR (a.fuel_id = '" . addslashes($criteria->fuel_id) . "'))";
        }

        if ($criteria->carcase_id > 0) {
            $criteriaList[] = "((cc.parent_id = '" . addslashes($criteria->carcase_id) . "') OR (a.carcase_id = '" . addslashes($criteria->carcase_id) . "'))";
        }

        if ($criteria->color_id > 0) {
            $criteriaList[] = "(a.color_id = '" . addslashes($criteria->color_id) . "')";
        }

        if ($criteria->kpp_id > 0) {
            $criteriaList[] = "((k.parent_id = '" . addslashes($criteria->kpp_id) . "') OR (a.kpp_id = '" . addslashes($criteria->kpp_id) . "'))";
        }

        if ($criteria->autostate_id > 0) {
            $criteriaList[] = "((ast.parent_id = '" . addslashes($criteria->autostate_id) . "') OR (a.autostate_id = '" . addslashes($criteria->autostate_id) . "'))";
        }

        if ($criteria->exchangerequired == 1) {
            $criteriaList[] = "(a.exchange = 1)";
        }

        if ($criteria->tuningrequired == 1) {
            $criteriaList[] = "(a.tuning = 1)";
        }

        if ($criteria->customsrequired == 1) {
            $criteriaList[] = "(a.notcustoms = 0)";
        }

        if ($criteria->urgentrequired == 1) {
            $criteriaList[] = "(a.urgent = 1)";
        }

        if ($criteria->showsold == 0) {
            $criteriaList[] = "(a.sold = 0)";
        }

        if ($criteria->photorequired == 1) {
            $criteriaList[] = "(NOT(ph.namesmall IS null))";
        }

        $criteriaList[] = "(a.approved = 1)";

        if (count($criteriaList) > 0) {
            $criteriaStr = " where " . join(" AND ", $criteriaList);
        }

        if (!is_numeric($page)) {
            $page = 0;
        }

        //Get number of auto which are satisfied the search criteria
        $sql = AutoRepository::SQL_GET_NUM_OF_AUTO_FIELDS . $criteriaStr;
        $numOfAuto = Utility::getScalarSQL($sql);

        //Calculate and return number of pages
        $numOfPages = ceil($numOfAuto / $configuration->searchPageSize);

        //Fix invalid page number
        if ($page >= $numOfPages) {
            $page = 0;
        }
        //Calculate offset of the auto record for the current page number
        $offset = $page * $configuration->searchPageSize;

        $pageStr = " LIMIT " . $offset . ", " . $configuration->searchPageSize;

        $orderExpression = " ORDER BY a.updated DESC, a.premiumstatus DESC, a.id DESC";

        //Get list of auto
        $sql = AutoRepository::SQL_GET_AUTO_FIELDS . $criteriaStr . $orderExpression . $pageStr;
        $listOfAuto = Utility::getObjectCollectionFromSQL($sql, 'Auto');

        if ($listOfAuto != null) {
            foreach ($listOfAuto as $auto) {
                if ($auto->photoname != null) {
                    $auto->smallphotoname = $configuration->smallImagePrefix . $auto->photoname;
                    $auto->bigphotoname = $configuration->bigImagePrefix . $auto->photoname;
                }
            }
        }

        return $listOfAuto;
    }

    /**
     * Returns list of new added auto
     * @param int $numOfAutoToGet
     * @return Auto[] 
     */
    public static function GetNewAddedAuto($numOfAutoToGet) {
        global $configuration;

        $criteriaList = Array();
        $criteriaStr = " where (a.approved = 1) ";

        $pageStr = " LIMIT " . $numOfAutoToGet;

        $orderExpression = " ORDER BY a.updated DESC, a.premiumstatus DESC, a.id DESC";

        //Get list of auto
        $sql = AutoRepository::SQL_GET_AUTO_FIELDS . $criteriaStr . $orderExpression . $pageStr;
        $listOfAuto = Utility::getObjectCollectionFromSQL($sql, 'Auto');

        if ($listOfAuto != null) {
            foreach ($listOfAuto as $auto) {
                if ($auto->photoname != null) {
                    $auto->smallphotoname = $configuration->smallImagePrefix . $auto->photoname;
                    $auto->bigphotoname = $configuration->bigImagePrefix . $auto->photoname;
                }
            }
        }

        return $listOfAuto;
    }

    /**
     * Determines whether auto is valid
     * @param Auto $auto
     * @param UIErrorInfo[] $errors 
     */
    public static function IsValid($auto, &$errors) {
        $errors = AutoRepository::ValidateAutoInfo($auto);
        return (count($errors) <= 0);
    }

    /**
     * Validate auto fields
     * @param Auto $auto
     * @return UIErrorInfo[]
     */
    private static function ValidateAutoInfo($auto) {
        $errors = Array();

        if (!is_numeric($auto->id)) {
            $errors[] = new UIErrorInfo(Utility::getlocaltext("auto_not_selected"), "id");
        }

        if (!is_numeric($auto->id_mark) || ($auto->id_mark <= 0) || (AutoRepository::GetAutoMarkById($auto->id_mark) == null)) {
            $errors[] = new UIErrorInfo(Utility::getlocaltext("brand_name_not_selected"), "id_mark");
        }

        if (!is_numeric($auto->id_model) || ($auto->id_model <= 0) || (AutoRepository::GetAutoModelById($auto->id_model) == null)) {
            $errors[] = new UIErrorInfo(Utility::getlocaltext("model_name_not_selected"), "id_model");
        }

        $auto->modification = trim($auto->modification);

        if (strlen($auto->modification) > 25) {
            $errors[] = new UIErrorInfo(Utility::getlocaltext("modification_name") . ": " .
                            str_replace("{0}", "25", Utility::getlocaltext("str_maximum_length"))
                            , "modification");
        }

        if (!is_numeric($auto->user_id) || ($auto->user_id <= 0) || (UserRepository::GetUser($auto->user_id) == null)) {
            $errors[] = new UIErrorInfo(Utility::getlocaltext("invalid_user"), "user_id");
        }

        if (!is_numeric($auto->autostate_id) || ($auto->autostate_id <= 0) || (AutoRepository::GetAutoStateById($auto->autostate_id) == null)) {
            $errors[] = new UIErrorInfo(Utility::getlocaltext("autostate_not_selected"), "autostate_id");
        }

        if (!is_numeric($auto->carcase_id) || ($auto->carcase_id <= 0) || (AutoRepository::GetCarCaseById($auto->carcase_id) == null)) {
            $errors[] = new UIErrorInfo(Utility::getlocaltext("carcase_type_not_selected"), "carcase_id");
        }

        if (!is_numeric($auto->color_id) || ($auto->color_id <= 0) || (AutoRepository::GetColorById($auto->color_id) == null)) {
            $errors[] = new UIErrorInfo(Utility::getlocaltext("color_not_selected"), "color_id");
        }

        if (!is_numeric($auto->fuel_id) || ($auto->fuel_id <= 0) || (AutoRepository::GetFuelById($auto->fuel_id) == null)) {
            $errors[] = new UIErrorInfo(Utility::getlocaltext("fuel_not_selected"), "fuel_id");
        }

        if (!is_numeric($auto->fuelsupply_id) || ($auto->fuelsupply_id <= 0) || (AutoRepository::GetFuelSupplyById($auto->fuelsupply_id) == null)) {
            $errors[] = new UIErrorInfo(Utility::getlocaltext("fuel_supply_not_selected"), "fuelsupply_id");
        }

        if (!is_numeric($auto->kpp_id) || ($auto->kpp_id <= 0) || (AutoRepository::GetKppById($auto->kpp_id) == null)) {
            $errors[] = new UIErrorInfo(Utility::getlocaltext("kpp_not_selected"), "kpp_id");
        }

        if (!is_numeric($auto->transmission_id) || ($auto->transmission_id <= 0) || (AutoRepository::GetTransmissionById($auto->transmission_id) == null)) {
            $errors[] = new UIErrorInfo(Utility::getlocaltext("transmission_not_selected"), "transmission_id");
        }

        if (($auto->country_id == "-") || (LocationRepository::GetCountryById($auto->country_id) == null)) {
            $errors[] = new UIErrorInfo(Utility::getlocaltext("country_not_selected"), "country_id");
        } else if (($auto->region_id == "-") || (LocationRepository::GetRegionById($auto->country_id, $auto->region_id) == null)) {
            $errors[] = new UIErrorInfo(Utility::getlocaltext("region_not_selected"), "region_id");
        } else if (!is_numeric($auto->city_id) || ($auto->city_id <= 0) || (LocationRepository::GetCityById($auto->region_id, $auto->city_id) == null)) {
            $errors[] = new UIErrorInfo(Utility::getlocaltext("city_not_selected"), "city_id");
        }

        if (!is_numeric($auto->price) || ($auto->price <= 0)) {
            $errors[] = new UIErrorInfo(Utility::getlocaltext("price_invalid"), "price");
        }

        if (!is_numeric($auto->year) || ($auto->year <= 0)) {
            $errors[] = new UIErrorInfo(Utility::getlocaltext("year_not_selected"), "year");
        } else if ($auto->year < 1769) {
            $errors[] = new UIErrorInfo(Utility::getlocaltext("year_invalid"), "year");
        }

        if (!is_numeric($auto->mileage) || ($auto->mileage <= 0)) {
            $errors[] = new UIErrorInfo(Utility::getlocaltext("mileage_invalid"), "mileage");
        }

        if (!is_numeric($auto->volume) || ($auto->volume <= 0)) {
            $errors[] = new UIErrorInfo(Utility::getlocaltext("engine_volume_invalid"), "volume");
        }

        if ($auto->power) {
            if (!is_numeric($auto->power) || ($auto->power <= 0)) {
                $errors[] = new UIErrorInfo(Utility::getlocaltext("power_invalid"), "power");
            }
        }

        if ($auto->consumption) {
            if (!is_numeric($auto->consumption) || ($auto->consumption <= 0)) {
                $errors[] = new UIErrorInfo(Utility::getlocaltext("consumption_invalid"), "consumption");
            }
        }

        if ($auto->acceleration) {
            if (!is_numeric($auto->acceleration) || ($auto->acceleration <= 0)) {
                $errors[] = new UIErrorInfo(Utility::getlocaltext("acceleration_invalid"), "acceleration");
            }
        }

        if ($auto->cylinders) {
            $auto->cylinders = trim($auto->cylinders);
            if (strlen($auto->cylinders) > 55) {
                $errors[] = new UIErrorInfo(Utility::getlocaltext("num_of_cylinders") . ": " .
                                str_replace("{0}", "55", Utility::getlocaltext("str_maximum_length"))
                                , "cylinders");
            }
        }

        if ($auto->description) {
            $auto->description = trim($auto->description);
            if (strlen($auto->description) > 1000) {
                $errors[] = new UIErrorInfo(Utility::getlocaltext("description") . ": " .
                                str_replace("{0}", "1000", Utility::getlocaltext("str_maximum_length"))
                                , "description");
            }
        }

        if ($auto->exchange == null) {
            $auto->exchange = 0;
        }

        if (($auto->exchange != 0) && ($auto->exchange != 1)) {
            $errors[] = new UIErrorInfo(Utility::getlocaltext("exchange_status_invalid"), "exchange");
        }

        if ($auto->tuning == null) {
            $auto->tuning = 0;
        }

        if (($auto->tuning != 0) && ($auto->tuning != 1)) {
            $errors[] = new UIErrorInfo(Utility::getlocaltext("tuning_status_invalid"), "tuning");
        }

        if ($auto->tuningdesc) {
            $auto->tuningdesc = trim($auto->tuningdesc);
            if (strlen($auto->tuningdesc) > 1000) {
                $errors[] = new UIErrorInfo(Utility::getlocaltext("tuning_description") . ": " .
                                str_replace("{0}", "1000", Utility::getlocaltext("str_maximum_length"))
                                , "tuningdesc");
            }
        }

        if ($auto->notcustoms == null) {
            $auto->notcustoms = 0;
        }

        if (($auto->notcustoms != 0) && ($auto->notcustoms != 1)) {
            $errors[] = new UIErrorInfo(Utility::getlocaltext("notcustoms_status_invalid"), "notcustoms");
        }

        if ($auto->urgent == null) {
            $auto->urgent = 0;
        }

        if (($auto->urgent != 0) && ($auto->urgent != 1)) {
            $errors[] = new UIErrorInfo(Utility::getlocaltext("urgent_status_invalid"), "urgent");
        }

        if ($auto->sold == null) {
            $auto->sold = 0;
        }

        if (($auto->sold != 0) && ($auto->sold != 1)) {
            $errors[] = new UIErrorInfo(Utility::getlocaltext("sold_status_invalid"), "sold");
        }

        if ($auto->approved == null) {
            $auto->approved = ApprovedStatus::Validating;
        }

        if (($auto->approved != ApprovedStatus::Validating) && ($auto->approved != ApprovedStatus::Approved) && ($auto->approved != ApprovedStatus::Refused)) {
            $errors[] = new UIErrorInfo(Utility::getlocaltext("approved_status_invalid"), "approved");
        }


        if ($auto->premiumstatus == null) {
            $auto->premiumstatus = 0;
        }

        if (!is_numeric($auto->premiumstatus)) {
            $auto->premiumstatus = 0;
        }

        if ($auto->photos) {
            foreach ($auto->photos as $key => $value) {
                if (!($value instanceof Photo)) {
                    $errors[] = new UIErrorInfo(Utility::getlocaltext("photo_invalid_collection"), "");
                    break;
                }
            }
        }

        if ($auto->equipment) {
            foreach ($auto->equipment as $key => $value) {
                if (!($value instanceof Equipment)) {
                    $errors[] = new UIErrorInfo(Utility::getlocaltext("equipment_invalid_collection"), "");
                    break;
                }
            }
        }

        return $errors;
    }

    /**
     * Validates search criteria and fixes invalid fields
     * @param SearchAutoCriteria $criteria
     * @return SearchAutoCriteria
     */
    public static function ValidateAndFixSearchCriteria($criteria) {
        if (!is_numeric($criteria->id_mark) || ($criteria->id_mark <= 0) || (AutoRepository::GetAutoMarkById($criteria->id_mark) == null)) {
            $criteria->id_mark = -1;
        }

        if (!is_numeric($criteria->id_model) || ($criteria->id_model <= 0) || (AutoRepository::GetAutoModelById($criteria->id_model) == null)) {
            $criteria->id_model = -1;
        }

        if (!is_numeric($criteria->autostate_id) || ($criteria->autostate_id <= 0) || (AutoRepository::GetAutoStateById($criteria->autostate_id) == null)) {
            $criteria->autostate_id = -1;
        }

        if (!is_numeric($criteria->carcase_id) || ($criteria->carcase_id <= 0) || (AutoRepository::GetCarCaseById($criteria->carcase_id) == null)) {
            $criteria->carcase_id = -1;
        }

        if (!is_numeric($criteria->color_id) || ($criteria->color_id <= 0) || (AutoRepository::GetColorById($criteria->color_id) == null)) {
            $criteria->color_id = -1;
        }

        if (!is_numeric($criteria->fuel_id) || ($criteria->fuel_id <= 0) || (AutoRepository::GetFuelById($criteria->fuel_id) == null)) {
            $criteria->fuel_id = -1;
        }

        if (!is_numeric($criteria->kpp_id) || ($criteria->kpp_id <= 0) || (AutoRepository::GetKppById($criteria->kpp_id) == null)) {
            $criteria->kpp_id = -1;
        }

        if (!is_numeric($criteria->transmission_id) || ($criteria->transmission_id <= 0) || (AutoRepository::GetTransmissionById($criteria->transmission_id) == null)) {
            $criteria->transmission_id = -1;
        }

        if (($criteria->country_id == "-") || (LocationRepository::GetCountryById($criteria->country_id) == null)) {
            $criteria->country_id = "-";
            $criteria->region_id = "-";
            $criteria->city_id = -1;
        }

        if (($criteria->region_id == "-") || (LocationRepository::GetRegionById($criteria->country_id, $criteria->region_id) == null)) {
            $criteria->region_id = "-";
            $criteria->city_id = -1;
        }

        if (!is_numeric($criteria->city_id) || ($criteria->city_id <= 0) || (LocationRepository::GetCityById($criteria->region_id, $criteria->city_id) == null)) {
            $criteria->city_id = -1;
        }

        if (!is_numeric($criteria->priceFrom) || ($criteria->priceFrom <= 0)) {
            $criteria->priceFrom = "";
        }

        if (!is_numeric($criteria->priceTo) || ($criteria->priceTo <= 0)) {
            $criteria->priceTo = "";
        }

        if (is_numeric($criteria->priceFrom) && is_numeric($criteria->priceTo)) {
            if ($criteria->priceFrom > $criteria->priceTo) {
                $criteria->priceTo = $criteria->priceFrom;
            }
        }

        if (!is_numeric($criteria->yearFrom) || ($criteria->yearFrom <= 1769)) {
            $criteria->yearFrom = "";
        }

        if (!is_numeric($criteria->yearTo) || ($criteria->yearTo <= 1769)) {
            $criteria->yearTo = "";
        }

        if (is_numeric($criteria->yearFrom) && is_numeric($criteria->yearTo)) {
            if ($criteria->yearFrom > $criteria->yearTo) {
                $criteria->yearTo = $criteria->yearFrom;
            }
        }

        if (!is_numeric($criteria->volumeFrom) || ($criteria->volumeFrom <= 0)) {
            $criteria->volumeFrom = "";
        }

        if (!is_numeric($criteria->volumeTo) || ($criteria->volumeTo <= 0)) {
            $criteria->volumeTo = "";
        }

        if (is_numeric($criteria->volumeFrom) && is_numeric($criteria->volumeTo)) {
            if ($criteria->volumeFrom > $criteria->volumeTo) {
                $criteria->volumeTo = $criteria->volumeFrom;
            }
        }

        if ($criteria->exchangerequired == null) {
            $criteria->exchangerequired = 0;
        }

        if (($criteria->exchangerequired != 0) && ($criteria->exchangerequired != 1)) {
            $criteria->exchangerequired = 0;
        }

        if ($criteria->tuningrequired == null) {
            $criteria->tuningrequired = 0;
        }

        if (($criteria->tuningrequired != 0) && ($criteria->tuningrequired != 1)) {
            $criteria->tuningrequired = 0;
        }

        if ($criteria->customsrequired == null) {
            $criteria->customsrequired = 0;
        }

        if (($criteria->customsrequired != 0) && ($criteria->customsrequired != 1)) {
            $criteria->customsrequired = 0;
        }

        if ($criteria->urgentrequired == null) {
            $criteria->urgentrequired = 0;
        }

        if (($criteria->urgentrequired != 0) && ($criteria->urgentrequired != 1)) {
            $criteria->urgentrequired = 0;
        }

        if ($criteria->showsold == null) {
            $criteria->showsold = 0;
        }

        if (($criteria->showsold != 0) && ($criteria->showsold != 1)) {
            $criteria->showsold = 1;
        }

        if ($criteria->photorequired == null) {
            $criteria->photorequired = 0;
        }

        if (($criteria->photorequired != 0) && ($criteria->photorequired != 1)) {
            $criteria->photorequired = 0;
        }
        return $criteria;
    }

    /**
     * Remove auto
     * @param Auto $auto_id
     */
    public static function RemoveAuto($auto) {
        Utility::executeSQL("delete from auto_equipment where auto_id = '" . addslashes($auto->id) . "';");
        Utility::executeSQL("delete from auto where id = '" . addslashes($auto->id) . "' limit 1;");

        AutoRepository::RemoveAllPhoto($auto);
    }

    /**
     * Removes all photos assigned to auto
     * @param Auto $auto
     */
    private static function RemoveAllPhoto($auto) {
        global $configuration;
        $folder = AutoRepository::GetAutoDirectory($auto);
        $photos = AutoRepository::GetPhoto($auto->id);
        if ($photos) {
            foreach ($photos as $photo) {
                Utility::deleteFile($folder . "/" . $photo->name);
                Utility::deleteFile($folder . "/" . $photo->namesmall);
                Utility::deleteFile($folder . "/" . $photo->namebig);
                Utility::deleteFile($folder . "/" . $photo->nameoriginal);
                Utility::deleteFile($folder . "/" . $photo->namesmalloriginal);
                Utility::deleteFile($folder . "/" . $photo->namebigoriginal);
            }
        }
        Utility::deleteDir($folder);
        AutoRepository::RemoveAllPhotoInfoFromDb($auto);
    }

    /**
     * Removes all photo info assigned to auto
     * @param Auto $auto 
     */
    private static function RemoveAllPhotoInfoFromDb($auto) {
        Utility::executeSQL("delete from photo where auto_id = '" . addslashes($auto->id) . "';");
    }

    /**
     * Remove photo
     * @param Auto $auto
     * @param int $photo_id
     */
    public static function RemovePhoto($auto, $photo_id) {
        global $configuration;
        $folder = AutoRepository::GetAutoDirectory($auto);
        $photo = AutoRepository::GetPhotoById($photo_id);
        if ($photo) {
            Utility::deleteFile($folder . "/" . $photo->name);
            Utility::deleteFile($folder . "/" . $photo->namesmall);
            Utility::deleteFile($folder . "/" . $photo->namebig);
            Utility::deleteFile($folder . "/" . $photo->nameoriginal);
            Utility::deleteFile($folder . "/" . $photo->namesmalloriginal);
            Utility::deleteFile($folder . "/" . $photo->namebigoriginal);
            Utility::executeSQL("delete from photo where id = '" . addslashes($photo->id) . "';");
        }
    }

    /**
     * Updates the photo
     * @param Photo $photo
     */
    public static function UpdatePhoto($photo) {
        $sql = "UPDATE `photo` SET `auto_id` = '" . addslashes($photo->auto_id) . "', `name` = '" . addslashes($photo->name) . "', `nameoriginal` = '" .
                addslashes($photo->nameoriginal) . "', `pathtodir` = '" .
                addslashes($photo->pathtodir) . "', `index` =  '" . addslashes($photo->index) . "', `default` = '" .
                addslashes($photo->default) . "' WHERE `id` = '" . addslashes($photo->id) . "';";
        Utility::executeSQL($sql);
    }

    /**
     * Add information about photo to database
     * @param Photo $photo 
     */
    public static function AddPhotoInfoToDb($photo) {

        $sql = "insert into photo (`id`, `auto_id`, `name`, `nameoriginal`, `pathtodir`, `index`, `default`) VALUES
                (NULL, '" . addslashes($photo->auto_id) . "', '" . addslashes($photo->name) . "', '" . addslashes($photo->nameoriginal) .
                "', '" . addslashes($photo->pathtodir) . "', '" . addslashes($photo->index) . "', '" . addslashes($photo->default) . "');";
        return Utility::executeInsertSQL($sql);
    }

    /**
     * Determines whether auto belongs to the user
     * @param int $auto_id
     * @param int $user_id
     * @return bool
     */
    public static function IsAutoBelongsToUser($auto_id, $user_id) {
        $auto = AutoRepository::GetAuto($auto_id);
        return ($auto != null) && ($auto->user_id == $user_id);
    }

    /**
     * Determines whether auto belongs to the user
     * @param Auto $auto
     * @param int $user_id
     * @return bool
     */
    public static function IsAutoBelongsToUser2($auto, $user_id) {
        return ($auto != null) && ($auto->user_id == $user_id);
    }

    /**
     * Determines whether auto belongs to the user or user is in admin role
     * @param Auto $auto
     * @param User $user
     * @return bool
     */
    public static function IsAutoBelongsToUser3($auto, $user) {
        return ($auto != null) && ($user != null) && (($auto->user_id == $user->id) || ($user->role == UserRoles::Admin));
    }

    /**
     * Get all user's auto
     * @param int $user_id
     * @param bool $notsoldonly
     * @return Auto[]
     */
    public static function GetUserAuto($user_id, $notsoldonly = false) {
        global $configuration;

        $soldparam = $notsoldonly ? "(sold = 0) and " : "";
        $listOfAuto = Utility::getObjectCollectionFromSQL(AutoRepository::SQL_GET_AUTO_FIELDS . " where " . $soldparam . "(user_id = '" . addslashes($user_id) . "');", 'Auto');

        if ($listOfAuto != null) {
            foreach ($listOfAuto as $auto) {
                if ($auto->photoname != null) {
                    $auto->smallphotoname = $configuration->smallImagePrefix . $auto->photoname;
                    $auto->bigphotoname = $configuration->bigImagePrefix . $auto->photoname;
                }
            }
        }

        return $listOfAuto;
    }

    /**
     * Get number of not approved user's auto
     * @param int $user_id
     * @return int
     */
    public static function GetNumOfNotApprovedUserAuto($user_id) {
        global $configuration;

        $sql = AutoRepository::SQL_GET_NUM_OF_AUTO_FIELDS . " where (user_id = '" . addslashes($user_id) . "') and ((approved = '" . addslashes(ApprovedStatus::Validating) . "') or (approved = '" . addslashes(ApprovedStatus::Refused) . "'))";
        $numOfAuto = Utility::getScalarSQL($sql);
        return $numOfAuto;
    }

    /**
     * Get auto which are satisfied the filter
     * @param AutoFilter $autoFilter
     * @param int $numOfPages
     * @param int $page
     * @return Auto[]
     */
    public static function GetAutoWithFilter($filter, &$numOfPages, &$page = 0) {
        global $configuration;

        $approved = isset($filter->approved) ? " and (approved = '" . addslashes($filter->approved) . "')" : "";
        $sold = isset($filter->sold) ? " and (sold = '" . addslashes($filter->sold) . "')" : "";
        $premiumstatus = isset($filter->premiumstatus) ? " and (premiumstatus = '" . addslashes($filter->premiumstatus) . "')" : "";

        $criteriaStr = " where (1 = 1) " . $approved . $sold . $premiumstatus;

        //Get number of auto which are satisfied the search criteria
        $sql = AutoRepository::SQL_GET_NUM_OF_AUTO_FIELDS . $criteriaStr;
        $numOfAuto = Utility::getScalarSQL($sql);

        //Calculate and return number of pages
        $numOfPages = ceil($numOfAuto / $configuration->managementPageSize);

        //Fix invalid page number
        if ($page >= $numOfPages) {
            $page = 0;
        }
        //Calculate offset of the auto record for the current page number
        $offset = $page * $configuration->managementPageSize;

        $pageStr = " LIMIT " . $offset . ", " . $configuration->managementPageSize;

        $orderExpression = " ORDER BY a.updated DESC, a.premiumstatus DESC, a.id DESC";

        //Get list of auto
        $listOfAuto = Utility::getObjectCollectionFromSQL(AutoRepository::SQL_GET_AUTO_FIELDS . $criteriaStr . $orderExpression . $pageStr . " ;", 'Auto');

        if ($listOfAuto != null) {
            foreach ($listOfAuto as $auto) {
                if ($auto->photoname != null) {
                    $auto->smallphotoname = $configuration->smallImagePrefix . $auto->photoname;
                    $auto->bigphotoname = $configuration->bigImagePrefix . $auto->photoname;
                }
            }
        }

        return $listOfAuto;
    }

    /**
     * Get all auto marks
     * @return  AutoMark[]
     */
    public static function GetAutoMarks() {
        return Utility::getObjectCollectionFromSQL("select * from automark order by mark_name asc;", 'AutoMark');
    }

    /**
     * Get all auto marks which has logos
     * @return  AutoMark[]
     */
    public static function GetAutoMarksWithLogo() {
        return Utility::getObjectCollectionFromSQL("select * from automark where not(mark_logo is null) order by mark_name asc;", 'AutoMark');
    }

    /**
     * Get auto mark by id
     * @param int $mark_id
     * @return AutoMark
     */
    public static function GetAutoMarkById($mark_id) {
        return Utility::getObjectFromSQL("select * from automark where id_mark = '" . addslashes($mark_id) . "' limit 1;", 'AutoMark');
    }

    /**
     * Get auto models for auto mark
     * @param int $mark_id
     * @return AutoModel[]
     */
    public static function GetAutoModels($mark_id) {
        return Utility::getObjectCollectionFromSQL("select * from automodel where id_mark = '" . addslashes($mark_id) . "' order by model_name asc;", 'AutoModel');
    }

    /**
     * Get auto model by id
     * @param int $model_id
     * @return AutoModel
     */
    public static function GetAutoModelById($model_id) {
        return Utility::getObjectFromSQL("select * from automodel where id_model = '" . addslashes($model_id) . "' limit 1;", 'AutoModel');
    }

    /**
     * Get well known auto modifications
     * @param int $model_id
     * @return AutoModif[]
     */
    public static function GetAutoModifications($model_id) {
        return Utility::getObjectCollectionFromSQL("select * from automodif where id_model = '" . addslashes($model_id) . "' order by name asc;", 'AutoModif');
    }

    /**
     * Get users auto modifications
     * @param int $model_id
     * @param int $limit
     * @return object[]
     */
    public static function GetUserAutoModifications($model_id, $limit) {
        return Utility::getObjectCollectionFromSQL("select distinct modification from auto where id_model = '" . addslashes($model_id) . "' and (approved = 1) order by modification asc limit " . $limit . ";");
    }

    /**
     * Get all auto states
     * @return AutoState[]
     */
    public static function GetAutoStates() {
        return Utility::getObjectCollectionFromSQL("select * from autostate order by `index` asc;", 'AutoState');
    }

    /**
     * Get autos state by id
     * @param int $autostate_id
     * @return AutoState 
     */
    public static function GetAutoStateById($autostate_id) {
        return Utility::getObjectFromSQL("select * from autostate where id = '" . addslashes($autostate_id) . "' limit 1;", 'AutoState');
    }

    /**
     * Get all car cases
     * @return CarCase[]
     */
    public static function GetCarCases() {
        return Utility::getObjectCollectionFromSQL("select * from carcase order by `index` asc;", 'CarCase');
    }

    /**
     * Gat car case by id
     * @param int $carcase_id
     * @return CarCase
     */
    public static function GetCarCaseById($carcase_id) {
        return Utility::getObjectFromSQL("select * from carcase where id = '" . addslashes($carcase_id) . "' limit 1;", 'CarCase');
    }

    /**
     * Get all colors
     * @return Color[]
     */
    public static function GetColors() {
        return Utility::getObjectCollectionFromSQL("select * from color order by `index` asc;", 'Color');
    }

    /**
     * Get color by id
     * @param int $color_id
     * @return Color 
     */
    public static function GetColorById($color_id) {
        return Utility::getObjectFromSQL("select * from color where id = '" . addslashes($color_id) . "' limit 1;", 'Color');
    }

    /**
     * Get all equipment
     * @return Equipment[]
     */
    public static function GetEquipment() {
        return Utility::getObjectCollectionFromSQL("select * from equipment order by `index` asc;", 'Equipment');
    }

    /**
     * Get equipment by id
     * @param int $equipment_id
     * @return Equipment
     */
    public static function GetEquipmentById($equipment_id) {
        return Utility::getObjectFromSQL("select * from equipment where id = '" . addslashes($equipment_id) . "' limit 1;", 'Equipment');
    }

    /**
     * Remove all equipment associated to auto
     * @param Auto $auto
     */
    private static function RemoveAutoEquipment($auto) {
        Utility::executeSQL("delete from auto_equipment where auto_id = '" . addslashes($auto->id) . "'");
    }

    /**
     * Associate equipment to auto
     * @param Auto $auto
     * @param Equipment $equipment 
     */
    private static function AddAutoEquipment($auto, $equipment) {
        Utility::executeSQL("insert into auto_equipment (`auto_id`, `equipment_id`) VALUES ('" . addslashes($auto->id) . "', '" . addslashes($equipment->id) . "');");
    }

    /**
     * Get equipment of auto
     * @param int $auto_id
     * @return Equipment[]
     */
    public static function GetAutoEquipment($auto_id) {
        $sql = "select e.* from equipment e
                inner join auto_equipment as ae on e.id = ae.equipment_id
                where ae.auto_id = '" . addslashes($auto_id) . "'
                order by e.`index` asc;";

        return Utility::getObjectCollectionFromSQL($sql, 'Equipment');
    }

    /**
     * Get all fuel types
     * @return Fuel[]
     */
    public static function GetFuel() {
        return Utility::getObjectCollectionFromSQL("select * from fuel order by `index` asc;", 'Fuel');
    }

    /**
     * Get fuel type by id
     * @param int $fuel_id
     * @return Fuel
     */
    public static function GetFuelById($fuel_id) {
        return Utility::getObjectFromSQL("select * from fuel where id = '" . addslashes($fuel_id) . "' limit 1;", 'Fuel');
    }

    /**
     * Get all fuel supply types
     * @return FuelSupply[]
     */
    public static function GetFuelSupply() {
        return Utility::getObjectCollectionFromSQL("select * from fuelsupply order by `index` asc;", 'FuelSupply');
    }

    /**
     * Get fuel supply by id
     * @param int $fuelsupply_id
     * @return FuelSupply
     */
    public static function GetFuelSupplyById($fuelsupply_id) {
        return Utility::getObjectFromSQL("select * from fuelsupply where id = '" . addslashes($fuelsupply_id) . "' limit 1;", 'FuelSupply');
    }

    /**
     * Get all kpp types
     * @return Kpp[]
     */
    public static function GetKpp() {
        return Utility::getObjectCollectionFromSQL("select * from kpp order by `index` asc;", 'Kpp');
    }

    /**
     * Get kpp type by id
     * @return Kpp
     */
    public static function GetKppById($kpp_id) {
        return Utility::getObjectFromSQL("select * from kpp where id = '" . addslashes($kpp_id) . "' limit 1;", 'Kpp');
    }

    /**
     * Get all photo for auto
     * @param int $auto_id
     * @return Photo[]
     */
    public static function GetPhoto($auto_id) {
        global $configuration;

        $photos = Utility::getObjectCollectionFromSQL("select * from photo where auto_id = '" . addslashes($auto_id) . "' order by `index` asc;", 'Photo');
        if ($photos != null) {
            foreach ($photos as $photo) {
                $photo->namesmall = $configuration->smallImagePrefix . $photo->name;
                $photo->namebig = $configuration->bigImagePrefix . $photo->name;
                $photo->namesmalloriginal = $configuration->smallImagePrefix . $photo->nameoriginal;
                $photo->namebigoriginal = $configuration->bigImagePrefix . $photo->nameoriginal;
            }
        }
        return $photos;
    }

    /**
     * Get number of photos for auto
     * @param int $auto_id
     * @return int
     */
    public static function GetNumberOfPhotos($auto_id) {
        return Utility::getScalarSQL("select count(*) from photo where auto_id = '" . addslashes($auto_id) . "';");
    }

    /**
     * Get max index of photos for auto
     * @param int $auto_id
     * @return int
     */
    public static function GetMaxPhotoIndex($auto_id) {
        return Utility::getScalarSQL("select max(`index`) from photo where auto_id = '" . addslashes($auto_id) . "';");
    }

    /**
     * Returns photo by id
     * @param int $photo_id
     * @return Photo
     */
    public static function GetPhotoById($photo_id) {
        global $configuration;

        $photo = Utility::getObjectFromSQL("select * from photo where id = '" . addslashes($photo_id) . "' limit 1;", 'Photo');
        if ($photo != null) {
            $photo->namesmall = $configuration->smallImagePrefix . $photo->name;
            $photo->namebig = $configuration->bigImagePrefix . $photo->name;
            $photo->namesmalloriginal = $configuration->smallImagePrefix . $photo->nameoriginal;
            $photo->namebigoriginal = $configuration->bigImagePrefix . $photo->nameoriginal;
        }
        return $photo;
    }

    /**
     * Get all transmission types
     * @return Transmission[]
     */
    public static function GetTransmission() {
        return Utility::getObjectCollectionFromSQL("select * from transmission order by `index` asc;", 'Transmission');
    }

    /**
     * Get transmission type
     * @return Transmission
     */
    public static function GetTransmissionById($transmission_id) {
        return Utility::getObjectFromSQL("select * from transmission where id = '" . addslashes($transmission_id) . "' limit 1;", 'Transmission');
    }

}

?>
