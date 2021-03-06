<?php

namespace Tests\Parser\Tag;

use ACAT\Document\HTML\HTMLDocument;
use ACAT\Exception\DocumentException;
use ACAT\Exception\TagGeneratorException;
use ACAT\Parser\Tag\HTMLTagGenerator;
use ACAT\Parser\Tag\TagGenerator;
use PHPUnit\Framework\TestCase;

/**
 *
 */
class HTMLTagGeneratorTest extends TestCase {

	/**
	 * @test
	 *
	 * @throws DocumentException
	 * @throws TagGeneratorException
	 */
	public function createTags(): void {

		$currentDocument = __DIR__ . "/Resources/tag_generator_test_lck.html";

		//create a copy from original file
		copy(__DIR__ . '/Resources/tag_generator_test.html', $currentDocument);

		//create new instance
		$htmlDocument = new HTMLDocument($currentDocument);

		//check instance
		$this->assertInstanceOf(HTMLDocument::class, $htmlDocument);

		//html tag generator
		$htmlGenerator = TagGenerator::getInstance($htmlDocument->getContentPart());

		//check instance
		$this->assertInstanceOf(HTMLTagGenerator::class, $htmlGenerator);

		//generate tags
		$htmlGenerator->generateTags();

		//save document
		$htmlDocument->save();

	}

}