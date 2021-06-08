<?php

/*
 * This file is part of the xAPI package.
 *
 * (c) Christian Flothmann <christian.flothmann@xabbuh.de>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Entrili\XApiBundle\Model;

/**
 * The Actor of a {@link Statement}.
 *
 * @author Christian Flothmann <christian.flothmann@xabbuh.de>
 */
abstract class Actor extends StatementObject
{
    /**
     * The actor's {@link InverseFunctionalIdentifier inverse functional identifier}
     *
     * @var InverseFunctionalIdentifier|null
     */
    private $iri;

    /**
     * Name of the {@link Agent} or {@link Group}
     * @var string|null
     */
    private $name;

    /**
     * @param InverseFunctionalIdentifier|null $iri
     * @param string|null $name
     */
    public function __construct(InverseFunctionalIdentifier $iri = null, $name = null)
    {
        $this->iri = $iri;
        $this->name = $name;
    }

    /**
     * Returns the Actor's {@link InverseFunctionalIdentifier inverse functional identifier}.
     *
     * @return InverseFunctionalIdentifier|null The inverse functional identifier
     */
    public function getInverseFunctionalIdentifier()
    {
        return $this->iri;
    }

    /**
     * Returns the name of the {@link Agent} or {@link Group}.
     *
     * @return string|null The name
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Checks if another actor is equal.
     *
     * Two actors are equal if and only if all of their properties are equal.
     *
     * @param StatementObject|Actor $object The actor to compare with
     *
     * @return bool True if the actors are equal, false otherwise
     */
    public function equals($object)
    {
        if (!parent::equals($object)) {
            return false;
        }

        if (!$object instanceof Actor) {
            return false;
        }

        if ($this->name !== $object->name) {
            return false;
        }

        if (null !== $this->iri xor null !== $object->iri) {
            return false;
        }

        if (null !== $this->iri && null !== $object->iri && !$this->iri->equals($object->iri)) {
            return false;
        }

        return true;
    }
}
