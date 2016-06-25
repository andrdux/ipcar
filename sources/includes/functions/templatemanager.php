<?php

/**
 * Contains methods for template management
 */
class TemplateManager {

    public static $MasterPageName;

    /**
     * Regiter file in template's header
     * @param string $file
     */
    public static function RegisterHeaderFile($file) {
        global $smarty;

        $fileextension = strtolower(Utility::getFileExtension($file));
        switch ($fileextension) {
            case ".css":
                $pagecss = $smarty->get_template_vars("page_css");
                if (!$pagecss) {
                    $pagecss = array();
                }
                if (!in_array($file, $pagecss)) {
                    array_push($pagecss, $file);
                }
                $smarty->assign("page_css", $pagecss);
                break;
            case ".js":
                $pagejs = $smarty->get_template_vars("page_js");
                if (!$pagejs) {
                    $pagejs = array();
                }
                if (!in_array($file, $pagejs)) {
                    array_push($pagejs, $file);
                }
                $smarty->assign("page_js", $pagejs);
                break;
        }
    }

    /**
     * Set page title
     * @param string $title 
     */
    public static function SetPageTitle($title) {
        global $smarty;
        $smarty->assign("page_title", $title);
    }

    /**
     * Set page keywords
     * @param string $keywords
     */
    public static function SetPageKeywords($keywords) {
        global $smarty;
        $smarty->assign("page_keywords", $keywords);
    }

    /**
     * Set page description
     * @param string $description
     */
    public static function SetPageDescription($description) {
        global $smarty;
        $smarty->assign("page_description", $description);
    }

    /**
     * Set page entry text
     * @param string $entrytext
     */
    public static function SetPageEntryText($entrytext) {
        global $smarty;
        $smarty->assign("entry_text", $entrytext);
    }

    /**
     * Displays the template
     * @param string $templatename 
     */
    public static function Display($templatename) {
        global $smarty;
        $smarty->display($templatename);
    }

    /**
     * Fetch the template to string
     * @param string $templatename
     */
    public static function Fetch($templatename) {
        global $smarty;
        return $smarty->fetch($templatename);
    }

    /**
     * Displays page's layout
     * @param string $masterpage
     */
    public static function DisplayLayout($pagename = "", $masterpage = "") {
        global $smarty;

        $smarty->assign("page_name", $pagename);

        if ($masterpage == "") {
            $smarty->display(TemplateManager::$MasterPageName);
        } else {
            $smarty->display($masterpage);
        }
    }

    /**
     * Applies htmlentities to each field of the object
     * @param object $obj
     * @return <type>
     */
    private static function HtmlEntitiesToObject($obj) {
        if (gettype($obj) == "array") {
            foreach ($obj as $key => $val) {
                $obj[$key] = TemplateManager::HtmlEntitiesToObject($val);
            }
            return $obj;
        } else if (gettype($obj) == "object") {
            foreach ($obj as $prpname => $propvalue) {
                $obj->$prpname = TemplateManager::HtmlEntitiesToObject($obj->$prpname);
                return $obj;
            }
        } else {
            $obj = htmlentities($obj, ENT_QUOTES, "utf-8");
            return $obj;
        }
    }

    /**
     * Assign value to template variable
     * @param string $key
     * @param object $value 
     */
    public static function Assign($key, $value) {
        global $smarty;

        $value = TemplateManager::HtmlEntitiesToObject($value);

        $smarty->assign($key, $value);
    }

}

?>
