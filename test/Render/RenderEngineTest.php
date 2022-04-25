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

/**
 *
 */
class RenderEngineTest extends AbstractRenderTest {

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
	 * @return void
	 * @throws ConditionParserException
	 * @throws DOMException
	 * @throws DocumentException
	 * @throws ElementException
	 * @throws RenderException
	 * @throws TagGeneratorException
	 */
	public function renderInvoiceWithResultSet() : void {

		$wordDocument = $this->getWordDocument();
		$wordDocument->open();

		foreach ($wordDocument->getContentParts() as $contentPart) {
			$renderEngine = new RenderEngine($contentPart, 'DETAILVIEW', 'rechnung');
			$renderEngine->render(2684);
		}

		$wordDocument->save();
		$wordDocument->close();

	}


}