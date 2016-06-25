$(document).ready(function() {
    $('#price').autoNumeric();
    $('#year').autoNumeric();
    $('#mileage').autoNumeric();
    $('#volume').autoNumeric();
    $('#power').autoNumeric();
    $('#consumption').autoNumeric();
    $('#acceleration').autoNumeric();

    $('#modification').autocomplete({
        source: function( request, response ) {
            $.get("handlers/autoformajax.php",
            {
                action: "loadModifications",
                modelid: $("#id_model").val()
            },
            function(data){
                if(data != null){
                    var result = eval(data);
                    if(result != null){
                        response( $.map( result, function( item ) {
                            return {
                                label: item.modification,
                                value: item.modification
                            }
                        }));
                    }
                }
            }
            );

        },
        minLength: 1
    });

    $("#modification").watermark($("#modification").attr("watermark"), "watermark");
    $("#price").watermark($("#price").attr("watermark"), "watermark");
    $("#year").watermark($("#year").attr("watermark"), "watermark");
    $("#mileage").watermark($("#mileage").attr("watermark"), "watermark");
    $("#volume").watermark($("#volume").attr("watermark"), "watermark");
    $("#power").watermark($("#power").attr("watermark"), "watermark");
    $("#consumption").watermark($("#consumption").attr("watermark"), "watermark");
    $("#acceleration").watermark($("#acceleration").attr("watermark"), "watermark");
    $("#cylinders").watermark($("#cylinders").attr("watermark"), "watermark");
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

function showTuningDescription(show, blockid){
    $("#" + blockid).css('display', show ? 'block' : 'none');
}

function showCreateUserInfo(show, blockid){
    $("#" + blockid).css('display', show ? 'block' : 'none');
}