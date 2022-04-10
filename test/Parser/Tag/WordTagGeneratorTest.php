<?php

namespace ACAT\Parser\Tag;

use ACAT\Document\Word\WordContentPart;
use ACAT\Document\Word\WordDocument;
use ACAT\Exception\DocumentException;
use ACAT\Parser\Normalizer;
use ACAT\Parser\ParserConstants;
use ACAT\Parser\Placeholder\BlockPlaceholder;
use ACAT\Parser\Placeholder\FieldPlaceholder;
use ACAT\Parser\Placeholder\TextPlaceholder;
use ACAT\Parser\Placeholder\WordTextPlaceholder;
use DOMNodeList;
use PHPUnit\Framework\TestCase;

/**
 *
 */
class WordTagGeneratorTest extends TestCase {

	/**
	 * @test
	 */
	public function getTagWithOnePlaceHolder(): void {

		$testString = '${B:0}';
		$nodes = $this->getNodes($testString);

		$this->assertCount(1, $nodes);
		$this->assertInstanceOf(BlockPlaceholder::class, $nodes[0]);
		$this->assertEquals($testString, $nodes[0]->getNodeAsString());

	}

	/**
	 * @test
	 */
	public function getTagsWithPlaceHolderAtTheEnd(): void {

		$testString = 'noch mehr Text ${F:145} ';
		$nodes = $this->getNodes($testString);

		$this->assertCount(3, $nodes);

		$this->assertInstanceOf(WordTextPlaceholder::class, $nodes[0]);
		$this->assertEquals('noch mehr Text ', $nodes[0]->getNodeAsString());

		$this->assertInstanceOf(FieldPlaceholder::class, $nodes[1]);
		$this->assertEquals('${F:145}', $nodes[1]->getNodeAsString());

		$this->assertInstanceOf(WordTextPlaceholder::class, $nodes[2]);
		$this->assertEquals(' ', $nodes[2]->getNodeAsString());

	}

	/**
	 * @test
	 */
	public function getTagsWithPlaceHolderAtTheStart(): void {

		$testString = '${F:145} noch mehr Text';
		$nodes = $this->getNodes($testString);

		$this->assertCount(2, $nodes);

		$this->assertInstanceOf(FieldPlaceholder::class, $nodes[0]);
		$this->assertEquals('${F:145}', $nodes[0]->getNodeAsString());

		$this->assertInstanceOf(WordTextPlaceholder::class, $nodes[1]);
		$this->assertEquals(' noch mehr Text', $nodes[1]->getNodeAsString());

	}

	/**
	 * @test
	 */
	public function getTagsWithOnlyPlaceHolders(): void {

		$testString = '${F:145} ${F:146} ${F:147} ${F:148}';
		$nodes = $this->getNodes($testString);

		$this->assertCount(7, $nodes);

		$this->assertInstanceOf(FieldPlaceholder::class, $nodes[0]);
		$this->assertEquals('${F:145}', $nodes[0]->getNodeAsString());

		$this->assertInstanceOf(WordTextPlaceholder::class, $nodes[1]);
		$this->assertEquals(' ', $nodes[1]->getNodeAsString());

		$this->assertInstanceOf(FieldPlaceholder::class, $nodes[2]);
		$this->assertEquals('${F:146}', $nodes[2]->getNodeAsString());

		$this->assertInstanceOf(WordTextPlaceholder::class, $nodes[3]);
		$this->assertEquals(' ', $nodes[3]->getNodeAsString());

		$this->assertInstanceOf(FieldPlaceholder::class, $nodes[4]);
		$this->assertEquals('${F:147}', $nodes[4]->getNodeAsString());

		$this->assertInstanceOf(WordTextPlaceholder::class, $nodes[5]);
		$this->assertEquals(' ', $nodes[5]->getNodeAsString());

		$this->assertInstanceOf(FieldPlaceholder::class, $nodes[6]);
		$this->assertEquals('${F:148}', $nodes[6]->getNodeAsString());

	}

	/**
	 * @test
	 */
	public function getTagWithPlaceHolderAtTheStartAndEnd(): void {

		$testString = '${F:145} noch mehr Text ${T:0}';
		$nodes = $this->getNodes($testString);

		$this->assertCount(3, $nodes);

		$this->assertInstanceOf(FieldPlaceholder::class, $nodes[0]);
		$this->assertEquals('${F:145}', $nodes[0]->getNodeAsString());

		$this->assertInstanceOf(WordTextPlaceholder::class, $nodes[1]);
		$this->assertEquals(' noch mehr Text ', $nodes[1]->getNodeAsString());

		$this->assertInstanceOf(TextPlaceholder::class, $nodes[2]);
		$this->assertEquals('${T:0}', $nodes[2]->getNodeAsString());

	}

	/**
	 * @test
	 */
	public function getTagWithOnePlaceHolderAndText(): void {

		$testString = 'Ich bin ein Text ${F:145} noch mehr Text';
		$nodes = $this->getNodes($testString);

		$this->assertCount(3, $nodes);

		$this->assertInstanceOf(WordTextPlaceholder::class, $nodes[0]);
		$this->assertEquals('Ich bin ein Text ', $nodes[0]->getNodeAsString());

		$this->assertInstanceOf(FieldPlaceholder::class, $nodes[1]);
		$this->assertEquals('${F:145}', $nodes[1]->getNodeAsString());

		$this->assertInstanceOf(WordTextPlaceholder::class, $nodes[2]);
		$this->assertEquals(' noch mehr Text', $nodes[2]->getNodeAsString());

	}

	/**
	 * @test
	 */
	public function getTagWithOnePlaceHolderInTheMiddleAndAtTheEnd(): void {

		$testString = 'Ich bin ein Text ${F:145} noch mehr Text ${T:0}';
		$nodes = $this->getNodes($testString);

		$this->assertCount(4, $nodes);

		$this->assertInstanceOf(WordTextPlaceholder::class, $nodes[0]);
		$this->assertEquals('Ich bin ein Text ', $nodes[0]->getNodeAsString());

		$this->assertInstanceOf(FieldPlaceholder::class, $nodes[1]);
		$this->assertEquals('${F:145}', $nodes[1]->getNodeAsString());

		$this->assertInstanceOf(WordTextPlaceholder::class, $nodes[2]);
		$this->assertEquals(' noch mehr Text ', $nodes[2]->getNodeAsString());

		$this->assertInstanceOf(TextPlaceholder::class, $nodes[3]);
		$this->assertEquals('${T:0}', $nodes[3]->getNodeAsString());

	}

	/**
	 * @param $testString
	 * @return array
	 */
	private function getNodes($testString): array {

		$tagGenerator = new WordTagGenerator();
		preg_match_all(ParserConstants::MARKER_REG_EX, $testString, $matches, PREG_OFFSET_CAPTURE);

		return $tagGenerator->getTags($matches, $testString);

	}

	/**
	 * @test
	 *
	 * @throws DocumentException
	 */
	public function createTags(): void {

		$currentDocument = __DIR__ . "/Resources/tag_generator_test_document_lck.docx";

		//create a copy from original file
		copy(__DIR__ . '/Resources/tag_generator_test_document.docx', $currentDocument);

		//create new instance
		$wordDocument = new WordDocument($currentDocument);

		//open document
		$wordDocument->open();

		//check instance
		$this->assertInstanceOf(WordDocument::class, $wordDocument);

		//word tag generator
		$wordTagGenerator = new WordTagGenerator();

		//check instance
		$this->assertInstanceOf(WordTagGenerator::class, $wordTagGenerator);

		//normalizer
		$normalizer = new Normalizer();

		//check instance
		$this->assertInstanceOf(Normalizer::class, $normalizer);

		//get content parts
		foreach ($wordDocument->getContentParts() as $contentPart) {

			//check content part
			$this->assertInstanceOf(WordContentPart::class, $contentPart);

			//normalize the content part
			$normalizer->normalize($contentPart);

			//generate tags
			$wordTagGenerator->generateTags($contentPart);

		}

		//save document
		$wordDocument->save();

		//get 'word/document.xml' ContentPart
		$contentPart = $wordDocument->getContentParts()['word/document.xml'];
		$this->assertInstanceOf(WordContentPart::class, $contentPart);

		//get all acat:fields
		$fieldNodes = $contentPart->getXPath()->query('//acat:field');
		$this->assertInstanceOf(DOMNodeList::class, $fieldNodes);
		$this->assertCount(29, $fieldNodes);

		//get all acat:conditions
		$conditionNodes = $contentPart->getXPath()->query('//acat:condition');
		$this->assertInstanceOf(DOMNodeList::class, $conditionNodes);
		$this->assertCount(14, $conditionNodes);

		//get all acat:text
		$textNodes = $contentPart->getXPath()->query('//acat:text');
		$this->assertInstanceOf(DOMNodeList::class, $textNodes);
		$this->assertCount(2, $textNodes);

		//get all acat:block
		$blockNodes = $contentPart->getXPath()->query('//acat:block');
		$this->assertInstanceOf(DOMNodeList::class, $blockNodes);
		$this->assertCount(12, $blockNodes);

		//close document
		$wordDocument->close();

	}

}