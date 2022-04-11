<?php

namespace Tests\Parser\Render;

use ACAT\Document\Word\WordContentPart;
use PHPUnit\Framework\TestCase;

/**
 *
 */
class AbstractRenderTest extends TestCase {

	/**
	 * @return WordContentPart
	 */
	protected function getWordContentPart() : WordContentPart {

		$testXMLFile = __DIR__ . '/Resources/document.xml';

		$xmlContent = file_get_contents($testXMLFile);
		$this->assertIsString($xmlContent);

		return new WordContentPart($testXMLFile, $xmlContent);

	}

	/**
	 * @return array
	 */
	protected function getValues() : array {

		$values['156'] = 156;
		$values['1121'] = 1121;
		$values['1744'] = 1744;
		$values['1745'] = 1745;
		$values['1747'] = 1747;
		$values['1748'] = 1748;

		return $values;

	}


}