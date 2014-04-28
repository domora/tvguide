<?php

function with($object) { return $object; }

$programs = [
    1927810 => [
        'name' => 'Programme test TF1',
        'image' => 'http://domora.com/img/logo4.png',
        'description' => 'Ibi victu recreati et quiete, postquam abierat timor, vicos opulentos adorti equestrium '.
                         'adventu cohortium, quae casu propinquabant, nec resistere planitie porrecta conati.',
        'start' => with(new \DateTime())->getTimestamp(),
        'end' => with(new \DateTime('+25 minutes'))->getTimestamp(),
        'type' => 'magazine',
    ],
    2983721 => [
        'name' => 'Programme test France 2',
        'image' => 'http://domora.com/img/logo4.png',
        'description' => 'Ibi victu recreati et quiete, postquam abierat timor, vicos opulentos adorti equestrium '.
                         'adventu cohortium, quae casu propinquabant, nec resistere planitie porrecta conati.',
        'start' => with(new \DateTime('-10 minutes'))->getTimestamp(),
        'end' => with(new \DateTime('+30 minutes'))->getTimestamp(),
        'type' => 'movie',
    ],
    1928173 => [
        'name' => 'Questions pour un champion',
        'image' => 'http://www.tv5.org/cms/userdata/c_bloc/102/102714/102714_vignette_questions-pour-un-champion.jpg',
        'description' => 'Ibi victu recreati et quiete, postquam abierat timor, vicos opulentos adorti equestrium '.
                         'adventu cohortium, quae casu propinquabant, nec resistere planitie porrecta conati.',
        'start' => with(new \DateTime('-5 minutes'))->getTimestamp(),
        'end' => with(new \DateTime('+30 minutes'))->getTimestamp(),
        'type' => 'game show',
    ],
    198727 => [
        'name' => 'Programme test m6',
        'image' => 'http://domora.com/img/logo4.png',
        'description' => 'Ibi victu recreati et quiete, postquam abierat timor, vicos opulentos adorti equestrium '.
                         'adventu cohortium, quae casu propinquabant, nec resistere planitie porrecta conati.',
        'start' => with(new \DateTime('-5 minutes'))->getTimestamp(),
        'end' => with(new \DateTime('+10 minutes'))->getTimestamp(),
        'type' => 'movie',
    ],
    1987222 => [
        'name' => 'Programme test bis',
        'image' => 'http://domora.com/img/logo4.png',
        'description' => 'Ibi victu recreati et quiete, postquam abierat timor, vicos opulentos adorti equestrium '.
                         'adventu cohortium, quae casu propinquabant, nec resistere planitie porrecta conati.',
        'start' => with(new \DateTime('+15 minutes'))->getTimestamp(),
        'end' => with(new \DateTime('+30 minutes'))->getTimestamp(),
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
                    'image' => $programs[1927810]['image'],
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
                    'image' => $programs[2983721]['image'],
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
                    'image' => $programs[1928173]['image'],
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
                    'image' => $programs[198727]['image'],
                    'start' => $programs[198727]['start'],
                    'end' => $programs[198727]['end']
                ],
                [
                    'id' => 1987222,
                    'name' => $programs[1987222]['name'],
                    'image' => $programs[1987222]['image'],
                    'start' => $programs[1987222]['start'],
                    'end' => $programs[1987222]['end']
                ]
            ]
        ],            
    ]
];
