<?php

namespace Entrili\XApiBundle\EventListener;

use Entrili\XApiBundle\Serializer\StatementSerializerInterface;
use Entrili\XApiBundle\Serializer\StateSerializerInterface;
use Symfony\Component\HttpKernel\Event\RequestEvent ;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Symfony\Component\Serializer\Exception\ExceptionInterface as BaseSerializerException;

/**
 * @author Christian Flothmann <christian.flothmann@xabbuh.de>
 */
class SerializerListener
{
    private $stateSerializer;
    private $statementSerializer;

    public function __construct(StatementSerializerInterface $statementSerializer, StateSerializerInterface $stateSerializer)
    {
        $this->statementSerializer = $statementSerializer;
        $this->stateSerializer = $stateSerializer;
    }

    /**
     * @param RequestEvent  $event
     * @throws \Entrili\XApiBundle\Exception\UnsupportedStatementVersionException
     */
    public function onKernelRequest(RequestEvent  $event)
    {
        $request = $event->getRequest();

        if (!$request->attributes->has('xapi.route')) {
            return;
        }

        if ($request->isMethod('OPTIONS'))
            return;

        try {
            switch ($request->attributes->get('xapi_serializer')) {
                case 'state':
                    $request->attributes->set('state', $this->stateSerializer->deserializeState($request->query->all(), $request->getContent()));
                    break;

                case 'statement':
                    $request->attributes->set('statement', $this->statementSerializer->deserializeStatement($request->getContent()));
                    break;
            }
        } catch (BaseSerializerException $e) {
            throw new BadRequestHttpException(sprintf('The content of the request cannot be deserialized into a valid xAPI %s.', $request->attributes->get('xapi_serializer')), $e);
        }
    }
}
