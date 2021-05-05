$(document).ready(function() {
    $("#submit").click(function () {
        var email = $("#empid").val();
        var dataString = "empid=" + email + "&submit=";
        $.ajax({
            type: "GET",
            url: "http://10.1.144.91/PHPCodeForBackend/forgotPassword.php",
            data: dataString,
            crossDomain: true,
            cache: false,
            beforeSend: function () {
                $("#submit").val('Loading...');
            },
            success: function(data){
                if(data === "This email is not registered in the system. Please sign up or contact the administrator."){
                    alert(data);
                    return false;
                } else if(data === "There was an error updating your password. Please contact the administrator."){
                    alert(data);
                    return false;
                }
                alert(data);
                window.location = "../index.html";
            }
        })
        return false;
    });
});
