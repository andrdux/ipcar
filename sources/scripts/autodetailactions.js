$(document).ready(function() {
    $('#imagepreview').load(function() {
        doLoadingImage(false);
    });
});

function loadPhoto(url){
    $("#imagepreview").attr("src", $("#emptyImageUrl").val());
    doLoadingImage(true);
    $("#imagepreview").attr("src", url);
}

function doLoadingImage(loading){
    if(loading){
        $("#imagepreview").css("display", "none");
        $("#progress").css("display", "block");
    }
    else{
        $("#progress").css("display", "none");
        $("#imagepreview").css("display", "block");
    }
}

function openPhotoGallery(url){
    window.open(url, "photogallery", 'width=1130,height=740,toolbar=0,resizable=0');
    return false;
}