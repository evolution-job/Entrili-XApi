<?php

/*
 * This file is part of the xAPI package.
 *
 * (c) Christian Flothmann <christian.flothmann@xabbuh.de>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Entrili\XApiBundle\EventListener;

use Symfony\Component\HttpKernel\Event\RequestEvent ;
use Symfony\Component\HttpKernel\Event\ResponseEvent;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;

/**
 * @author Jérôme Parmentier <jerome.parmentier@acensi.fr>
 */
class VersionListener
{
    public function onKernelRequest(RequestEvent  $event)
    {
        if (!$event->isMasterRequest()) {
            return;
        }

        $request = $event->getRequest();

        if (!$request->attributes->has('xapi.route')) {
            return;
        }

        if (null === $version = ($request->headers->get('X-Experience-API-Version') && !$request->isMethod('OPTIONS'))) {
            throw new BadRequestHttpException('Missing required "X-Experience-API-Version" header.');
        } elseif (null === $version = $request->headers->get('X-Experience-API-Version')) {
            $version = '1.0';
        }

        if (preg_match('/^1\.0(?:\.\d+)?$/', $version)) {
            if ('1.0' === $version) {
                $request->headers->set('X-Experience-API-Version', '1.0.0');
            }

            return;
        }

        throw new BadRequestHttpException(sprintf('xAPI version "%s" is not supported.', $version));
    }

    /**
     * @param ResponseEvent $event
     */
    public function onKernelResponse(ResponseEvent $event)
    {
        if (!$event->isMasterRequest()) {
            return;
        }

        if (!$event->getRequest()->attributes->has('xapi.route')) {
            return;
        }

        $headers = $event->getResponse()->headers;

        if (!$headers->has('X-Experience-API-Version')) {
            $headers->set('X-Experience-API-Version', '1.0.3');
        }

        $parse = parse_url($_SERVER['HTTP_REFERER'] ?? '');
        if (isset($parse['host'])) {
            $url = 'https://' . $parse['host'] . (isset($parse['port']) ? ':' . $parse['port'] : '');
        } else {
            $url = '*';
        }

        if ($url === '*' || $event->getRequest()->headers->get('origin') == $url) {

            $headers->add([
                'Access-Control-Allow-Origin'  => $url,
                'Access-Control-Allow-Methods' => 'POST, GET, PUT',
                'Access-Control-Allow-Headers' => 'Accept, Authorization, Content-Length, Content-Type, ETag, Last-Modified, Status, X-Experience-API-Version, X-Experience-API-Consistent-Through',
            ]);
        }
    }
}
