<?php

/*
 * This file is part of the xAPI package.
 *
 * (c) Christian Flothmann <christian.flothmann@xabbuh.de>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Entrili\XApiBundle\Tests\Repository\Storage;

use Entrili\XApiBundle\Repository\Mapping\StatementObject as MappedObject;

/**
 * {@link StatementObject} repository interface definition.
 *
 * @author Jérôme Parmentier <jerome.parmentier@acensi.fr>
 */
interface ObjectStorage
{
    /**
     * @param array $criteria
     *
     * @return MappedObject The object or null if no matching object
     *                      has been found
     */
    public function findObject(array $criteria);
}
