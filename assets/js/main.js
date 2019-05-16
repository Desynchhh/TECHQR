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


function checkFields(amount, formID){
    var emptyFields = Array();
        //Check title
    var title = document.getElementById("title").value;
    if(!title){
        emptyFields.push("Opgavetitel ");
    }

    for(var i = 1; i <= amount; i++){
            //Get answer field
        var answerValue = document.getElementById("answer"+i).value;
            //Get check point field
        var pointsValue = document.getElementById("points"+i).value;
        //Check answer field is not empty
        if(!answerValue){
            emptyFields.push("Svarmulighed "+i+" ");
        }
        //Check points field is not empty and is a number
        if(!pointsValue || isNaN(pointsValue)){
            emptyFields.push("Point "+i+" ");
        }
    }
    
    //Check if emptyFields array is empty
    var noEmptyFields = (emptyFields && emptyFields.length) ? false : true;
    if(noEmptyFields){
            //If empty, submit create form
        document.getElementById(formID).submit();
    } else {
            //Else, show missing field number
        alert("Du mangler at udfylde felterne: " + emptyFields);
    }
}


//View more results per page
function pagPerPage(offset, e_id = null){
    //Get value from the view's <select> tag
    var per_page = document.getElementById('pag_per_page').value;
    //Get the current url and split each segment into an array
    var url = window.location + '';
    var url_segments = url.split("/");
    //Create array of possible indexes, along with a counter
    var nums = (e_id != null) ? [e_id, per_page, offset] : [per_page, offset];
    var numCount = 0;
    //Construct new url based on segments
    var newUrl = "";
    for(var i = 0; i < url_segments.length; i++){
        if(isNaN(url_segments[i]) || url_segments[i] == ""){
            //Add segment to new url
            var newSegment = (i == 0) ? url_segments[i] : '/'+url_segments[i];
            newUrl = newUrl+newSegment;
        } else {
            var newSegment = '/'+nums[numCount];
            newUrl = newUrl+newSegment;
            numCount++;
        }
    }
    
    //"Reload" page with the new per_page parameter
    window.location.assign(newUrl);
    //window.location.replace(newUrl);
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
