var dlgSoldLink = "";

$(document).ready(function() {
    $("#dlgConfirmSold").dialog({
        width: 400,
        height: 130,
        modal: true,
        draggable: false,
        resizable: false,
        autoOpen: false
    });

    $("#dlgPhotoUpload").dialog({
        width: 690,
        height: 570,
        modal: true,
        draggable: false,
        resizable: false,
        autoOpen: false,
        title: $("#lblUploadPhoto").val(),
        close: function(event, ui) { 
            document.location = "myauto.php";
        }
    });

    $("#photoform").jqupload({
        "callback":"onphotoupload"
    });
    $("#photoform").jqupload_form();
   
});

function setDialogSold(open, link)
{
    if(open){
        dlgSoldLink = link;
        $("#dlgConfirmSold").dialog('open');
    }
    else{
        $("#dlgConfirmSold").dialog('close');
    }
    return false;
}

function setDialogUploader(open, autoid)
{
    if(open){
        $("#autoid").attr("value", autoid);
        showallphotos();
        $("#dlgPhotoUpload").dialog('open');
    }
    else{
        $("#dlgPhotoUpload").dialog('close');
    }
    return false;
}

function dlgSoldRedirectToAction(){
    if(dlgSoldLink){
        $.post(dlgSoldLink,{},
            function(data){
                setDialogSold(false, '');
                document.location = "myauto.php";
            }
        );
    }
}

function onphotoupload(return_message){
    $.unblockUI();
    eval("result="+return_message);
    if(result.error){
        $("#successContainer").css("display", "none");
        $("#errorContainer").css("display", "block");
        $("#photoMsgError").html(result.error.msg);
    }else{
        $("#errorContainer").css("display", "none");
        $("#successContainer").css("display", "block");
        $("#photoMsgSuccess").html(result.message);
    }
    showallphotos();
}

function removephoto(id)
{
    if(confirm($("#removePhotoConfirmationText").val()))
    {
        $.post("handlers/photouploader.php",
        {
            autoid:$("#autoid").val(),
            photoid: id,
            action: "remove"
        },
        function(data){
            eval("result=" + data);
            $("#photoMsg").html(result.message);
            showallphotos();
        }
        );
    }
    return false;
}

function showallphotos()
{
    if($("#autoid").val())
    {
        $.get("handlers/photouploader.php",
        {
            autoid: $("#autoid").val(),
            action: "getallphotos"
        },
        function(data){
            $("#photos").html(data);
        }
        );
    }
}