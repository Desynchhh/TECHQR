//Runs when "departmentbox" in assignments/create.php is changed
const editDropdown = () => {
    //Get the correct selectbox
    const eventbox = document.getElementById("eventbox");
    const selectedDepartmentID = document.getElementById("departmentbox").value;
    
    //Remove all elements from the selectbox except the first one (it's empty)
    for(i = eventbox.options.length - 1; i >= 1; i--){
        eventbox.remove(i);
    }

    //Add events to newEvents which have the selected department
    for(i = 0; i < events.length; i++){
        if(events[i]['d_id'] == selectedDepartmentID){
            //Create option element to put event info in
            let option = document.createElement("option");
            option.text = events[i]['e_name'];
            option.value = events[i]['e_id'];
            //Add option element to event dropdown
            eventbox.add(option);
        }
    }
}


//Check fields in assignments/create.php and assignments/edit.php are not empty
const checkFields = formID => {
    //Disable submit button, so the user doesn't accidentally click twice
    const submitBtn = document.querySelector("#submitBtn");
    submitBtn.disabled = true;
    //Get requested amount of answers
    const amount = document.querySelector("#answerAmount").value;
    //Check title
    const title = document.querySelector("#title");
    //Instantiate array to store empty fields
    const emptyFields = Array();

    if(!title.value){
        //Title field is empty
        emptyFields.push("Opgavetitel ");
        title.classList.add('error-field');
    } else {
        title.classList.remove('error-field');
    }

    for(let i = 1; i <= amount; i++){
        //Get answer field
        const answerElement = document.querySelector(`#answer${i}`);
        //Get check point field
        const pointsElement = document.querySelector(`#points${i}`);
        //Check answer field is not empty
        if(!answerElement.value){
            //Answer field empty
            emptyFields.push(`Svarmulighed i `);
            answerElement.classList.add('error-field');
        } else {
            answerElement.classList.remove('error-field');
        }
        //Check points field is not empty and is a number
        if(!pointsElement.value || isNaN(pointsElement.value)){
            //Points field empty or NaN
            emptyFields.push(`Point ${i} `);
            pointsElement.classList.add('error-field');
        } else {
            pointsElement.classList.remove('error-field');
        }
    }
    
    //Check if emptyFields array is empty
    const noEmptyFields = (emptyFields && emptyFields.length) ? false : true;
    if(noEmptyFields){
        //Clear sessionStorage
        sessionStorage.clear();
        //If empty, submit create form
        document.querySelector(`#${formID}`).submit();
    } else {
        //Re-enable submit button if form validation failed
        submitBtn.disabled = false;
    }
}



//Add or remove answer- and point fields
const changeFields = () => {
    //Get element containing all input fields
    const divFields = document.querySelector('#inputFields');
    //Get how many answers the user requests
    let answerAmount = document.querySelector("#answerAmount").value;
    //Create arrays for storing input data
    const answers = [],
          points = [];
    
    //Remove all current input fields
    let whileCounter = 1;
    while(divFields.firstElementChild){
        //Push input values into arrays
        answers.push(divFields.firstElementChild.querySelector("#answer"+whileCounter).value);
        points.push(divFields.firstElementChild.querySelector("#points"+whileCounter).value);
        //Delete child element
        divFields.removeChild(divFields.firstElementChild);
        //Increase counter
        whileCounter++;
    }

    
    
    //Get data stored in sessionStorage
    let sessionAnswers = getSessionStorage('answer'),
        sessionPoints = getSessionStorage('point');

    //Get bools deciding whether to update session data or not
    const updateAnswers = compareArrays(answers, sessionAnswers);
    const updatePoints = compareArrays(points, sessionPoints);

    //Update session
    if(updateAnswers === true){
        setSessionStorage(answers, "answer");
        sessionAnswers = getSessionStorage("answer");
    }
    if(updatePoints === true){
        setSessionStorage(points, "point");
        sessionPoints = getSessionStorage("point");
    }

    //Ensure there are not less or more answers than allowed
    if(answerAmount > 9){
        answerAmount = 9
    } else if(answerAmount < 1) {
        answerAmount = 1;
    }

    //Create HTML elements
    for(let i = 1; i <= answerAmount; i++){
        //Create elements
        const divCol = document.createElement("div"),
              divFormGroup = document.createElement("div"),
              inputAnswer = document.createElement("input"),
              inputPoints = document.createElement("input"),
              lblAnswer = document.createElement("label"),
              lblPoints = document.createElement("label");

        //Add attributes to div elements
        divCol.classList.add("col-md-4");
        divFormGroup.classList.add("form-group");

        //Add attributes to inputs
        inputAnswer.type = "text";
        inputAnswer.id = "answer"+i;
        inputAnswer.name = "answer"+i;
        inputAnswer.placeholder = "Svarmulighed "+i;
        inputAnswer.classList.add("form-control");
        inputAnswer.classList.add("ass-input");
        if(sessionAnswers[i-1]){
            inputAnswer.value = sessionAnswers[i-1];
        } else if(typeof originalAnswers !== 'undefined'){
            if(originalAnswers[i-1]){
                inputAnswer.value = originalAnswers[i-1]['answer'];
            }
        }

        inputPoints.type = "text";
        inputPoints.id = "points"+i;
        inputPoints.name = "points"+i;
        inputPoints.placeholder = "Point "+i;
        inputPoints.classList.add("form-control");
        inputPoints.classList.add("ass-input");
        if(sessionPoints[i-1]){
            inputPoints.value = sessionPoints[i-1];
        } else if(typeof originalAnswers !== 'undefined'){
            if(originalAnswers[i-1]){
                inputPoints.value = originalAnswers[i-1]['points'];
            }
        }

        //Add attributes to labels
        lblAnswer.innerHTML = `Svarmulighed ${i}:`;
        lblPoints.innerHTML = `Point ${i}:`;

        //Make babies
        divFormGroup.appendChild(lblAnswer);
        divFormGroup.appendChild(inputAnswer);
        divFormGroup.appendChild(lblPoints);
        divFormGroup.appendChild(inputPoints);
        divCol.appendChild(divFormGroup);

        //Append newly created elements to "root" element
        divFields.appendChild(divCol);
    }
}

//Compare if 2 arrays are 100% the same
const compareArrays = (arr1, arr2) => {
    if(arr1.length !== arr2.length){
        return true;
    } else if(isArrayValuesEqual(arr1, arr2) === false){
        return true;
    }
    //Arrays are 100% equal
    return false;
}


//Compare each item in 2 arrays
const isArrayValuesEqual = (arr1, arr2) => {
    arr1.forEach((item, i) => {
        if(item !== arr2[i]){
            //Values are not equal
            return false;
        }
    });
    //All values are the same
    return true;
}


//Set session values
const setSessionStorage = (arr, key) => {
    //Save each item in the array param in sessionStorage
    arr.forEach((value, i) => sessionStorage.setItem(`${key}${i}`, value));
}


//Get session values
const getSessionStorage = key => {
    //Set variables
    const ret = [];

    //Get all items from sessionStorage
    for(let i = 0; i <= sessionStorage.length; i++){
        if(sessionStorage.getItem(`${key}${i}`) !== null){
            ret.push(sessionStorage.getItem(`${key}${i}`));
        }
    }
    //Return array
    return ret;
}