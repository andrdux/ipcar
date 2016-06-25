$(document).ready(function() {
    $('#priceFrom').autoNumeric();
    $('#priceTo').autoNumeric();
    $('#yearFrom').autoNumeric();
    $('#yearTo').autoNumeric();
    $('#volumeFrom').autoNumeric();
    $('#volumeTo').autoNumeric();
});

function loadAutoModels(idmark, ddlid){
    $.get("handlers/autoformajax.php",
    {
        idmark: idmark,
        action: "loadAutoModels"
    },
    function(data){
        var result = eval(data);
        clearDropDownList(ddlid);

        if(result != null){
            $.each(result, function(key, value){
                $('#' + ddlid).append($("<option></option>").attr("value", value.id_model).text(value.model_name));
            });
        }
    }
    );
}

function loadRegions(idcountry, ddlRegionsid, ddlCitiesid){
    $.get("handlers/autoformajax.php",
    {
        idcountry: idcountry,
        action: "loadRegions"
    },
    function(data){
        var result = eval(data);
        clearDropDownList(ddlRegionsid);
        clearDropDownList(ddlCitiesid);

        if(result != null){
            $.each(result, function(key, value){
                $('#' + ddlRegionsid).append($("<option></option>").attr("value", value.id).text(value.name));
            });
        }
    }
    );

}

function loadCities(idregion, ddlid){
    $.get("handlers/autoformajax.php",
    {
        idregion: idregion,
        action: "loadCities"
    },
    function(data){
        var result = eval(data);
        clearDropDownList(ddlid);
        if(result != null){
            $.each(result, function(key, value){
                $('#' + ddlid).append($("<option></option>").attr("value", value.id).text(value.name));
            });
        }
    }
    );

}

function goToSearchPage(page){
    document.location = $("#scriptname").val() + "?" + "action=search&"+ $("#searchparams").val() + "&page=" + page;
    return false;
}

function gotoDetailsPage(url){
    document.location = url;
    return false;
}
