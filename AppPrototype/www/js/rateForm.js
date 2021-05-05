$(document).ready(function() {
    var emailFromLoginPage = sessionStorage.getItem('email');

    $("#submitRateForm").on('submit', function(e) {
        e.preventDefault();
        var formData = new FormData(this);
        $.ajax({
            type: "POST",
            url: "http://10.1.144.91/PHPCodeForBackend/rateForm.php?email=" + emailFromLoginPage,
            data: formData,
            dataType: 'json',
            contentType: false,
            cache: false,
            processData: false,
            crossDomain: true,
            beforeSend: function () {
                $("#submit").val('Loading...');
            },
            success: function(response){
                if(response.status === 1){
                    alert(response.message);
                    window.location = "../pages/menu.html";
                    return false;
                }
                alert(response.message);
                return false;
            }
        })
        return false;
    });
});
