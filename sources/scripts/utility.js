function markControlWithError(ctrlid){
    $("#" + ctrlid).removeClass("errorcontrol").addClass("errorcontrol");
}

function clearDropDownList(ddlid){
    $('#' + ddlid).find('option[value!=-]').remove();
}