<?php

namespace Domora\TvGuide\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Doctrine\Common\Collections\Criteria;

use Domora\TvGuide\Data\Program;
use Domora\TvGuide\Data\Channel;
use Domora\TvGuide\Data\Schedule;
use Domora\TvGuide\Response\Success;
use Domora\TvGuide\Response\Error;

class ProgramController extends AbstractController
{
    const PROGRAM_ENTITY = 'Domora\TvGuide\Data\Program';
    
    public function getProgramsAction(Request $request)
    {
        // Extracts channels IDs from the request
        $channels = $request->get('channels');

        if (!is_array($channels) && !empty($channels)) {
            $channels = preg_split('/[:,;]+/', $channels);
        }

        if (count($channels) == 0) {
            throw new Error(400, 'MISSING_CHANNELS_ARGUMENT',
                'A list of channel must be provided in "channels" argument');
        }
        
        if (!$request->get('start')) {
            throw new Error(400, 'MISSING_START_ARGUMENT',
                'A start date must be provided in "start" argument');
        }
        
        if (!$request->get('end')) {
            throw new Error(400, 'MISSING_END_ARGUMENT',
                'An end date must be provided in "end" argument');
        }

        // Extracts the time period to look for
        try {
            $start = new \DateTime($request->get('start'));
        } catch(\Exception $e) {
            throw new Error(400, 'UNVALID_START_ARGUMENT',
                'Unvalid datetime format in "start" argument');
        }
        
        try {
            $end = new \DateTime($request->get('end'));
        } catch(\Exception $e) {
            throw new Error(400, 'UNVALID_END_ARGUMENT',
                'Unvalid datetime format in "end" argument');
        }

        $period = $end->getTimestamp() - $start->getTimestamp();
        if ($period < 0 || $period > 24 * 3600) {
            throw new Error(400, 'UNVALID_PERIOD',
                'The period between "start" and "end" must be between 0 and 24 hours');
        }

        // Finds all the requested channels
        $qb = $this->em->createQueryBuilder();
        $channels = $qb->select('c')
            ->from('Domora\TvGuide\Data\Channel', 'c')
            ->where($qb->expr()->in('c.id', $channels))
            ->getQuery()
            ->getResult();

        // Sets the programs filter
        $criteria = Criteria::create()
            ->where(Criteria::expr()->andX(
                Criteria::expr()->gte('start', $start),
                Criteria::expr()->lte('start', $end)
            ))
            ->orWhere(Criteria::expr()->andX(
                Criteria::expr()->lte('stop', $end),
                Criteria::expr()->gte('stop', $start)
            ))
            ->orWhere(Criteria::expr()->andX(
                Criteria::expr()->lte('start', $start),
                Criteria::expr()->gte('stop', $end)
            ));

        Channel::setProgramsFilter($criteria);
        
        $schedule = new Schedule();
        $schedule->setChannels($channels);
        $mode = $request->get('xmltv') ? ['xmltv'] : ['schedule'];
        
        return [$schedule, $mode];
    }
    
    public function getProgramAction(Program $program)
    {
        return [$program, 'details'];
    }
    
    public function deleteProgramAction(Program $program)
    {
        $this->em->remove($program);
        $this->em->flush();
        
        return $this->serializer->serialize(new Success(200, 'PROGRAM_REMOVED'));
    }
}
