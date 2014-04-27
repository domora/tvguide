<?php

namespace Domora;

use Domora\TvGuide\Data\DataManager;
use Domora\TvGuide\Data\Program;
use Domora\TvGuide\Data\Channel;

class DataFixtures
{
    
    public function __construct($em)
    {
        $this->em = $em;
    }
    
    public function load()
    {
        //$this->em->clearDatabase();
        
        $channelNames = ['TF1', 'France 2', 'France 3', 'Canal+', 'France 5', 'M6'];
        $channels = [];
        $programs = [];

        foreach ($channelNames as $i => $name) {
            $channel = new Channel();
            $channel->setName($name);
            $this->em->persist($channel);
            $this->generatePrograms($channel, 50);
        }

        $this->em->flush();
    }

    private function generatePrograms(Channel $channel, $number)
    {
        $dummyDescriptions = [
            "Lorem ipsum dolor sit amet, consectetur adipiscing elit. Traditur, inquit, ".
            "ab Epicuro ratio neglegendi doloris. Quantum Aristoxeni ingenium consumptum".
            "videmus in musicis? Neminem videbis ita laudatum, ut artifex callidus comparandarum".
            " voluptatum diceretur. Bonum liberi: misera orbitas. Sed erat aequius ".
            "Triarium aliquid de dissensione nostra iudicare. Maximus dolor, inquit, brevis est.",

            "Summum a vobis bonum voluptas dicitur. Quod ea non occurrentia fingunt, vincunt ".
            "Aristonem; Rhetorice igitur, inquam, nos mavis quam dialectice disputare? Ut ".
            "proverbia non nulla veriora sint quam vestra dogmata. ",

            "Illa videamus, quae a te de amicitia dicta sunt. Quid enim me prohiberet ".
            "Epicureum esse, si probarem, quae ille diceret? ",

            "Respondent extrema primis, media utrisque, omnia omnibus. Ad quorum et ".
            "cognitionem et usum iam corroborati natura ipsa praeeunte deducimur. ".
            "Mene ergo et Triarium dignos existimas, apud quos turpiter loquare? ".
            "Huius ego nunc auctoritatem sequens idem faciam. Ita fit cum gravior, ".
            "tum etiam splendidior oratio. Et quod est munus, quod opus sapientiae? ".
            "Duo enim genera quae erant, fecit tria. Nondum autem explanatum satis, ".
            "erat, quid maxime natura vellet. Equidem e Cn. Ecce aliud simile dissimile. ",

            "Inde igitur, inquit, ordiendum est. Si quidem, inquit, tollerem, sed relinquo. ".
            "Proclivi currit oratio. Venit ad extremum; Igitur neque stultorum quisquam beatus".
            " neque sapientium non beatus. Erat enim res aperta. "
        ];
        
        $date = new \DateTime(sprintf('-%d minutes', rand(1, 20)));
        
        for ($i = 0; $i < $number; $i++) {
            $description = $dummyDescriptions[array_rand($dummyDescriptions)];
            $words = explode(' ', preg_replace('/[,\.;\?]/', '', $description));
            shuffle($words);
            
            $program = new Program();
            $program->setChannel($channel);
            $program->setName(ucfirst(implode(' ', array_slice($words, 0, rand(1, 5)))));
            $program->setDescription($description);
            $program->setStart(clone $date);
            $date->modify(sprintf('+%d minutes', rand(5, 120)));
            $program->setEnd(clone $date);
            $date->modify('+3 minutes');

            $channel->addProgram($program);
            $this->em->persist($program);
        }
    }
}
