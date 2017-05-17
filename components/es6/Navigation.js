"use strict";

class Navigation {
    constructor(pages, coursePath, {navigation = "linear", passingScore = 0.8, debug = false} = {}) {
        this.pages = pages;
        this.page = null; //a shortcut way to get to the information about the current page.
        this.coursePath = coursePath || 'courses/';
        this.navigation = navigation; //forwardOnly || linear (forward or back one page at a time)
        this.passingScore = passingScore; //Expressed as a percentage. e.g. Enter 0.8 for 80%.
        this.curPage = 0;
        this.prevElem = null;
        this.nextElem = null;
        this.cmaElem = null;
        this.debug = debug;
        
        if (debug) {
            $('#adminCntl').removeClass('hidden');
        }
        
        $(document).ajaxStart(function() {
            $('#loadingIndicator').show();
        }).ajaxStop(function() {
            $('#loadingIndicator').hide();
        });
    }
    
    reload(curPage) {
        //Prepare a page to load. You will need to call fetchPage() to actually load it.
        if (typeof curPage !== 'undefined') {
            if (Number.isInteger(curPage)) {
                this.curPage = curPage;
            }
        }
        this._initPage();
    }
    
    setNext(newPage) {
        //Call with no parameter to use the default next page.
        if (typeof newPage === 'undefined') {
            this.curPage++;
            if (this.curPage >= pages.length) {
                this.curPage = pages.length - 1;
            }
        } else {
            this.curPage = newPage;
        }
        this._initPage();
    }
    
    setPrev(newPage) {
        //Call with no parameter to use the default next page.
        if (typeof newPage === 'undefined') {
            this.curPage--;
            if (this.curPage < 0) {
                this.curPage = 0;
            }
        } else {
            this.curPage = newPage;
        }
        this._initPage();
    }
    
    enable(whichOne) {
        if (whichOne === 'prev') {
            this.setButton(this.prevElem, 'enabled');
        } else if (whichOne === 'next') {
            this.setButton(this.nextElem, 'enabled');
        } else if (whichOne === 'cma') {
            this.setButton(this.cmaElem, 'enabled');
        }
    }
    
    disable(whichOne) {
        if (whichOne === 'prev') {
            this.setButton(this.prevElem, 'disabled');
        } else if (whichOne === 'next') {
            this.setButton(this.nextElem, 'disabled');
        } else if (whichOne === 'cma') {
            this.setButton(this.cmaElem, 'disabled');
        }
    }
    
    setButton(elem, attr) {
        if (attr === 'enabled') {
            $(elem).removeAttr('disabled');
        }
        if (attr === 'disabled') {
            $(elem).attr('disabled', 'disabled');
        }
    }
    
    logAnswer(correct) {
        //Student responses are saved in memory. Once the course is completed
        //the scores can get written to disk by ajax/saveScore.php.
        this.pages[this.curPage].interaction.correct = correct;
    }
    
    getScore() {
        //returns student’s percentage score
        let totalQuestions = 0;
        let nbrCorrect = 0;
        this.pages.forEach((elem) => {
           if (elem.hasOwnProperty('interaction')) {
               totalQuestions++;
               if (elem.interaction.hasOwnProperty('correct')) {
                   if (elem.interaction.correct) {
                       nbrCorrect++;
                   }
               }
           }
        });
        if (totalQuestions > 0) {
            return {
                "totalQuestions": totalQuestions,
                "nbrCorrect": nbrCorrect,
                "score": nbrCorrect / totalQuestions,
                "passingScore": this.passingScore
            };
        } else {
            return {
                "totalQuestions": totalQuestions,
                "nbrCorrect": nbrCorrect,
                "score": 0,
                "passingScore": this.passingScore
            };
        }
    }
    
    _initPage() {
        //A new current page has been set, but not loaded yet.
        this.page = this.pages[this.curPage];
        if (this.page.hasOwnProperty('navigation')) {
            this.navigation = this.page.navigation;
        }
    }
    
    buildPage(interaction) {
        //Generate all the HTML for the page specified by this.curPage.
        //interaction is a (blank) instance of the Interaction class.
        
        //Always clean out the previous interaction (in case the current page doesn’t have one).
        interaction.init();
        return new Promise ((resolve, reject) => {
            let params = {
                filename: encodeURI(this.coursePath + this.page.href)
            };
            $.ajax({
                //request
                type: 'GET',
                url: 'ajax/getPage.php',
                data: params,
                cache: false,
                //response
                dataType: 'html',
                async: true
            }).done((data, textStatus, jqXHR) => {
                //After valid AJAX call (could still have failed)...
                //Either found, or successfully save a new email address
                if (!(data === false || data === 'false')) {
                    let finalHTML = this._pageInjections(data, interaction);
                    resolve(finalHTML);
                } else {
                    reject('Could not find file' + params.filename);
                }
            }).fail(function(jqXHR, textStatus, errorThrown) {
                //The AJAX portion of the call failed.
                reject('getPage.php AJAX call failed.');
            });            
        });
    }
    
    _pageInjections(html, interaction) {
        /*  ES6 does not have public and private methods, so I'm
            using the _prefix convention to hint that it is
            private to this class (even though it really isn't). */
        let prevState;
        let nextState;
        let finalHTML = html;
        
        /*  Take a look at the curPage and determine if an
            interaction and feedback need to be injected into
            the page. */
        if (this.page.hasOwnProperty('interaction')) {
            //Build the interaction; append to bottom of page.
            this.page.interaction.debug = nav.debug;
            interaction.init(this.page.interaction);
            finalHTML += interaction.build();
            finalHTML += interaction.buildFeedback();
        }
        

        /*  This function is called for every page that is built in a course
            so this is where I will also set a disabled attribute on the
            navigation buttons. Setting the state of the navigation buttons
            follows this order:
            1. Use rules specified by "this.navigation".
            2. When an interaction is on the page disable the [Next] button.
            2. Use optional overrides in the course definition file (e.g. course1.php).
            3. Use first and last page rules.
        */
        if (this.navigation === 'linear') {
            prevState = 'enabled';
            nextState = 'enabled';
        }
        if (this.navigation === 'forwardOnly') {
            prevState = 'disabled';
            nextState = 'enabled';
        }
        if (this.page.hasOwnProperty('interaction')) {
            nextState = 'disabled';
        }
        if (this.page.hasOwnProperty("buttons")) {
            if (this.page.buttons.hasOwnProperty("prev")) {
                if (this.page.buttons.prev === 'enabled') {
                    prevState = 'enabled';
                } else {
                    prevState = 'disabled';
                }
            }
            if (this.page.buttons.hasOwnProperty("next")) {
                if (this.page.buttons.next === 'enabled') {
                    nextState = 'enabled';
                } else {
                    nextState = 'disabled';
                }
            }
        }
        if (this.curPage >= (pages.length -1)) { //On last page of course
            nextState = 'disabled';
        }
        if (this.curPage <= 0) { //On first page of course
            prevState = 'disabled';
        }
        
        this.setButton(this.prevElem, prevState);
        this.setButton(this.nextElem, nextState);
        
        return finalHTML;
    }
}
