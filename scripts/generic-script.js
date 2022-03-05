/*
* Conor, Pat, Regina
* SDEV328 AMD
* generic-script.js
* */

let userCheck = document.getElementById("newUserForms");

userCheck.onclick = function showNewUserBox()
{
    if (document.getElementById("newUser").style.display === "block") {
        document.getElementById("newUser").style.display = "none";
    } else {
        document.getElementById("newUser").style.display = "block";
    }
}