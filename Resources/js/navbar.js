$(document).ready(function(){
    menu = document.querySelector("#menu")
    dropdown = document.querySelector("#dropdown")
    dropdown.style.height = "0px"

    menu.addEventListener("click",function(){
        if(dropdown.style.height == "0px"){
            dropdown.style.height = "100px"
            dropdown.style.border = "0.1px solid #c9c9c9"
        } else{
            dropdown.style.height = "0px"
            dropdown.style.border = "none"
        }
    })
})