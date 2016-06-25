<?php

require_once '../init.php';
require_once '../includes/functions/autorepository.php';
require_once '../includes/functions/userrepository.php';

//Get logged user
$user = UserRepository::GetLoggedUser();
//if user is not logged in then exit
if ($user == null) {
    exit;
}

$autoid = Utility::getPageParam("autoid", -1);
$auto = AutoRepository::GetAuto($autoid);
//if auto is not exists or sold then exit
if (($auto == null) || ($auto->sold == 1)) {
    exit;
}

//if auto is not belongs the logged user then exit
if (!AutoRepository::IsAutoBelongsToUser3($auto, $user)) {
    exit;
}

$action = Utility::getPageParam("action", "");

$result = Array();
$errors = Array();

switch ($action) {
    case "getallphotos":
        $photos = AutoRepository::GetPhoto($autoid);
        TemplateManager::Assign("photos", $photos);
        TemplateManager::Display("uploadphotolist.html");
        break;
    case "upload":
        $numberOfPhotos = AutoRepository::GetNumberOfPhotos($auto->id);

        if ($numberOfPhotos >= $configuration->maxAllowedNumberOfPhotos) {
            $result["error"] = new UIErrorInfo(str_replace("{0}", $configuration->maxAllowedNumberOfPhotos, Utility::getlocaltext("max_number_of_photo_exceeded")), "");

            if (!empty($_FILES["file"]["tmp_name"])) {
                Utility::deleteFile($_FILES["file"]["tmp_name"]);
            }
        } else {
            if (empty($_FILES["file"]["tmp_name"]) || $_FILES["file"]["tmp_name"] == "none") {
                $result["error"] = new UIErrorInfo(Utility::getlocaltext("cant_upload_photo"), "");
            } else {
                $sourcFileExtension = Utility::getFileExtension($_FILES["file"]["name"]);
                if (!in_array($sourcFileExtension, $configuration->allowedFileExtensions)) {
                    $result["error"] = new UIErrorInfo(str_replace("{0}", join(", ", $configuration->allowedFileExtensions), Utility::getlocaltext("invalid_photo_extension")), "");
                } else {
                    $autodir = AutoRepository::GetAutoDirectory($auto);

                    $uniqueFileName = Utility::getUniqueJPEGFileName("auto");
                    $originalUniqueFileName = Utility::getUniqueJPEGFileName("auto");

                    $sourcePhotoName = "src_" . $uniqueFileName;

                    $bigPhotoName = $configuration->bigImagePrefix . $uniqueFileName;
                    $mediumPhotoName = $uniqueFileName;
                    $smallPhotoName = $configuration->smallImagePrefix . $uniqueFileName;

                    $origBigPhotoName = $configuration->bigImagePrefix . $originalUniqueFileName;
                    $origMediumPhotoName = $originalUniqueFileName;
                    $origSmallPhotoName = $configuration->smallImagePrefix . $originalUniqueFileName;


                    if (copy($_FILES["file"]["tmp_name"], $autodir . '/' . $sourcePhotoName)) {

                        $im1Result = Utility::createThumbnailImage($autodir . '/' . $sourcePhotoName, $autodir . '/' . $origBigPhotoName, $configuration->bigPhotoSize->width, $configuration->bigPhotoSize->height);
                        $im2Result = Utility::createThumbnailImage($autodir . '/' . $sourcePhotoName, $autodir . '/' . $origMediumPhotoName, $configuration->mediumPhotoSize->width, $configuration->mediumPhotoSize->height);
                        $im3Result = Utility::createThumbnailImage($autodir . '/' . $sourcePhotoName, $autodir . '/' . $origSmallPhotoName, $configuration->smallPhotoSize->width, $configuration->smallPhotoSize->height);

                        if ($im1Result && $im2Result && $im3Result) {

                            $cpRes1 = Utility::createWatermarkText($autodir . '/' . $origBigPhotoName, $autodir . '/' . $bigPhotoName);
                            $cpRes2 = Utility::createWatermarkText($autodir . '/' . $origMediumPhotoName, $autodir . '/' . $mediumPhotoName);
                            $cpRes3 = Utility::createWatermarkText($autodir . '/' . $origSmallPhotoName, $autodir . '/' . $smallPhotoName);

                            if ($cpRes1 && $cpRes2 && $cpRes3) {

                                //Calculate index of new photo
                                $photoIndex = AutoRepository::GetMaxPhotoIndex($auto->id);
                                if (($photoIndex == null) || !is_numeric($photoIndex)) {
                                    $photoIndex = 0;
                                } else {
                                    $photoIndex += 1;
                                }

                                $photo = new Photo();
                                $photo->auto_id = $auto->id;
                                $photo->index = $photoIndex;
                                $photo->default = ((int) AutoRepository::GetNumberOfPhotos($auto->id) <= 0) ? 1 : 0;
                                $photo->name = $uniqueFileName;
                                $photo->nameoriginal = $originalUniqueFileName;
                                $photo->pathtodir = AutoRepository::GetAutoDirectory($auto, true);

                                AutoRepository::AddPhotoInfoToDb($photo);

                                //if approve auto automatically
                                if ($configuration->autoApproveAuto) {
                                    $auto->approved = 1;
                                } else {
                                    $auto->approved = 0;
                                }

                                AutoRepository::SaveAuto($auto);

                                $result["message"] = Utility::getlocaltext("success_upload_photo");
                            } else {
                                $result["error"] = new UIErrorInfo(Utility::getlocaltext("error_image_processing"), "");
                            }
                        } else {
                            $result["error"] = new UIErrorInfo(Utility::getlocaltext("error_image_processing"), "");
                        }
                        Utility::deleteFile($_FILES["file"]["tmp_name"]);
                        Utility::deleteFile($autodir . '/' . $sourcePhotoName);
                    } else {
                        $result["error"] = new UIErrorInfo(Utility::getlocaltext("error_during_upload_photo"), "");                        
                    }
                }
            }
        }
        echo Utility::getJSON($result);
        break;
    case "remove":
        $photoid = Utility::getPageParam("photoid", -1);
        $photo = AutoRepository::GetPhotoById($photoid);
        if (($photo != null) && ($photo->auto_id == $auto->id)) {
            AutoRepository::RemovePhoto($auto, $photoid);

            //Make another photo default
            if ($photo->default == 1) {
                $otherPhotos = AutoRepository::GetPhoto($auto->id);
                if ($otherPhotos && (count($otherPhotos) > 0)) {
                    $otherPhotos[0]->default = 1;
                    AutoRepository::UpdatePhoto($otherPhotos[0]);
                }
            }

            $result["message"] = Utility::getlocaltext("success_remove_photo");
        }
        echo Utility::getJSON($result);
        break;
}
?>
