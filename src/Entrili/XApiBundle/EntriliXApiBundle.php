<?php

/*
 * This file is part of the xAPI package.
 *
 * (c) Christian Flothmann <christian.flothmann@xabbuh.de>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Entrili\XApiBundle;

use Symfony\Component\HttpKernel\Bundle\Bundle;
use Entrili\XApiBundle\DependencyInjection\EntriliXApiExtension;

/**
 * @author Christian Flothmann <christian.flothmann@xabbuh.de>
 */
class EntriliXApiBundle extends Bundle
{
    public function getContainerExtension()
    {
        return new EntriliXApiExtension();
    }
}
