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


//Check fields in assignments/create.php and assignments/edit.php are not empty
function checkFields(amount, formID){
    var emptyFields = Array();
    //Check title
    var title = document.getElementById("title");
    if(!title.value){
        emptyFields.push("Opgavetitel ");
        title.classList.add('error-field');
    } else {
        title.classList.remove('error-field');
    }

    for(var i = 1; i <= amount; i++){
        //Get answer field
        var answerElement = document.getElementById("answer"+i);
        //Get check point field
        var pointsElement = document.getElementById("points"+i);
        //Check answer field is not empty
        if(!answerElement.value){
            emptyFields.push("Svarmulighed "+i+" ");
            answerElement.classList.add('error-field');
        } else {
            answerElement.classList.remove('error-field');
        }
        //Check points field is not empty and is a number
        if(!pointsElement.value || isNaN(pointsElement.value)){
            emptyFields.push("Point "+i+" ");
            pointsElement.classList.add('error-field');
        } else {
            pointsElement.classList.remove('error-field');
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


//Runs when "departmentbox" in assignments/create.php is changed
function editDropdown(){
    //Get the correct selectbox
    var eventbox = document.getElementById("eventbox");
    var selectedDepartmentID = document.getElementById("departmentbox").value;
    
    //Remove all elements from the selectbox except the first one (it's empty)
    for(i = eventbox.options.length - 1; i >= 1; i--){
        eventbox.remove(i);
    }

    //Add events to newEvents which have the selected department
    for(i = 0; i < events.length; i++){
        if(events[i]['d_id'] == selectedDepartmentID){
            //Create option element to put event info in
            var option = document.createElement("option");
            option.text = events[i]['e_name'];
            option.value = events[i]['e_id'];
            //Add option element to event dropdown
            eventbox.add(option);
        }
    }
}


//View more results per page
function pagPerPage(offset, id = null){
    //Get value from the view's <select> tag
    var per_page = document.getElementById('pag_per_page').value;
    //Get the current url and split each segment into an array
    var url = window.location + '';
    var url_segments = url.split("/");
    //Create array of possible indexes, along with a counter
    var nums = (id != null) ? [id, per_page, offset] : [per_page, offset];
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
