<?php

namespace Tests\Render;

use ACAT\Document\Word\WordContentPart;
use ACAT\Exception\ElementException;
use ACAT\Parser\Element\ElementGenerator;
use ACAT\Parser\Element\Word\WordElementGenerator;
use PHPUnit\Framework\TestCase;

/**
 *
 */
class AbstractRenderTest extends TestCase {

	/**
	 * @test
	 *
	 * @return WordContentPart
	 */
	public function getWordContentPart(): WordContentPart {

		$testXMLFile = __DIR__ . '/Resources/document.xml';

		$xmlContent = file_get_contents($testXMLFile);
		$this->assertIsString($xmlContent);

		return new WordContentPart($testXMLFile, $xmlContent);

	}

	/**
	 * @return array
	 */
	protected function getValues(): array {

		$values['156'] = 156;
		$values['1121'] = 1121;
		$values['1744'] = 1744;
		$values['1745'] = 1745;
		$values['1747'] = 1747;
		$values['1748'] = 1748;

		return $values;

	}

	/**
	 * @return WordElementGenerator
	 * @throws ElementException
	 */
	public function getWordElementGenerator(): WordElementGenerator {
		$wordContentPart = $this->getWordContentPart();
		return ElementGenerator::getInstance($wordContentPart);
	}

	/**
	 * @return WordContentPart
	 */
	private function getParagraphBlockContentPart(): WordContentPart {

		$testXMLFile = __DIR__ . '/Resources/ParagraphBlock.xml';

		$xmlContent = file_get_contents($testXMLFile);
		$this->assertIsString($xmlContent);

		$contentPart = new WordContentPart($testXMLFile, $xmlContent);
		$this->assertInstanceOf(WordContentPart::class, $contentPart);

		return $contentPart;

	}

	/**
	 * @return WordElementGenerator
	 * @throws ElementException
	 */
	public function getWordParagraphElementGenerator() : WordElementGenerator {
		return ElementGenerator::getInstance($this->getParagraphBlockContentPart());
	}

	/**
	 * @return WordContentPart
	 */
	private function getTableRowBlockContentPart(): WordContentPart {

		$testXMLFile = __DIR__ . '/Resources/TableRowBlock.xml';

		$xmlContent = file_get_contents($testXMLFile);
		$this->assertIsString($xmlContent);

		$contentPart = new WordContentPart($testXMLFile, $xmlContent);
		$this->assertInstanceOf(WordContentPart::class, $contentPart);

		return $contentPart;

	}

	/**
	 * @return WordElementGenerator
	 * @throws ElementException
	 */
	public function getWordTableRowElementGenerator() : WordElementGenerator {
		return ElementGenerator::getInstance($this->getTableRowBlockContentPart());
	}

	/**
	 * @return WordContentPart
	 */
	protected function getConditionContentPart(): WordContentPart {

		$testXMLFile = __DIR__ . '/Resources/Conditions.xml';

		$xmlContent = file_get_contents($testXMLFile);
		$this->assertIsString($xmlContent);

		$contentPart = new WordContentPart($testXMLFile, $xmlContent);
		$this->assertInstanceOf(WordContentPart::class, $contentPart);

		return $contentPart;

	}

	/**
	 * @return WordElementGenerator
	 * @throws ElementException
	 */
	public function getConditionElementGenerator() : WordElementGenerator {
		return ElementGenerator::getInstance($this->getConditionContentPart());
	}

}