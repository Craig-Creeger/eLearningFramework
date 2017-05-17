"use strict";

class Interaction {

    init(iObj) {
        if (typeof iObj === 'undefined') {
            //Blank out this interaction
            this.type = null;
            this.allowedAttempts = null;
            this.correctFeedback = null;
            this.finalIncorrect = null;
            this.question = null;            
        } else {
            this.debug = iObj.debug; //Set by Navigation._pageInjections()
            this.type = iObj.type;
            this.allowedAttempts = iObj.allowedAttempts;
            this.correctFeedback = iObj.correctFeedback;
            this.finalIncorrect = iObj.finalIncorrect;
            this.question = iObj.question;
            
            if (iObj.hasOwnProperty('showCorrectIndicator')) {
                this.showCorrectIndicator = iObj.showCorrectIndicator;
            } else {
                this.showCorrectIndicator = true; //default
            }

            if (iObj.hasOwnProperty('magicOrdering')) {
                this.magicOrdering = iObj.magicOrdering;
            } else {
                this.magicOrdering = true; //default. See _formatQuestion() for usage.
            }
            
            if (iObj.hasOwnProperty('distractors')) {
                this.answers = new Array(); 
                this.distractors = iObj.distractors;
                this.distractors.forEach((element, indx) => {
                    this.answers.push(element.sbChecked);
                });
                if (this.type === 'mc' && this.distractors.length < 3) {
                    //Boolean (true/false, yes/no) questions only get one chance
                    this.allowedAttempts = 1;
                }
            }
        }
        this.attempt = 0;
        this.finalGuess = false;
        this.correct = false; //stores result for the student’s last guess.
        this.guesses = null;  //stores student’s guesses.
    }

    build() {
        //Creates the HTML for the complete interaction, including feedback box.
        let interactHTML = '<p class="question">' + this._formatQuestion(this.question) + '</p>';
        let marks = {};
        
        /* BUILDING MULTIPLE CHOICE RADIO BUTTONS */
        if (this.type === 'mc') {
            //Create radio buttons
            interactHTML += '<p class="instructions">Choose the best answer and click the Check My Answer button.</p>';
            interactHTML += '<table class="layoutTable distractorTable">';
            //Iterate over the distractors.
            this.distractors.forEach((element, indx) => {
                marks = this._setCorrectMarks(element);
                interactHTML += `
<tr>
    <td class="distFdbk invisible"><img src="img/greenCheckmark.svg" width=25 height=25></td>
    <td class="distInput"><input type="radio" name="mcRadio" id="mcRadio${indx + 1}" ${marks.checked}></td>
    <td class="distOrdinal">${marks.markCorrect}${String.fromCharCode(indx + 65)}.${marks.markEnd}</td>
    <td class="distDistractor"><label for="mcRadio${indx + 1}">${element.text}</label></td>
</tr>`;
            });
            interactHTML += '</table>';
            
            
        /* BUILDING MULTIPLE SELECT CHECKBOXES */
        } else if (this.type === 'ms') {
            //Create checkboxes
            interactHTML += '<p class="instructions">Select all that apply and click the [Check My Answer] button when done.</p>';
            interactHTML += '<table class="layoutTable distractorTable">';
            //Iterate over the distractors.
            this.distractors.forEach((element, indx) => {
                marks = this._setCorrectMarks(element);
                interactHTML += `
<tr>
    <td class="distFdbk invisible"><img src="img/greenCheckmark.svg" width=25 height=25></td>
    <td class="distInput"><input type="checkbox" name="msCheckbox[]" id="msCheckbox${indx + 1}" ${marks.checked}></td>
    <td class="distOrdinal">${marks.markCorrect}${String.fromCharCode(indx + 65)}.${marks.markEnd}</td>
    <td class="distDistractor"><label for="msCheckbox${indx + 1}">${element.text}</label></td>
</tr>`;
            });
            interactHTML += '</table>';
        }
        interactHTML += '<p><button id="cma">Check My Answer</button></p>\n';
        return interactHTML;
    }
    
    buildFeedback() {
        return '<div id="feedback" class="invisible"></div>';
    }
    
    judge(guesses) {
        //Compares the student’s guess with the answers. Note: guesses and answers are arrays filled with only true and false values.
        this.correct = true;
        this.guesses = guesses; //save the guesses for when getFeedback() is called.
        this.attempt++;
        this.finalGuess = this.attempt >= this.allowedAttempts;
        this.answers.forEach((element, idx) => {
            if (element !== guesses[idx]) {
                this.correct = false;
            }
        });
        return this.correct;
    }
    
    getFeedback() {
        let fdbk = '';
        if (this.correct) {
            return this.correctFeedback;
        } else if (this.finalGuess) {
            return this.finalIncorrect;
        } else {
            //return distractor-level feedbacks
            this.guesses.forEach((element, idx) => {
               if (element && this.distractors[idx].hasOwnProperty('feedback')) {
                   fdbk += this.distractors[idx].feedback + ' ';
               } 
            });
            fdbk += 'Try again.';
            return fdbk;
        }
    }
    
    correctAnswers() {
        //Returns an array of distractors that should be checked. Zero-based numbers.
        let ca = new Array();
        this.answers.forEach((element, idx) => {
            if (element) {
                ca.push(idx);
            }
        });
        return ca;
    }
    
    _setCorrectMarks(element) {
        //When in Debug mode, developers and reviews will get a visual indicator showing
        //which of the distractors should be checked.
        if (this.debug) {
            if (element.sbChecked) {
                return {
                    'markCorrect': '<mark>',
                    'markEnd': '</mark>',
                    'checked': 'checked'};
            } else {
                return {
                    'markCorrect': '',
                    'markEnd': '',
                    'checked': ''};
            }
        } else {
            return {
                    'markCorrect': '',
                    'markEnd': '',
                    'checked': ''};
        }
    }
    
    _formatQuestion(question) {
        if (this.magicOrdering) {
            /*  
            Look for a number or alpha prefix. If it exists, convert to the appropriate list item.

            For example, if the question text passed in is:
            d. What is a good pet?

            Then it should get converted to:
            <ol style="list-style-type:lower-alpha;" start=4><li>What is a good pet?</li></ol>
            */
            let ordinal = '';
            let listType = '';
            let sequence = 0;
            let re = /^[a-zA-Z0-9]+\.\s+/g;
            let prefix = re.exec(question);
            if (prefix !== null) {
                ordinal = /[a-zA-Z0-9]+/.exec(prefix[0]);
                if (ordinal !== null) {
                    ordinal = ordinal[0];
                    if (/\d/.test(ordinal)) {
                        listType = 'decimal';
                    } else if (/[a-z]/.test(ordinal)) {
                        listType = 'lower-alpha';
                    } else if (/[A-Z]/.test(ordinal)) {
                        listType = 'upper-alpha';
                    } else {
                        listType = 'upper-roman'; //This should never occur.
                    }
                    if (Number.isInteger(parseInt(ordinal))) {
                        sequence = ordinal;
                    } else {
                        //Apparently it is alphabetic. This will only work for one character; so, "d. " works but "af. " does not.
                        if (listType === 'lower-alpha') {
                            sequence = String(ordinal).charCodeAt(0) - 97 + 1;
                        } else {
                            sequence = String(ordinal).charCodeAt(0) - 65 + 1;
                        }
                    }
                    return '<ol style="list-style-type:' + listType + ';" start=' + sequence + '><li>' + question.substr(re.lastIndex) + '</li></ol>';
                } else {
                    //error condition
                    return question;
                }
            } else {
                //Couldn't find anything that looked like an ordering prefix so just 
                //return the original string.
                return question;
            }
        } else {
            return question;
        }
    }
}