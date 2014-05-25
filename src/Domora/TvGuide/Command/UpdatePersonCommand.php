<?php

namespace Domora\TvGuide\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Goutte\Client;
use Silex\Application;

class UpdatePersonCommand extends Command
{
    public function __construct(Application $app)
    {
        parent::__construct();
        $this->app = $app;
    }
    
    protected function configure()
    {
        $this->setName('tvscraper:persons:update')
            ->addOption(
                'numbers',
                null,
                InputOption::VALUE_REQUIRED,
                'Numbers of persons to update',
                1
            );
    }
    
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $em = $this->app['orm.em'];
        $wikipedia = $this->app['wikipedia'];
        $dialog = $this->getHelperSet()->get('dialog');
        $numbers = $input->getOption('numbers');

        $persons = $em->createQuery('SELECT p FROM Domora\TvGuide\Data\Person p ORDER BY p.lastUpdate ASC')
            ->setMaxResults($numbers)
            ->getResult();

        foreach ($persons as $person) {
            $wikipedia->updatePerson($person);
            $person->setLastUpdate(new \DateTime());
        }

        $em->flush();
    }
}


