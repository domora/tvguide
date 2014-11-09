<?php

namespace Domora\Tests;

use Doctrine\Common\Persistence\ObjectManager;
use Doctrine\Common\DataFixtures\AbstractFixture;

use Domora\TvGuide\Data\Channel;
use Domora\TvGuide\Data\Program;
use Domora\TvGuide\Data\Person;

class DataFixtures extends AbstractFixture
{
    protected $manager;
    protected $persons = [];
    
    public function load(ObjectManager $manager)
    {
        $this->manager = $manager;
        $this->generatePersons();
        $this->generateChannels();
        $manager->flush();
    }
    
    protected function generateChannels()
    {
        $channelNames = [
            'fr-ch1' => 'Channel 1 FR',
            'fr-ch2' => 'Channel 2 FR',
            'de-ch1' => 'Channel 1 DE',
        ];
        
        $channels = [];
        $programs = [];

        foreach ($channelNames as $id => $name) {
            $channel = new Channel();
            $channel->setId($id);
            $channel->setName($name);
            $channel->setCountry(substr($id, 0, 2));
            $this->manager->persist($channel);
            $this->generatePrograms($channel, 50);
        }
    }
    
    protected function generatePrograms(Channel $channel, $number)
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
            $program->setTitle(ucfirst(implode(' ', array_slice($words, 0, rand(1, 5)))));
            $program->setDescription($description);
            $program->setStart(clone $date);
            $date->modify(sprintf('+%d minutes', rand(5, 120)));
            $program->setStop(clone $date);
            $date->modify('+3 minutes');
            
            $persons = array_rand($this->persons, rand(2, 5));

            foreach ($persons as $index) {
                $program->addActor($this->persons[$index]);
            }
            
            $channel->addProgram($program);
            $this->manager->persist($program);
        }
    }
    
    protected function generatePersons()
    {
        for ($i = 0; $i < 10; $i++) {
            $person = new Person();
            $person->setWikipediaId(0);
            $person->setName("Person $i");
            $person->setDescription("Summum a vobis bonum voluptas dicitur.", "fr");
            $this->persons[] = $person;
            $this->manager->persist($person);
        }
    }
}