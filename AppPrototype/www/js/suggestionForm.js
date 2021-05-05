$(document).ready(function() {
    var emailFromLoginPage = sessionStorage.getItem('email');

    $("#submitForm").on('submit', function(e) {
        e.preventDefault();
        var formData = new FormData(this);
        if($.trim($("#title").val()).length > 0 & $.trim($("#location").val()).length > 0 & $.trim($("#problem").val()) != null & $("#date").val() != null & $.trim($("#improvement").val()) != null & $.trim($("#proposal").val()) != null){
            $.ajax({
                type: "POST",
                url: "http://10.1.144.91/PHPCodeForBackend/suggestionFormData.php?email=" + emailFromLoginPage,
                data: formData,
                dataType: 'json',
                contentType: false,
                cache: false,
                processData: false,
                crossDomain: true,
                success: function(response){
                    if(response.status === 1){
                        alert(response.message);
                        window.location = "../pages/menu.html";
                    }else {
                        alert(response.message);
                    }
                }
            });
        }
        return false;
    });
});
