<?php
//Note: When switching navigation styles, you will usually want to place them in pairs (linear page next to a forwardOnly page) so that it works when navigating in both directions.
$pages = [
    [
        'href'=>'lesson1.html',
    ],[
        'href'=>'quizOverview.html',
        'navigation'=>'linear',
    ],[
        'href'=>'question1.html',
        'navigation'=>'forwardOnly',
        'interaction'=>[
            'type'=>'ms',
            'allowedAttempts'=>2,
            'correctFeedback'=>'That’s right; cats and dogs make excellent house pets.',
            'finalIncorrect'=>'No. cats and dogs make excellent house pets.',
            'question'=>'Which animal(s) make good house pet(s)?',
            'distractors'=>[
                [
                    'text'=>'Cats',
                    'sbChecked'=>true,
                    'feedback'=>'Cats make excellent house pets.',
                ],[
                    'text'=>'Dogs',
                    'sbChecked'=>true,
                    'feedback'=>'Dogs are acceptable house pets in a pinch.',
                ],[
                    'text'=>'Horses',
                    'sbChecked'=>false,
                    'feedback'=>'Not even close. Horses are terrible beasts and don’t belong anywhere, especially in a house.',
                ],[
                    'text'=>'Ducks',
                    'sbChecked'=>false,
                    'feedback'=>'Ducks are OK in hill jack country, but not in most parts of the world.',
                ],
            ],
        ],
    ],[
        'href'=>'question2.html',
        'interaction'=>[
            'type'=>'mc',
            'allowedAttempts'=>2,
            'correctFeedback'=>'That’s right; most Porters are made with Ale yeast.',
            'finalIncorrect'=>'No; most Porters are made with Ale yeast.',
            'question'=>'Porters are typically made with what type of yeast?',
            'distractors'=>[
                [
                    'text'=>'Ale',
                    'sbChecked'=>true,
                ],[
                    'text'=>'Lager',
                    'sbChecked'=>false,
                    'feedback'=>'No, however lager is a type of yeast.',
                ],[
                    'text'=>'Hybrid',
                    'sbChecked'=>false,
                    'feedback'=>'No. There is no such thing as a hybrid yeast.',
                ],
            ],
        ],    ],[
        'href'=>'question3.html',
        'interaction'=>[
            'type'=>'mc',
            'allowedAttempts'=>2, //This will programatically changed to 1.
            'correctFeedback'=>'Of course. Good job.',
            'finalIncorrect'=>'Are you fucking kidding me? No way.',
            'question'=>'The best Jello-O is served how?',
            'distractors'=>[
                [
                    'text'=>'Cold, translucent, and plain.',
                    'sbChecked'=>true,
                ],[
                    'text'=>'In a salad with marshmallows, sour cream, and pineapple chunks.',
                    'sbChecked'=>false,
                ],
            ],
        ],
    ],[
        'href'=>'quizResults.html',
        'navigation'=>'forwardOnly',
    ],[
        'href'=>'lesson2.html',
        'navigation'=>'linear',
    ]
];