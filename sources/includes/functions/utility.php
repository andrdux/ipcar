<?php

/**
 * Class with utility functions
 */
class Utility {

    /**
     * display styles for dump object
     */
    private static function display_dump_styles() {
        static $displayed = false;
        if (!$displayed) {
            $displayed = true;
            echo "
        <style>
            .util_dump {
                font: 10px;
                color: #000000;
                background-color: #fefcff;
                padding: 3px;
                border: 1px dashed #cccccc;
            }
            table.util_dump, table.util_dump tr td {
                border-collapse: collapse;
                border-color: gray;
            }
        </style>
        ";
        }
    }

    /**
     * dump object
     */
    public static function dump($var, $caption = "", $escape = true) {
        ob_start();
        print_r($var);
        $content = ob_get_contents();
        ob_end_clean();
        Utility::display_dump_styles();
        echo "<pre class='util_dump'>" . htmlentities($caption, ENT_QUOTES) . ' ' . ($escape ? htmlentities($content, ENT_QUOTES) : $content) . "</pre>";
    }

    /**
     * get page's param value
     */
    public static function getPageParam($name, $def_val = null) {
        if (isset($_POST[$name]))
            return $_POST[$name];
        if (isset($_GET[$name]))
            return $_GET[$name];
        return $def_val;
    }

    /**
     * Get values for object's properties from HTML form
     * @param object $obj
     * @param bool $ignoreNonExistingFields
     * @return object
     */
    public static function getObjectPropertiesFromForm($obj, $ignoreNonExistingFields = false) {
        foreach ($obj as $prpname => $propvalue) {
            if (strtolower($prpname) != "id") {
                $val = Utility::getPageParam($prpname);
                if ($ignoreNonExistingFields) {
                    if ($val != null) {
                        $obj->$prpname = $val;
                    }
                } else {
                    $obj->$prpname = $val;
                }
            }
        }
        return $obj;
    }

    /**
     * get localization text by key
     */
    public static function getlocaltext($key) {
        global $smarty;
        return $smarty->get_config_vars($key);
    }

    /**
     * get JSON for object
     */
    public static function getJSON($object) {
        return json_encode($object);
    }

    /**
     * get object from JSON
     */
    public static function getObjectFromJSON($json) {
        return json_decode($json);
    }

    /**
     * Executes SQL query and converts first row of the result to object
     * @param string $sql
     * @param string $classname
     * @return object 
     */
    public static function getObjectFromSQL($sql, $classname = "stdClass") {
        global $dbi;
        return $dbi->fetch_object($sql, $classname);
    }

    /**
     * Executes SQL query and converts all rows of the result to object collection
     * @param string $sql
     * @param string $classname
     * @return object
     */
    public static function getObjectCollectionFromSQL($sql, $classname = "stdClass") {
        global $dbi;
        return $dbi->fetch_object_collection($sql, $classname);
    }

    /**
     * Executes SQL and returns result
     * @param string $sql
     */
    public static function executeSQL($sql) {
        global $dbi;
        return $dbi->query($sql);
    }

    /**
     * Get SQL scalar result
     * @param string $sql
     */
    public static function getScalarSQL($sql) {
        global $dbi;
        return $dbi->fetch_one($sql);
    }

    /**
     * Executes insert SQL query and returns autoincrement values
     * @param string $sql
     * @return int autoincrement value 
     */
    public static function executeInsertSQL($sql) {
        global $dbi;
        $dbi->query($sql);
        return $dbi->insert_id();
    }

    /**
     * Returns file extension
     * @param string $filename
     * @return string
     */
    public static function getFileExtension($filename) {
        return strtolower(substr($filename, strrpos($filename, '.')));
    }

    /**
     * Returns unique file name with .jpg extension
     * @param string $prefix
     * @param int $uidlength
     * @return string
     */
    public static function getUniqueJPEGFileName($prefix = "", $uidlength = 5) {
        return $prefix . Utility::getUniqueId($uidlength) . ".jpg";
    }

    /**
     * Returns unique id
     * @param int $uidlength
     * @return string
     */
    public static function getUniqueId($uidlength = 5) {
        return substr(md5(uniqid(rand(), true)), 0, $uidlength);
    }

    /**
     * Returns hash for the password
     * @param strring $psw
     * @return string
     */
    public static function getPasswordHash($psw) {
        return sha1($psw);
    }

    /**
     * Determines whether directory exists
     * @param string $dir_path
     * @return bool 
     */
    public static function isDirExists($dir_path) {
        $dir_dest = @opendir($dir_path);
        if ($dir_dest) {
            @closedir($dir_dest);
            return true;
        } else {
            @closedir($dir_dest);
            return false;
        }
    }

    /**
     * Determines whether file exists
     * @param string $file_path
     * @return bool
     */
    public static function isFileExists($file_path) {
        $f_dest = @file($file_path);
        if ($f_dest) {
            return true;
        } else {
            return false;
        }
    }

    /**
     * Determines whether email is valid
     * @param string $email
     * @return bool
     */
    public static function isCorrectEmail($email) {
        if (!isset($email) || empty($email))
            return false;
        $result = preg_replace("/.+@.+\..+/is", "", $email);
        if (strlen($result) == 0)
            return true;
        else
            return false;
    }

    /**
     * Determines whether web url is valid
     * @param string $www
     * @return bool
     */
    public static function isCorrectWWW($www) {
        if (!isset($www) || empty($www))
            return false;
        $result = preg_replace("/.+\..+/is", "", $www);
        if (strlen($result) == 0)
            return true;
        else
            return false;
    }

    /**
     * Determines whether file extension is valid
     * @param string $src
     * @param string $ext
     * @return bool
     */
    public static function isValidFileExtension($src, $ext) {
        $result = preg_replace("/.+\.$ext/is", "", $src);
        if (strlen($result) == 0)
            return true;
        else
            return false;
    }

    /**
     * Returns all file names in the specified directory
     * @param string $dir_name
     * @param string[] $all_file_names
     */
    public static function getDirAllFileNames($dir_name, &$all_file_names) {
        $dir_name .= "/";
        $directory = @opendir($dir_name);
        if ($directory) {
            while ($ldir = readdir($directory)) {
                if (($ldir != '.') && ($ldir != '..') && is_dir($dir_name . $ldir)) {
                    Utility::getDirAllFileNames($dir_name . $ldir, &$all_file_names);
                }

                if (($ldir != '.') && ($ldir != '..') && !is_dir($dir_name . $ldir)) {
                    $all_file_names[] = strtolower($dir_name . $ldir);
                }
            }
            @closedir($directory);
        }
    }

    /**
     * Returns all sub directories in specified directory
     * @param string $dir_name
     * @param string[] $all_subdirs
     */
    public static function getAllSubDirs($dir_name, &$all_subdirs) {
        $dir_name .= "/";
        $directory = @opendir($dir_name);
        if ($directory) {
            while ($ldir = readdir($directory)) {
                if (($ldir != '.') && ($ldir != '..') && is_dir($dir_name . $ldir)) {
                    $all_subdirs[] = strtolower($dir_name . $ldir);
                    Utility::getAllSubDirs($dir_name . $ldir, &$all_subdirs);
                }
            }
            @closedir($directory);
        }
    }

    /**
     * Creates full copy of directory
     * @param string $dir_dest
     * @param string $dir_src
     */
    public static function copyDir($dir_dest, $dir_src) {
        @rmdir($dir_dest);
        @mkdir($dir_dest);
        $all_file_names = array();
        $all_subdirs = array();
        Utility::getDirAllFileNames($dir_src, &$all_file_names);
        Utility::getAllSubDirs($dir_src, &$all_subdirs);
        for ($i = 0; $i < sizeof($all_subdirs); $i++) {
            $all_subdirs[$i] = str_replace($dir_src . "/", "", $all_subdirs[$i]);
            @mkdir($dir_dest . "/" . $all_subdirs[$i]);
        }
        for ($i = 0; $i < sizeof($all_file_names); $i++) {
            @copy($all_file_names[$i], $dir_dest . "/" . str_replace($dir_src . "/", "", $all_file_names[$i]));
        }
    }

    /**
     * Removes the directory and all files in it
     * @param string $dir_src
     */
    public static function deleteDir($dir_src) {
        $all_file_names = array();
        $all_subdirs = array();
        Utility::getDirAllFileNames($dir_src, &$all_file_names);
        Utility::getAllSubDirs($dir_src, &$all_subdirs);
        for ($i = 0; $i < sizeof($all_file_names); $i++) {
            @unlink($all_file_names[$i]);
        }
        for ($i = 0; $i < sizeof($all_subdirs); $i++) {
            @rmdir($all_subdirs[$i]);
        }
        @rmdir($dir_src);
    }

    /**
     * Removes the file
     * @param string $file 
     */
    public static function deleteFile($file) {
        if (Utility::isFileExists($file)) {
            unlink($file);
        }
    }

    /**
     * Creates the directory
     * @param string $dir 
     */
    public static function createDir($dir) {
        global $configuration;
        if (!Utility::isDirExists($dir)) {
            @mkdir($dir, $configuration->directorypermissions);
        }
    }

    /**
     * Creates the thumbnail image
     * @param string $name_big
     * @param string $name_small
     * @param int $max_x
     * @param int $max_y
     * @return bool
     */
    public static function createThumbnailImage($name_big, $name_small, $max_x, $max_y) {
        list($x, $y, $t, $attr) = getimagesize($name_big);

        if ($t == IMAGETYPE_GIF)
            $big = imagecreatefromgif($name_big);
        else if ($t == IMAGETYPE_JPEG)
            $big = imagecreatefromjpeg($name_big);
        else if ($t == IMAGETYPE_PNG)
            $big = imagecreatefrompng($name_big);
        else
            return false;

        if ($x > $y) {
            $xs = $max_x;
            $ys = $max_x / ($x / $y);
        } else {
            $ys = $max_y;
            $xs = $max_y / ($y / $x);
        }
        $small = imagecreatetruecolor($xs, $ys);
        $res = imagecopyresampled($small, $big, 0, 0, 0, 0, $xs, $ys, $x, $y);
        imagedestroy($big);
        imagejpeg($small, $name_small);
        imagedestroy($small);
        return true;
    }

    /**
     * Redirects user to url
     * @param string $url 
     */
    public static function redirectToUrl($url) {
        if ($url != "") {
            header("Location: " . $url . "\n");
        }
        exit;
    }

    /**
     * Redirects user to local url
     * @param string $localUrl
     */
    public static function redirectToLocalUrl($localUrl = "") {
        global $configuration;
        if ($localUrl != "") {
            header("Location: " . $configuration->wwwroot . "/" . $localUrl . "\n");
        }
        exit;
    }

    /**
     * Returns URL of the current page
     * @return string
     */
    public static function getCurrentPageURL() {
        $pageURL = $_SERVER['HTTPS'] == 'on' ? 'https://' : 'http://';
        $pageURL .= $_SERVER['SERVER_PORT'] != '80' ? $_SERVER["SERVER_NAME"] . ":" . $_SERVER["SERVER_PORT"] . $_SERVER["REQUEST_URI"] : $_SERVER['SERVER_NAME'] . $_SERVER['REQUEST_URI'];
        return $pageURL;
    }

    /**
     * Create watermark text on image file and save it
     * @param string $srcFile
     * @param string $resultFile
     * @param string $text;
     * @return bool
     */
    public static function createWatermarkText($srcFile, $resultFile, $text = "") {
        global $configuration;

        if ($text == "") {
            $text = $configuration->siteName;
        }

        $text = " " . $text . " ";
        $font = $configuration->standard_photos_dir . "/fonts/ARIALNB.TTF";

        $main_img_obj = imagecreatefromjpeg($srcFile);

        $width = imagesx($main_img_obj);
        $height = imagesy($main_img_obj);
        $angle = -rad2deg(atan2((-$height), ($width)));

        $c = imagecolorallocatealpha($main_img_obj, 128, 128, 128, 90);
        $size = (($width + $height) / 2) * 2 / strlen($text);
        $box = imagettfbbox($size, $angle, $font, $text);
        $x = $width / 2 - abs($box[4] - $box[0]) / 2;
        $y = $height / 2 + abs($box[5] - $box[1]) / 2;
        imagettftext($main_img_obj, $size, $angle, $x, $y, $c, $font, $text);

        imagejpeg($main_img_obj, $resultFile);
        return true;
    }

    /**
     * Creates watermark on JPEG image file from PNG image file
     * @param string $jpegFile
     * @param string $pngFile
     * @param string $resultFile
     */
    public static function createWatermarkOnJPEGFromPNGFile($jpegFile, $pngFile, $resultFile) {
        $img = imagecreatefromjpeg($jpegFile);
        $water = imagecreatefrompng($pngFile);
        $im = Utility::createWatermarkOnJPEGFromPNG($img, $water, 100);
        imagejpeg($im, $resultFile);
        return true;
    }

    /**
     * Creates watermark on JPEG image from PNG image
     * @param resource $main_img_obj
     * @param resource $watermark_img_obj
     * @param int $alpha_level
     * @return resource
     */
    public static function createWatermarkOnJPEGFromPNG($main_img_obj, $watermark_img_obj, $alpha_level = 100) {
        $alpha_level /= 100;

        $main_img_obj_w = imagesx($main_img_obj);
        $main_img_obj_h = imagesy($main_img_obj);
        $watermark_img_obj_w = imagesx($watermark_img_obj);
        $watermark_img_obj_h = imagesy($watermark_img_obj);


        $main_img_obj_min_x = floor(( $main_img_obj_w / 2 ) - ( $watermark_img_obj_w / 2 ));
        $main_img_obj_max_x = ceil(( $main_img_obj_w / 2 ) + ( $watermark_img_obj_w / 2 ));
        $main_img_obj_min_y = floor(( $main_img_obj_h / 2 ) - ( $watermark_img_obj_h / 2 ));
        $main_img_obj_max_y = ceil(( $main_img_obj_h / 2 ) + ( $watermark_img_obj_h / 2 ));

        $return_img = imagecreatetruecolor($main_img_obj_w, $main_img_obj_h);

        for ($y = 0; $y < $main_img_obj_h; $y++) {
            for ($x = 0; $x < $main_img_obj_w; $x++) {
                $return_color = NULL;

                $watermark_x = $x - $main_img_obj_min_x;
                $watermark_y = $y - $main_img_obj_min_y;

                $main_rgb = imagecolorsforindex($main_img_obj, imagecolorat($main_img_obj, $x, $y));

                if ($watermark_x >= 0 && $watermark_x < $watermark_img_obj_w &&
                        $watermark_y >= 0 && $watermark_y < $watermark_img_obj_h) {
                    $watermark_rbg = imagecolorsforindex($watermark_img_obj, imagecolorat($watermark_img_obj, $watermark_x, $watermark_y));

                    $watermark_alpha = round(( ( 127 - $watermark_rbg['alpha'] ) / 127), 2);
                    $watermark_alpha = $watermark_alpha * $alpha_level;

                    $avg_red = Utility::getAveColor($main_rgb['red'], $watermark_rbg['red'], $watermark_alpha);
                    $avg_green = Utility::getAveColor($main_rgb['green'], $watermark_rbg['green'], $watermark_alpha);
                    $avg_blue = Utility::getAveColor($main_rgb['blue'], $watermark_rbg['blue'], $watermark_alpha);

                    $return_color = Utility::getImageColor($return_img, $avg_red, $avg_green, $avg_blue);
                } else {
                    $return_color = imagecolorat($main_img_obj, $x, $y);
                }

                imagesetpixel($return_img, $x, $y, $return_color);
            }
        }
        return $return_img;
    }

    /**
     * Get average color
     * @param int $color_a
     * @param int $color_b
     * @param int $alpha_level
     * @return float
     */
    private static function getAveColor($color_a, $color_b, $alpha_level) {
        return round(( ( $color_a * ( 1 - $alpha_level ) ) + ( $color_b * $alpha_level )));
    }

    /**
     * Get index of the color
     * @param resource $im
     * @param int $r
     * @param int $g
     * @param int $b
     * @return int
     */
    private static function getImageColor($im, $r, $g, $b) {
        $c = imagecolorexact($im, $r, $g, $b);
        if ($c != -1)
            return $c;
        $c = imagecolorallocate($im, $r, $g, $b);
        if ($c != -1)
            return $c;
        return imagecolorclosest($im, $r, $g, $b);
    }

    /**
     * Creates url compatible paramater string from object's fields
     */
    public static function objectToUrlParamsString($obj) {
        $result = array();
        if ((gettype($obj) == "array") || (gettype($obj) == "object")) {
            foreach ($obj as $key => $val) {
                $result[] = $key . "=" . $val;
            }
        }
        return join("&", $result);
    }

}

?>
