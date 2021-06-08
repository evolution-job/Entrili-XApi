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
use Entrili\XApiBundle\Model\Result as ResultModel;
use Entrili\XApiBundle\Model\Score;

/**
 * Result
 *
 * @ORM\Table(name="xapi_result")
 * @ORM\Entity
 */
class Result
{
    /**
     * @var bool
     *
     * @ORM\Column(name="hasScore", type="boolean")
     */
    public $hasScore;

    /**
     * @var float
     *
     * @ORM\Column(name="scaled", type="float", nullable=true)
     */
    public $scaled;

    /**
     * @var float
     *
     * @ORM\Column(name="raw", type="float", nullable=true)
     */
    public $raw;

    /**
     * @var float
     *
     * @ORM\Column(name="min", type="float", nullable=true)
     */
    public $min;

    /**
     * @var float
     *
     * @ORM\Column(name="max", type="float", nullable=true)
     */
    public $max;

    /**
     * @var bool|null
     *
     * @ORM\Column(name="success", type="boolean", nullable=true)
     */
    public $success;

    /**
     * @var bool|null
     *
     * @ORM\Column(name="completion", type="boolean", nullable=true)
     */
    public $completion;

    /**
     * @var string
     *
     * @ORM\Column(name="response", type="string", nullable=true)
     */
    public $response;

    /**
     * @var string
     *
     * @ORM\Column(name="duration", type="string", nullable=true)
     */
    public $duration;

    /**
     * @var integer
     *
     * @ORM\Column(name="identifier", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    public $identifier;

    /**
     * @var \Entrili\XApiBundle\Repository\Mapping\Extensions
     *
     * @ORM\OneToOne(targetEntity="Entrili\XApiBundle\Repository\Mapping\Extensions", cascade={"all"})
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="extensions_id", referencedColumnName="identifier", unique=true)
     * })
     */
    public $extensions;

    /**
     * @param ResultModel $model
     * @return Result
     */
    public static function fromModel(ResultModel $model)
    {
        $result = new self();
        $result->success = $model->getSuccess();
        $result->completion = $model->getCompletion();
        $result->response = $model->getResponse();
        $result->duration = $model->getDuration();

        if (null !== $score = $model->getScore()) {
            $result->hasScore = true;
            $result->scaled = $score->getScaled();
            $result->raw = $score->getRaw();
            $result->min = $score->getMin();
            $result->max = $score->getMax();
        } else {
            $result->hasScore = false;
        }

        if (null !== $extensions = $model->getExtensions()) {
            $result->extensions = Extensions::fromModel($extensions);
        }

        return $result;
    }

    /**
     * @return ResultModel
     */
    public function getModel()
    {
        $score = null;
        $extensions = null;

        if ($this->hasScore) {
            $score = new Score($this->scaled, $this->raw, $this->min, $this->max);
        }

        if (null !== $this->extensions) {
            $extensions = $this->extensions->getModel();
        }

        return new ResultModel($score, $this->success, $this->completion, $this->response, $this->duration, $extensions);
    }
}
