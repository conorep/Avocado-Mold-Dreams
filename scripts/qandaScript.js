//toggle appropriate question and answer boxes

window.onload = function() {
    //this is the select element's id
    let userQuestion = document.getElementById("questionSelects");
    console.log(userQuestion);
    //this is the value of the initial option value
    let questionID = document.getElementById("questionSelects").value;
    console.log(questionID);
    document.getElementById("questionSelects").style.display = "block";

    //node list of all hidden q and a boxes. make the first one show its box on load.
    const questionElements = document.getElementsByClassName('hiddenQClass');
    questionElements[0].style.display = "block";


    userQuestion.onchange = function showQuestion()
    {
        questionID = document.getElementById("questionSelects").value;
        console.log(questionID);

        //iterate through list of elements gathered
        for(let x = 0; x < questionElements.length; x ++)
        {
            console.log(questionElements[x].id);
            // if id of div is equal to value of option, set to d-block. otherwise d-none.
            if(questionElements[x].id === questionID) {
                questionElements[x].style.display = "block";
            } else if (questionElements[x].id !== questionID) {
                questionElements[x].style.display = "none";
            }
        }

    }
}