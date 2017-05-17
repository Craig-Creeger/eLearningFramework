<?php
//Note: When switching navigation styles, you will usually want to place them in pairs (linear page next to a forwardOnly page) so that it works when navigating in both directions.
$pages = [
    [
        'href'=>'introduction.html',
    ],[
        'href'=>'mobile.html',
    ],[
        'href'=>'eLearning.html',
    ],[
        'href'=>'technical.html',
        'navigation'=>'linear',
    ],[
        'href'=>'question1.html',
        'navigation'=>'forwardOnly',
        'interaction'=>[
            'type'=>'mc',
            'allowedAttempts'=>2,
            'correctFeedback'=>'That’s right! All of the answers are correct.',
            'finalIncorrect'=>'That’s not right. The correct answer is D.',
            'question'=>'When creating a new course using this framework, ...',
            'distractors'=>[
                [
                    'text'=>'the layout and graphic design can be customized.',
                    'sbChecked'=>false,
                    'feedback'=>'Nearly all aspects of the design and layout can be customized, but there is a better answer.',
                ],[
                    'text'=>'the pages and interactions are defined in a single PHP associative array.',
                    'sbChecked'=>false,
                    'feedback'=>'It is true that course pages and all interactions are defined in an array, but there is a better answer.',
                ],[
                    'text'=>'it comes with a default responsive design and works on most browsers.',
                    'sbChecked'=>false,
                    'feedback'=>'That is correct, but there is a better answer.',
                ],[
                    'text'=>'All of the above.',
                    'sbChecked'=>true,
                    'feedback'=>'',
                ],
            ],
        ],
    ],[
        'href'=>'question2.html',
        'interaction'=>[
            'type'=>'ms',
            'allowedAttempts'=>2,
            'correctFeedback'=>'That’s right!',
            'finalIncorrect'=>'Hire Craig. You won’t regret it.',
            'question'=>'Select all the True statements about Craig.',
            'distractors'=>[
                [
                    'text'=>'He REALLY, REALLY wants to work with your team!!',
                    'sbChecked'=>true,
                    'feedback'=>''
                ],[
                    'text'=>'He has a craftsman’s mentality about programming. He will put the effort into any project to ensure that it is done well.',
                    'sbChecked'=>true,
                    'feedback'=>'',
                ],[
                    'text'=>'Brussel sprouts are a tasty treat anytime of day.',
                    'sbChecked'=>false,
                    'feedback'=>'There are very few times when eating Brussel sprouts could be good.',
                ],
            ],
        ],
    ],[
        'href'=>'quizResults.html',
    ]
];