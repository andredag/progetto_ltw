

function hideMenu(){
    var navLinks = document.getElementById("nav-links");
    navLinks.style.right = "-200px";
}

function showMenu(){
    var navLinks = document.getElementById("nav-links");
    navLinks.style.right = "0";
}


window.addEventListener('scroll', function(){
    var nav_bar = document.getElementById('nav_bar');
    nav_bar.classList.toggle("scroll_animation", window.scrollY > 0);
})
    

    
