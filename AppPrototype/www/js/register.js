$(document).ready(function() {
    $("#submit").click(function() {
        var email = $("#empid").val();
        var employee = $("#employee").val();
        var admin = $("#admin").val();
        var checked = $("#check").val();
        var emailCheck = /^[a-zA-Z0-9.!#$%&'*+/=?^_`{|}~-]+@[a-zA-Z0-9-]+(?:\.[a-zA-Z0-9-]+)*$/;
        var dataString = "empid=" + email + "&employee=" + employee + "&check="+ checked + "&submit=";

        if (document.getElementById("admin").checked){
            dataString = "empid=" + email + "&admin=" + admin + "&check="+ checked + "&submit=";
        }
        if(!document.getElementById("check").checked){
            alert("You must accept terms and conditions.");
            return false;
        }else if(!emailCheck.test(email)){
            alert("You have entered an invalid email address");
            return false;
        }
        if($.trim(email).length > 0) {
            $.ajax({
                type: "POST",
                url: "http://10.1.144.91/PHPCodeForBackend/register.php",
                data: dataString,
                crossDomain: true,
                cache: false,
                beforeSend: function () {
                    $("#submit").val('Loading...');
                },
                success: function (data) {
                    if (data == 300) {
                        alert("Your password has been sent to the email you provided. Please check your inbox.")
                        window.location = "../index.html";
                    }
                    else{
                        alert(data);
                    }
                }
            });
        }
        return false;
    });
});
