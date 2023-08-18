

$(function() {
    BASE_URL = $("#BASE_URL").val();
});

function deleteCastRow(self) {
    self.parentElement.parentElement.remove();
    casts--;

    // Re-arrange the cast numbers
    var trs = document.getElementById("cast-tbody").children;
    for (var a = 0; a < trs.length; a++) {
        var firstTd = trs[a].children[0];
        firstTd.innerHTML = (a + 1);
    }
}

function deleteCinemaRow(self) {
    // first parentElement is the <td>
    // second parentElement is the <tr>
    self.parentElement.parentElement.remove();
    cinemas--;

    // Re-arrange the cinema numbers
    var trs = document.getElementById("cinema-tbody").children;
    for (var a = 0; a < trs.length; a++) {
        var firstTd = trs[a].children[0];
        firstTd.innerHTML = (a + 1);
    }
}

function doSearch(form) {
    var searchType = form.search_type.value;
    var searchValue = form.search_value.value;

    window.location.href = BASE_URL + "search/" + searchType + "/" + searchValue;
    return false;
}

function callAjax(url, data, method, callBack) {
    $.ajax({
        url: url,
        method: method,
        data: data,
        success: function(response) {
            console.log(response);
            callBack(JSON.parse(response));
        }
    });
}

function register() {
    var name = $("#name").val();
    var email = $("#email").val();
    var password = $("#password").val();
    var token = $("#token").val();

    $("#register-loader").show();

    callAjax(BASE_URL + "user/register", { "name": name, "email": email, "password": password, "token": token }, "POST", function(response) {
        $("#register-loader").hide();
        if (response.error == "") {
            swal("Registered", response.msg, "success")
                .then((value) => {
                    window.location.href = BASE_URL + "user/verify_email/" + email;
                });
        } else {
            swal("Error", response.error, "error");
        }
    });

    return false;
}

function login() {
    var email = $("#email").val();
    var password = $("#password").val();
    var token = $("#token").val();

    $("#login-loader").show();

    callAjax(BASE_URL + "user/login", { "email": email, "password": password, "token": token }, "POST", function(response) {
        $("#login-loader").hide();

        if (response.error == "") {

            swal("Logged in", response.msg, "success")
                .then((value) => {
                    window.location.href = BASE_URL;
                });
        } else {
            if (response.error == "Not verified") {
                window.location.href = BASE_URL + "user/verify_email/" + email + "/verify";
            } else {
                swal("Error", response.error, "error");
            }
        }
    });

    return false;
}