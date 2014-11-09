<?php

namespace Domora\TvGuide\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use DDesrosiers\SilexAnnotations\Annotations as Silex;

use Domora\TvGuide\Data\Channel;
use Domora\TvGuide\Data\Program;
use Domora\TvGuide\Response\Success;

class ChannelController extends AbstractController
{
    const CHANNEL_ENTITY = 'Domora\TvGuide\Data\Channel';
    
    /**
     * @Silex\Route(
     *     @Silex\Request(method="GET", uri="channels")
     * )
     */
    public function getChannelsAction(Request $request)
    {
        $country = $request->get('country');
        $qb = $this->em->createQueryBuilder();
        
        $query = $qb->select('c')
            ->from(self::CHANNEL_ENTITY, 'c');
            
        if ($country) {
            $query->where('c.country = :country')
                ->setParameter('country', $country);
        }
            
        $channels = $query->getQuery()->getResult();
            
        return [$channels, 'details'];
    }
    
    /**
     * @Silex\Route(
     *     @Silex\Request(method="GET", uri="channels/{channel}"),
     *     @Silex\Convert(variable="channel", callback="entity.provider:convertChannel")
     * )
     */
    public function getChannelAction(Channel $channel)
    {
        return [$channel, 'details'];
    }
    
    /**
     * @Silex\Route(
     *     @Silex\Request(method="POST", uri="channels/{channel}/programs"),
     *     @Silex\Convert(variable="channel", callback="entity.provider:convertChannel")
     * )
     *
     * Todo: Handle error cases
     */
    public function postChannelProgramsAction(Request $request, Channel $channel)
    {
        $program = $this->serializer->deserialize($request->getContent(), ProgramController::PROGRAM_ENTITY, 'json');
        $program->setChannel($channel);
        $channel->addProgram($program);
        $program->generateUniqueId();
        
        $eliminateDuplicates = function($person) {
            $found = $this->em
                ->getRepository('Domora\TvGuide\Data\Person')
                ->findOneByName($person->getName());
            
            return $found ?: $person;
        };
        
        // Eliminate credits duplicates
        $credits = $program->getCredits();
        if ($credits) {
            $credits->replacePresenters($eliminateDuplicates);
            $credits->replaceDirectors($eliminateDuplicates);
            $credits->replaceWriters($eliminateDuplicates);
            $credits->replaceActors($eliminateDuplicates);
            $credits->replaceComposers($eliminateDuplicates);
            $credits->replaceGuests($eliminateDuplicates);
        }

        // Import image if necessary
        $data = json_decode($request->getContent(), true);
        if (isset($data['image'])) {
            $program->importImageFromUrl($data['image']);
        }
        
        $this->em->persist($program);
        $this->em->flush();

        return new Success(200, 'PROGRAM_CREATED', $program);
    }
}
