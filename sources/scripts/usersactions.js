$(document).ready(function() {
    
});

function confirmationRemoveUser(id){
    if (confirm($("#hdnRemoveMessage").val())){
        $.post("users.php",
        {
            action: "remove",
            userid:id
        },
        function(data){
            document.location = document.location;
        }
        );
    }
    return false;
}

function goToSearchPage(page){
    document.location = "users.php?page=" + page + "&" + $("#searchparams").val();
    return false;
}