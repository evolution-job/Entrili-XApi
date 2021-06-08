<?php

/*
 * This file is part of the xAPI package.
 *
 * (c) Christian Flothmann <christian.flothmann@xabbuh.de>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Entrili\XApiBundle\Tests\Fixtures\Data;

use Entrili\XApiBundle\Model\Agent;
use Entrili\XApiBundle\Model\InverseFunctionalIdentifier;
use Entrili\XApiBundle\Model\IRI;
use Entrili\XApiBundle\Model\IRL;
use Entrili\XApiBundle\Model\LanguageMap;
use Entrili\XApiBundle\Model\Statement;
use Entrili\XApiBundle\Model\StatementId;
use Entrili\XApiBundle\Model\StatementResult;
use Entrili\XApiBundle\Model\Verb;

/**
 * Statement result fixtures.
 *
 * @author Christian Flothmann <christian.flothmann@xabbuh.de>
 */
class StatementResultFixtures
{
    /**
     * Loads a statement result.
     *
     * @param IRL $urlPath An optional URL path refering to more results
     *
     * @return StatementResult
     */
    public static function getStatementResult(IRL $urlPath = null)
    {
        $statement1 = StatementFixtures::getMinimalStatement();

        $verb = new Verb(IRI::fromString('http://adlnet.gov/expapi/verbs/deleted'), LanguageMap::create(array('en-US' => 'deleted')));
        $statement2 = new Statement(
            StatementId::fromString('12345678-1234-5678-8234-567812345679'),
            new Agent(InverseFunctionalIdentifier::withMbox(IRI::fromString('mailto:bob@example.com'))),
            $verb,
            $statement1->getObject()
        );

        $statementResult = new StatementResult(array($statement1, $statement2), $urlPath);

        return $statementResult;
    }

    /**
     * Loads a statement result including a more reference.
     *
     * @return StatementResult
     */
    public static function getStatementResultWithMore()
    {
        $statementResult = static::getStatementResult(IRL::fromString('/xapi/statements/more/b381d8eca64a61a42c7b9b4ecc2fabb6'));

        return $statementResult;
    }
}
