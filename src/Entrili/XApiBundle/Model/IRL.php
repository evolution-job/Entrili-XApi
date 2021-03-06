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
 * An internationalized resource locator.
 *
 * @author Christian Flothmann <christian.flothmann@xabbuh.de>
 */
final class IRL
{
    private $value;

    private function __construct()
    {
    }

    /**
     * @param string $value
     *
     * @return self
     *
     * @throws \InvalidArgumentException if the given value is no valid IRL
     */
    public static function fromString($value)
    {
        $iri = new self();
        $iri->value = $value;

        return $iri;
    }

    public function getValue()
    {
        return $this->value;
    }

    public function equals(IRL $irl)
    {
        return $this->value === $irl->value;
    }
}
