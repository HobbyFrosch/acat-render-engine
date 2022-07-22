<?php

namespace Tests\Render;

use ACAT\Exception\ConditionParserException;
use ACAT\Exception\DocumentException;
use ACAT\Exception\ElementException;
use ACAT\Exception\RenderException;
use ACAT\Exception\TagGeneratorException;
use ACAT\Render\RenderEngine;
use DOMException;
use Monolog\Logger;

/**
 *
 */
class RenderEngineTest extends AbstractRenderTest {

	/**
	 * @test
	 *
	 * @return void
	 * @throws DocumentException
	 */
	public function renderInvoiceWithEmptyResultSet() : void {

		$wordDocument = $this->getWordDocument();

		$renderEngine = new RenderEngine();
		$renderEngine->render($wordDocument, []);

	}

	/**
	 * @test
	 *
	 * @return void
	 * @throws DocumentException
	 */
	public function renderInvoiceWithResultSet() : void {

		setlocale (LC_TIME, 'German', 'de_DE', 'deu');

		$wordDocument = $this->getWordDocument();

		$renderEngine = new RenderEngine($this->getLogger());
		$renderEngine->render($wordDocument, $this->getData());

	}

	/**
	 * @test
	 *
	 * @return void
	 * @throws DocumentException
	 */
	public function renderTestDok() : void {

		$wordDocument = $this->getZulassung();

		$renderEngine = new RenderEngine($this->getLogger());
		$renderEngine->render($wordDocument, $this->getEZDate());

	}

}