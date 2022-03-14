/*
* Conor, Pat, Regina
* SDEV328 AMD
* generic-script.js
* */

let userCheck = document.getElementById("newUserForms");

//toggle on and off new user box
userCheck.onclick = function showNewUserBox()
{
    if (document.getElementById("newUser").style.display === "block") {
        document.getElementById("newUser").style.display = "none";
    } else {
        document.getElementById("newUser").style.display = "block";
    }
}