$(document).ready(function() {
    sessionStorage.removeItem('email');
    $("#submit").click(function() {
        var email = $("#empid").val();
        var password = $("#password").val();
        var dataString = "empid=" + email + "&password=" + password + "&submit=";
        if(!document.getElementById("check").checked){
            alert("You must accept the basic data protection regulation.");
            return false;
        }
        $.ajax({
            type: "GET",
            url: "http://10.1.144.91/PHPCodeForBackend/login.php",
            data: dataString,
            crossDomain: true,
            cache: false,
            beforeSend: function () {
                $("#submit").val('Loading...');
            },
            success: function(data){
                if(data === "Email does not exist. Contact Administrator."){
                    alert(data);
                    return false;
                } else if(data === "The password is incorrect. Try again."){
                    alert(data);
                    return false;
                }
                sessionStorage.setItem("email", email);
                window.location = "../pages/menu.html";
            }
        })
    return false;
    });
});
