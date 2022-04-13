<?php

namespace Tests\Render;

use ACAT\Document\Word\WordDocument;
use ACAT\Exception\ConditionParserException;
use ACAT\Exception\DocumentException;
use ACAT\Exception\ElementException;
use ACAT\Exception\RenderException;
use ACAT\Exception\TagGeneratorException;
use ACAT\Render\RenderEngine;
use DOMException;
use PHPUnit\Framework\TestCase;

/**
 *
 */
class RenderEngineTest extends TestCase {

	/**
	 * @test
	 *
	 * @return void
	 * @throws ConditionParserException
	 * @throws DocumentException
	 * @throws ElementException
	 * @throws RenderException
	 * @throws TagGeneratorException
	 * @throws DOMException
	 */
	public function renderInvoiceWithEmptyResultSet() : void {

		$wordDocument = $this->getWordDocument();
		$wordDocument->open();

		$renderEngine = new RenderEngine($wordDocument);
		$renderEngine->render();

		$wordDocument->save();
		$wordDocument->close();

	}

	/**
	 * @test
	 *
	 * @return void
	 * @throws DocumentException
	 * @throws ElementException
	 * @throws TagGeneratorException
	 */
	public function getRecordStructure () : void {

		$wordDocument = $this->getWordDocument();
		$wordDocument->open();

		$renderEngine = new RenderEngine($wordDocument);
		$recordStructure = $renderEngine->getRecordStructure();

		$wordDocument->save();
		$wordDocument->close();

	}

	/**
	 * @return void
	 * @throws ConditionParserException
	 * @throws DOMException
	 * @throws DocumentException
	 * @throws ElementException
	 * @throws RenderException
	 * @throws TagGeneratorException
	 */
	public function renderInvoiceWithResultSet() : void {



		$wordDocument->open();

		foreach ($wordDocument->getContentParts() as $contentPart) {
			$renderEngine = new RenderEngine($contentPart, 'DETAILVIEW', 'rechnung');
			$renderEngine->render(2684);
		}

		$wordDocument->save();
		$wordDocument->close();

	}

	/**
	 * @return WordDocument
	 * @throws DocumentException
	 */
	private function getWordDocument() : WordDocument {

		$currentDocument = __DIR__ . "/resources/Rechnung_not_empty_lck.docx";
		copy(__DIR__ . '/resources/Rechnung.docx', $currentDocument);

		$wordDocument = new WordDocument($currentDocument);
		$this->assertInstanceOf(WordDocument::class, $wordDocument);

		return $wordDocument;

	}

}