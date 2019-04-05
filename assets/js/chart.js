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
    //Set answers
    //Set data as amount of times each answer was picked
    var data = [];//[5, 10, 15, 20, 25, 30, 35, 40, 45];
    var answers = [];
    var usedColors = [];
    var answerCount = 0;
    for(var o = 0; o < eventAns[index].length; o++){
        //Store all this specific assignments answers in an array
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
                label: 'Første Opgave',
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