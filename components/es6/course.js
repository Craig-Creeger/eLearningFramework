"use strict";

let interaction = new Interaction();
let nav = new Navigation(pages, coursePath, {debug: debugMode, passingScore: passingScore});
let elemOutput = document.getElementsByTagName('output')[0];

function fetchPage() {
    elemOutput.innerHTML = ''; //clear screen while user waits for page to load via AJAX.
    
    //buildPage() is an ES6 Promise.
    nav.buildPage(interaction).then(data => {
        $(elemOutput).html(data);
        if (interaction.type !== null) {
            enableCMA();
        }
        if (nav.debug) {
            document.querySelector('#adminCntl button').innerHTML = 'Reload ' + nav.page.href;
            let delay = setTimeout(function() {
                //If there are any video timers on the page, this will bypass them.
                nav.enable('next');
                nav.enable('prev');
            },4000);
        }
    }).catch(error => {
        elemOutput.innerHTML = error;
    })
}

//Display the first page in the course.
nav.reload();
fetchPage();

//Wire up the Prev and Next buttons
$('#prev').on('click',function(e) {
    nav.setPrev();
    fetchPage();
});
$('#next').on('click',function(e) {
    nav.setNext();
    fetchPage();
});
//Register the Prev and Next buttons with the Navigation class.
//This allows Navigation to set the disabled attribute on the elements.
nav.prevElem = document.getElementById('prev');
nav.nextElem = document.getElementById('next');

//Wire up the CMA button
function enableCMA() {
    $('#cma').one('click', handleCMA).removeAttr('disabled');
    nav.cmaElem = document.getElementById('cma');
}

/* When the Check My Answer (CMA) button has been clicked, judge the student’s response and display
   feedback if configured to do so. */
function handleCMA() {
    nav.disable('cma');
    
    //Judge the student’s guess.
    let guesses = new Array();
    $('.distInput input').each(function(idx, elem) {
        guesses.push($(elem).prop('checked'));
    });
    interaction.judge(guesses);
    
    //Display appropriate feedback
    let fdbk = interaction.getFeedback();
    if (fdbk.length > 0) {
        $('#feedback').html(fdbk).removeClass('invisible');
    }
    
    //Set buttons
    if (interaction.correct || interaction.finalGuess) {
        //Log the student’s response.
        lightUpIndicators(interaction.correctAnswers());
        nav.logAnswer(interaction.correct);
        nav.enable('next');
    } else {
        enableCMA();
    }    
}

function lightUpIndicators(correctAnswers) {
    //Show the learner which distractors were correct.
    $('td.distFdbk').each(function(idx, elem) {
        let foundIt = false;
        correctAnswers.forEach((elem) => {
            if (elem === idx) {
                foundIt = true;
            }
        });
        if (foundIt) {
            $(elem).removeClass('invisible');
        }
    });
}

function writeScoreToLMS() {
    //Once the quiz is finished, write the results to the database.
    let score = nav.getScore();
    let params = {
        pid: PID,
        learnerId: learnerId,
        courseId: courseId,
        score: score.score,
        pass: (score.score >= score.passingScore)
    };
    $.ajax({
        //request
        type: 'POST',
        url: 'ajax/saveScore.php',
        data: params,
        cache: false,
        //response
        dataType: 'json',
        async: true
    }).done(function(data, textStatus, jqXHR) {
        //After valid AJAX call (could still have failed)...
        if (data.response === 'OK') {
            //nop
        } else {
            $('output:first').append('<p class="callout error">There was a problem saving your information. ' + data.response + '. ' + MSGCONTACT + '</p>');
        }
    }).fail(function(jqXHR, textStatus, errorThrown) {
        //The AJAX portion of the call failed.
        $('output:first').append('<p class="callout error">AJAX call failed when saving your information.' + MSGCONTACT + '</p>');
    });
}