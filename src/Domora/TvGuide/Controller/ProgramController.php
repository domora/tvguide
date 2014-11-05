<?php

namespace Domora\TvGuide\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

use Domora\TvGuide\Data\Program;

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
            return $this->serializer->error('a list of channel must be provided in "channels" argument', 400);
        }

        // Extracts the time period to look for
        $start = new \DateTime($request->get('start'));
        $end = new \DateTime($request->get('end'));

        if (!$start) {
            return $this->serializer->error('unvalid datetime format in "start" argument', 400);
        }

        if (!$end) {
            return $this->serializer->error('unvalid datetime format in "end" argument', 400);
        }

        $period = $end->getTimestamp() - $start->getTimestamp();
        if ($period < 0 || $period > 24 * 3600) {
            return $this->serializer->error('the period between "start" and "end" must be between 0 and 24 hours', 400);
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
        
        return $this->serializer->response($schedule, $mode);
    }
    
    public function getProgramAction(Program $program)
    {
        return $this->serializer->response($program, ['details']);
    }
}
