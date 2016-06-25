$(document).ready(function() {
    $.blockUI.defaults.message = $("#blockUIMessage").html();
    $.blockUI.defaults.fadeIn = 0;
    $.blockUI.defaults.fadeOut = 400;
    $.blockUI.defaults.overlayCSS = {
        backgroundColor: '#000',
        opacity: 0.15,
        cursor: 'wait'
    };
    $.blockUI.defaults.baseZ = 10000;
    $.blockUI.defaults.css = {
        padding: 0,
        margin: 0,
        width: '20%',
        top: '45%',
        left: '40%',
        textAlign: 'center',
        color: '#000000',
        border: '1px solid #000000',
        backgroundColor: '#ffffff',
        backgroundRepeat: 'no-repeat',
        backgroundPosition: '30px 40%',
        backgroundImage: 'url(' + $("#loaderImageUrl").val() + ')',
        filter: 'alpha(opacity=50)',
        opacity: 0.5,
        cursor: 'wait',
        fontFamily: 'Verdana, Tahoma',
        fontSize: '12px',
        fontWeight: 'bold',
        padding: '0.6em',
        paddingLeft: '26px'
    }

    $(document).ajaxStart($.blockUI);
    $(document).ajaxStop($.unblockUI);
});