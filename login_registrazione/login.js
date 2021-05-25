
function getCookie(cname) {
    var name = cname + "=";
    var ca = document.cookie.split(';');
    for(var i = 0; i < ca.length; i++) {
        var c = ca[i];
        while (c.charAt(0) == ' ') {
            c = c.substring(1);
        }
        if (c.indexOf(name) == 0) {
            return c.substring(name.length, c.length);
        }
    }
    return "";
}
var username = getCookie("username");
var password = getCookie("password");
if(username!='') document.getElementById("log_username").value=username;
if(password!='') document.getElementById("log_password").value=password;


var log = document.getElementById("login-form");
var reg = document.getElementById("register-form");
var btn = document.getElementById("btn-color");

function register() {
    log.style.left = "-400px";
    reg.style.left = "50px";
    btn.style.left = "110px";
}

function login() {
    log.style.left = "50px";
    reg.style.left = "450px";
    btn.style.left = "0px";
}


if (location.search != "") {
    var x = location.search.substr(1).split(";");

    var y = x[0].split("=");

    if (y[1] == 'true') register();
    else login();

}
