<?php

/*
 * This file is part of the xAPI package.
 *
 * (c) Christian Flothmann <christian.flothmann@xabbuh.de>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Entrili\XApiBundle\Repository\Mapping;

use Doctrine\ORM\Mapping as ORM;
use Entrili\XApiBundle\Model\Extensions as ExtensionsModel;
use Entrili\XApiBundle\Model\IRI;

/**
 * Extensions
 *
 * @ORM\Table(name="xapi_extensions")
 * @ORM\Entity
 */
class Extensions
{
    /**
     * @var array
     *
     * @ORM\Column(name="extensions", type="json")
     */
    public $extensions;

    /**
     * @var integer
     *
     * @ORM\Column(name="identifier", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    public $identifier;

    /**
     * @param ExtensionsModel $model
     * @return Extensions
     */
    public static function fromModel(ExtensionsModel $model)
    {
        $extensions = new self();
        $extensions->extensions = array();

        foreach ($model->getExtensions() as $key) {
            $extensions->extensions[$key->getValue()] = $model->offsetGet($key);
        }

        return $extensions;
    }

    /**
     * @return ExtensionsModel
     */
    public function getModel()
    {
        $extensions = new \SplObjectStorage();

        foreach ($this->extensions as $key => $extension) {
            $extensions->attach(IRI::fromString($key), $extension);
        }

        return new ExtensionsModel($extensions);
    }
}
