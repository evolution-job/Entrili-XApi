<?php

namespace Entrili\XApiBundle\Controller;

use Entrili\XApiBundle\Exception\NotFoundException;
use Entrili\XApiBundle\Model\State;
use Entrili\XApiBundle\Repository\StateRepositoryInterface;
use Entrili\XApiBundle\Serializer\StateSerializerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

final class StateGetController
{
    private $repository;
    private $stateSerializer;

    /**
     * StateGetController constructor.
     *
     * @param StateRepositoryInterface $repository
     * @param StateSerializerInterface $stateSerializer
     */
    public function __construct(StateRepositoryInterface $repository, StateSerializerInterface $stateSerializer)
    {
        $this->repository = $repository;
        $this->stateSerializer = $stateSerializer;
    }

    /**
     * @param Request $request
     * @param State $state
     * @return Response
     * @throws NotFoundException
     */
    public function getState(State $state)
    {
        $mappedObject = $this->repository->findState([
            "stateId" => $state->getStateId(),
            "activity" => $state->getActivity()->getId()->getValue(),
            "registration" => $state->getRegistration()
        ]);

        if ($mappedObject instanceof \Entrili\XApiBundle\Repository\Mapping\State) {
            $response = new Response($mappedObject->data, 200, array());
        } else {
            $response = new Response('', 404, array());
        }

        $now = new \DateTime();
        $response->headers->set('X-Experience-API-Consistent-Through', $now->format(\DateTime::ATOM));

        return $response;
    }
}
