//Works as a sort of global counter for the percentage calculation
count = 0;
//Set colors
colorArray = [
    'rgb(255, 0, 0)',
    'rgb(0, 255, 0)',
    'rgb(0, 0, 255)',
    'rgb(127, 127, 127)',
    'rgb(127, 127, 255)',
    'rgb(255, 127, 0)',
    'rgb(255, 255, 0)',
    'rgb(0, 255, 255)',
    'rgb(255, 0, 255)'
];

for(var index = 0; index < eventAss.length; index++){
    //Set data as amount of times each answer was picked
    var data = [];
    //Set answers
    var answers = [];
    var usedColors = [];
    //Variable to count each time a team has picked an answer
    var answerCount = 0;
    for(var o = 0; o < eventAns[index].length; o++){
        //Store all the specific assignments answers in an array
        answers.push(eventAns[index][o]['answer']);
        //Get all needed colors, since not all assignments has 9 answers
        usedColors.push(colorArray[o]);
        for(var u = 0; u < teamAns.length; u++){
            if(teamAns[u]['ans_id'] == eventAns[index][o]['id']){
                answerCount++;
            }
        }
        data.push(answerCount);
        answerCount = 0;
    }
    //Calls function, then adds 1 to count
    count = calc_percentage(data, count);

    //Get context
    var ctx = document.getElementById('piechart'+eventAss[index]['ass_id']).getContext('2d');
    //Instantiate chart
    var chart = new Chart(ctx, {
        //Chart type
        type: 'pie',
        
        //Data for dataset
        data: {
            //List all answers
            labels: answers,
            datasets: [{
                //Show the assignment title
                label: "This is a label. I don't quite understand why it's here.",
                //Set colors for diagram
                backgroundColor: usedColors,
                borderColor: 'rgb(0, 0, 0)',
                //Display pick percentage for each answer
                data: data
            }]
        },
        
        //Configure options
        options: {}
    });
}

function calc_percentage(answers, count){
    totalAnswerCount = 0;
    //Get total amount of times the assignment was answered
    for(i = 0; i < answers.length; i++){
        totalAnswerCount += answers[i];
    }

    for(i = 0; i < answers.length; i++){
        //Calculate how often each answer was picked in percent
        percent = Math.round(answers[i] / totalAnswerCount * 100);
        //Write percentage into dedicated <dd> tag on the page
        if(isNaN(percent)){
            percent = 0;
        }
            document.getElementById("dd"+eventAns[count][i]['id']).innerHTML = percent+"%";
    }
    //Count goes up by 1 each time this function is called
    count++;
    return count;
}