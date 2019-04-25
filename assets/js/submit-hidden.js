//JS function to show a dialog box, which will call the reset function in Controllers/Events.php
function resetEvent(url){
    if(window.confirm('Er du sikker på du vil resette dette event?')){
        window.location.replace(url);
    }
}


//Same as above function, except the message is different
function deleteTeam(url){
    if(window.confirm('Er du sikker på du vil slette alle hold?')){
        window.location.replace(url);
    }
}


//Submit the form given as parameter, and set the given input fields value
function submitHidden(inputID, formID, element = null){
    //Find message for the prompt
    var warning = (element) 
        ? "Er du sikker på du vil slette "+element+" fra systemet?\nIndtast navnet/titlen for at bekræfte:" 
        : "Indtast nye navn/titel:";
    //Display prompt and get input
    var input = prompt(warning);
    if(input != null && input != ""){
        //Set input field value
        document.getElementById(inputID).value = input;
        //Submit form
        document.getElementById(formID).submit();
    }
}
