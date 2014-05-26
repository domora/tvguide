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
            )
            ->addOption(
                'since',
                null,
                InputOption::VALUE_OPTIONAL,
                'Ignore persons who have already been updated in the given interval',
                '-1 day'
            ); 
    }
    
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $em = $this->app['orm.em'];
        $wikipedia = $this->app['wikipedia'];
        $dialog = $this->getHelperSet()->get('dialog');
        $numbers = $input->getOption('numbers');
        $since = new \DateTime($input->getOption('since'));

        $persons = $em->createQuery('SELECT p FROM Domora\TvGuide\Data\Person p WHERE p.lastUpdate is NULL OR p.lastUpdate < :since ORDER BY p.lastUpdate ASC')
            ->setParameter('since', $since)
            ->setMaxResults($numbers)
            ->getResult();
            
        if (sizeof($persons) == 0) {
            $output->writeln('no persons to update');
            return;
        }

        foreach ($persons as $person) {
            try {
                $wikipedia->updatePerson($person);
                $person->setLastUpdate(new \DateTime());
                $output->writeln(sprintf('<info>%s</info> has been updated', $person->getName()));
            } catch(\Exception $e) {
                $output->writeln(sprintf('<error>"%s"</error>, skipping update of %s', $e->getMessage(), $person->getName()));
            }
        }

        $em->flush();
    }
}


