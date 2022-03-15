//toggle appropriate question and answer boxes, toggle appropriate tables

window.onload = function() {
    //this is the select element's id
    let userQuestion = document.getElementById("questionSelects");
    //this is the value of the initial option value
    let questionID = document.getElementById("questionSelects").value;
    /*document.getElementById("questionSelects").style.display = "block";*/

    //node list of all hidden q and a boxes. make the first one show its box on load.
    const questionElements = document.getElementsByClassName('hiddenQClass');
    questionElements[0].style.display = "block";

    userQuestion.onchange = function showQuestion()
    {
        questionID = document.getElementById("questionSelects").value;

        //iterate through list of elements gathered
        for(let x = 0; x < questionElements.length; x ++)
        {
            // if id of div is equal to value of option, set to d-block. otherwise d-none.
            if(questionElements[x].id === questionID) {
                questionElements[x].style.display = "block";
            } else if (questionElements[x].id !== questionID) {
                questionElements[x].style.display = "none";
            }
        }

    }

    //this is the select element's id
    let tableChoice = document.getElementById('tableSelect');
    //this is the value of the initial option value
    let tableChoiceVal = document.getElementById('tableSelect').value;

    //node list of all hidden q and a boxes. make the first one show its box on load.
    const tableClasses = document.getElementsByClassName('tableChoices');
    console.log(tableClasses);
    tableClasses[0].style.display = "block";

    tableChoice.onchange = function showTable()
    {
        tableChoiceVal = document.getElementById('tableSelect').value;

        //iterate through list of tables gathered
        for(let x = 0; x < tableClasses.length; x ++)
        {
            // if id of div is equal to value of option, set to d-block. otherwise d-none.
            if(tableClasses[x].id === tableChoiceVal) {
                tableClasses[x].style.display = "block";
            } else if (tableClasses[x].id !== tableChoiceVal) {
                tableClasses[x].style.display = "none";
            }
        }

    }

}

/*
window.onload = function() {

}
*/

