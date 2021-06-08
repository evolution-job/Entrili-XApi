<?php

namespace Entrili\XApiBundle\Controller;

use DateTime;
use Entrili\XApiBundle\Model\State;
use Entrili\XApiBundle\Repository\StateRepository;
use Symfony\Component\HttpFoundation\Response;

final class StatePutController
{
    private $repository;

    /**
     * StatePutController constructor.
     *
     * @param StateRepository $repository
     */
    public function __construct(StateRepository $repository)
    {
        $this->repository = $repository;
    }

    /**
     * @param State $state
     * @return Response
     */
    public function putState(State $state): Response
    {
        $state->setRegistration($state->getRegistration());

        $existState = $this->repository->findState([
            "stateId"      => $state->getStateId(),
            "activity"     => $state->getActivity()->getId()->getValue(),
            "registration" => $state->getRegistration()
        ]);

        if ($existState instanceof \Entrili\XApiBundle\Repository\Mapping\State) {
            $existState->data = is_array($state->getData()) ? json_encode($state->getData()) : $state->getData();
            $this->repository->updateState($existState, true);
        } else {
            $this->repository->storeState($state, true);
        }

        $response = new Response();

        $now = new DateTime();
        $response->headers->set('X-Experience-API-Consistent-Through', $now->format('Y-m-d\TH:i:sP'));

        return $response;
    }
}