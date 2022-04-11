<?php

namespace Test\Template\Model\Document\Element;

use ACAT\Document\Word\WordContentPart;
use JetBrains\PhpStorm\Pure;
use PHPUnit\Framework\TestCase;

/**
 *
 */
abstract class AbstractElementTest extends TestCase {

	/**
	 * @return WordContentPart
	 */
	#[Pure(true)]
	protected function getWordContentPart() : WordContentPart {
		return new WordContentPart('',  file_get_contents(__DIR__ . '/Resources/document.xml'));
	}

}