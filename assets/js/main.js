//JS function to show a dialog box, which will call the reset function in Controllers/Events.php
const resetEvent = url => {
    if(window.confirm('Er du sikker på du vil resette dette event?')){
        window.location.replace(url);
    }
}


//Same as above function, except the message is different
const deleteTeam = url => {
    if(window.confirm('Er du sikker på du vil slette alle hold?')){
        window.location.replace(url);
    }
}

const disableButton = (btnID, formID = null) => {
    document.querySelector(`#${btnID}`).disabled = true;
    if(formID){
        document.querySelector(`#${formID}`).submit();
    }
}

const enableButton = btnID => {
    document.querySelector(`#${btnID}`).disabled = false;
}

//View more results per page
const pagPerPage = (id = null) => {
    const offset = 0;
    //Get value from the view's <select> tag
    const per_page = document.getElementById('pag_per_page').value;
    //Get the current url and split each segment into an array
    const url = window.location + '';
    const url_segments = url.split("/");
    //Create array of possible indexes, along with a counter
    const nums = (id != null) ? [id, per_page, offset] : [per_page, offset];
    let numCount = 0;
    //Construct new url based on segments
    let newUrl = "";
    for(let i = 0; i < url_segments.length; i++){
        if(isNaN(url_segments[i]) || url_segments[i] == ""){
            //Add segment to new url
            let newSegment = (i == 0) ? url_segments[i] : '/'+url_segments[i];
            newUrl = newUrl+newSegment;
        } else {
            let newSegment = '/'+nums[numCount];
            newUrl = newUrl+newSegment;
            numCount++;
        }
    }
    
    //"Reload" page with the new per_page parameter
    window.location.assign(newUrl);
}


//Submit the form given as parameter, and set the given input fields value
const submitHidden = (inputID, formID, element = null) => {
    //Find message for the prompt
    const warning = (element) 
        ? "Er du sikker på du vil slette "+element+" fra systemet?\nIndtast navnet/titlen for at bekræfte:" 
        : "Indtast nye navn/titel:";
    //Display prompt and get input
    const input = prompt(warning);
    if(input != null && input != ""){
        //Set input field value
        document.getElementById(inputID).value = input;
        //Submit form
        document.getElementById(formID).submit();
    }
}