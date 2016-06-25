$(document).ready(function() {
    
    });

function requestToEditPage(action, id){
    $.post($("#hdnWebSiteRoot").val() + "/editauto.php",
    {
        action: action,
        autoid: id
    },
    function(data){
        document.location = "listofauto.php?page=" + $("#hdnCurrentPage").val();
    }
    );
}

function confirmationApproveAuto(id){
    if (confirm($("#hdnApproveMessage").val())){
        requestToEditPage("approve", id);
    }
    return false;
}

function confirmationRefuseAuto(id){
    if (confirm($("#hdnRefuseMessage").val())){
        requestToEditPage("refuse", id);
    }
    return false;
}

function confirmationSoldAuto(id){
    if (confirm($("#hdnSoldMessage").val())){
        requestToEditPage("sold", id);
    }
    return false;
}

function confirmationRemoveAuto(id){
    if (confirm($("#hdnRemoveMessage").val())){
        requestToEditPage("delete", id);
    }
    return false;
}

function goToSearchPage(page){
    document.location = "listofauto.php?page=" + page + "&" + $("#searchparams").val();
    return false;
}