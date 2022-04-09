<?php

namespace Tests\Document\Word;

use ACAT\Document\Word\WordDocument;
use ACAT\Exception\DocumentException;
use PHPUnit\Framework\TestCase;

class WordTest extends TestCase {

	/**
	 * @test
	 *
	 * @return void
	 * @throws DocumentException
	 */
	public function createWordDocument() : void {

		$path = __DIR__ . '/TEST.docx';
		$wordDocument = new WordDocument($path);

		$this->assertInstanceOf(WordDocument::class, $wordDocument);

		$wordDocument->open();

		foreach ($wordDocument->getContentParts() as $contentPart) {

		}

	}

	/**
	 * @test
	 *
	 * @return void
	 * @throws DocumentException
	 */
	public function invalidWordDocument() : void {

		$path = __DIR__ . '/invalid_word_document';
		$wordDocument = new WordDocument($path);

		$this->assertInstanceOf(WordDocument::class, $wordDocument);

		$this->expectException(DocumentException::class);
		$wordDocument->open();

	}

}