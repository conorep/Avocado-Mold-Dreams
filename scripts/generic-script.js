/*
* Conor, Pat, Regina
* SDEV328 AMD
* generic-script.js
* */

let userCheck = document.getElementById("newUserForms");

//toggle on and off, but also keep track of state with session variables.
userCheck.onclick = function showNewUserBox()
{
    if (document.getElementById("newUser").style.display === "block") {
        document.getElementById("newUser").style.display = "none";
        window.sessionStorage.setItem("displayState", 'none');
    } else {
        document.getElementById("newUser").style.display = "block";
        window.sessionStorage.setItem("displayState", 'block');
    }
}