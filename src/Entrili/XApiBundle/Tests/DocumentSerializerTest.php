<?php

/*
 * This file is part of the xAPI package.
 *
 * (c) Christian Flothmann <christian.flothmann@xabbuh.de>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Entrili\XApiBundle\Tests;

use Entrili\XApiBundle\Tests\Fixtures\Data\DocumentFixtures;
use Entrili\XApiBundle\Tests\Fixtures\Json\DocumentJsonFixtures;

/**
 * @author Christian Flothmann <christian.flothmann@xabbuh.de>
 */
abstract class DocumentSerializerTest extends SerializerTest
{
    private $documentDataSerializer;

    protected function setUp()
    {
        $this->documentDataSerializer = $this->createDocumentSerializer();
    }

    public function testDeserializeDocument()
    {
        $documentData = $this->documentDataSerializer->deserializeDocument(DocumentJsonFixtures::getDocument());

        $this->assertInstanceOf('\Entrili\XApiBundle\Model\Document', $documentData);
        $this->assertEquals('foo', $documentData['x']);
        $this->assertEquals('bar', $documentData['y']);
    }

    public function testSerializeDocument()
    {
        $documentData = DocumentFixtures::getDocument();

        $this->assertJsonStringEqualsJsonString(
            DocumentJsonFixtures::getDocument(),
            $this->documentDataSerializer->serializeDocument($documentData)
        );
    }

    abstract protected function createDocumentSerializer();
}
