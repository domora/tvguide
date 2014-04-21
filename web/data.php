<?php

$programs = [
    1927810 => [
        'name' => 'Programme test TF1',
        'description' => 'Ibi victu recreati et quiete, postquam abierat timor, vicos opulentos adorti equestrium '.
                         'adventu cohortium, quae casu propinquabant, nec resistere planitie porrecta conati.',
        'start' => new \DateTime(),
        'end' => new \DateTime('+25 minutes'),
        'type' => 'magazine',
    ],
    2983721 => [
        'name' => 'Programme test France 2',
        'description' => 'Ibi victu recreati et quiete, postquam abierat timor, vicos opulentos adorti equestrium '.
                         'adventu cohortium, quae casu propinquabant, nec resistere planitie porrecta conati.',
        'start' => new \DateTime('-10 minutes'),
        'end' => new \DateTime('+30 minutes'),
        'type' => 'movie',
    ],
    1928173 => [
        'name' => 'Questions pour un champion',
        'description' => 'Ibi victu recreati et quiete, postquam abierat timor, vicos opulentos adorti equestrium '.
                         'adventu cohortium, quae casu propinquabant, nec resistere planitie porrecta conati.',
        'start' => new \DateTime('-5 minutes'),
        'end' => new \DateTime('+30 minutes'),
        'type' => 'game show',
    ],
    198727 => [
        'name' => 'Programme test m6',
        'description' => 'Ibi victu recreati et quiete, postquam abierat timor, vicos opulentos adorti equestrium '.
                         'adventu cohortium, quae casu propinquabant, nec resistere planitie porrecta conati.',
        'start' => new \DateTime('-5 minutes'),
        'end' => new \DateTime('+10 minutes'),
        'type' => 'movie',
    ],
    1987222 => [
        'name' => 'Programme test bis',
        'description' => 'Ibi victu recreati et quiete, postquam abierat timor, vicos opulentos adorti equestrium '.
                         'adventu cohortium, quae casu propinquabant, nec resistere planitie porrecta conati.',
        'start' => new \DateTime('+15 minutes'),
        'end' => new \DateTime('+30 minutes'),
        'type' => 'talk',
    ],
];

$guide = [
    'channels' => [
        [
            'id' => 1,
            'name' => 'TF1',
            'programs' => [
                [
                    'id' => 1927810,
                    'name' => $programs[1927810]['name'],
                    'start' => $programs[1927810]['start'],
                    'end' => $programs[1927810]['end']
                ]
            ]
        ],
        [
            'id' => 2,
            'name' => 'France 2',
            'programs' => [
                [
                    'id' => 2983721,
                    'name' => $programs[2983721]['name'],
                    'start' => $programs[2983721]['start'],
                    'end' => $programs[2983721]['end']
                ]
            ]
        ],
        [
            'id' => 3,
            'name' => 'France 3',
            'programs' => [
                [
                    'id' => 1928173,
                    'name' => $programs[1928173]['name'],
                    'start' => $programs[1928173]['start'],
                    'end' => $programs[1928173]['end']
                ]
            ]
        ], 
        [
            'id' => 6,
            'name' => 'M6',
            'programs' => [
                [
                    'id' => 198727,
                    'name' => $programs[198727]['name'],
                    'start' => $programs[198727]['start'],
                    'end' => $programs[198727]['end']
                ],
                [
                    'id' => 1987222,
                    'name' => $programs[1987222]['name'],
                    'start' => $programs[1987222]['start'],
                    'end' => $programs[1987222]['end']
                ]
            ]
        ],            
    ]
];
