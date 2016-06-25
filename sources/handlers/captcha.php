<?php
 require('../includes/functions/php-captcha.inc.php');
 $aFonts = array('../pictures/standard/fonts/VeraBd.ttf', '../pictures/standard/fonts/VeraIt.ttf', '../pictures/standard/fonts/Vera.ttf');
 $oVisualCaptcha = new PhpCaptcha($aFonts, 200, 50);
 $oVisualCaptcha->Create();
?>

