"use strict";

//When both the email address and fullName field have data, show the Login/Begin button
$('#email').on('keypress change', function () {
    if (String(this.value).length && $('#fullName').val().length) {
        $('#begin').css('visibility', 'visible');
    } else {
        $('#begin').css('visibility', 'hidden');
    }
});
$('#fullName').on('keypress change', function () {
    if (String(this.value).length && $('#email').val().length) {
        $('#begin').css('visibility', 'visible');
    } else {
        $('#begin').css('visibility', 'hidden');
    }
});

/* This system uses "self-registration", which means anyone with access to this URL
   is allowed to take the course. The email address and promotionId is a compound 
   unique key and that is how the learner is looked-up in the database. If the 
   learner already exists, then pull their list of courses. If they are new, then 
   assign courses to them. */
$('#begin').on('click', function (e) {
    e.preventDefault();
    $('#begin').css('visibility', 'hidden');

    let params = {
        pid: PID,
        email: $('#email').val(),
        fullName: $('#fullName').val()
    };
    $.ajax({
        //request
        type: 'POST',
        url: 'ajax/postLearner.php',
        data: params,
        cache: false,
        //response
        dataType: 'json',
        async: true
    }).done(function (data, textStatus, jqXHR) {
        //After valid AJAX call (could still have failed)...
        if (data) {
            //Either found, or successfully save a new email address
            if (data.learnerId) {
                location.href = courseLauncher;
            } else {
                $('output:first').html('Found data, but no learnerId. ' + MSGCONTACT).addClass('error');
            }
        } else {
            $('output:first').html('Unable to retrieve from, or save to, the database. ' + MSGCONTACT).addClass('error');
        }
    }).fail(function (jqXHR, textStatus, errorThrown) {
        //The AJAX portion of the call failed.
        $('output:first').html('AJAX call failed from index. ' + MSGCONTACT).addClass('error');
    });
});