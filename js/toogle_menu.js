
function gestore_Eventi(){

var navLinks = document.getElementById("nav-links");

document.getElementById("exit-icon").addEventListener('click',function hideMenu(){
    navLinks.style.right = "-200px";
});

document.getElementById("menu-icon").addEventListener('click', function showMenu(){
    navLinks.style.right = "0";
});
}

    
