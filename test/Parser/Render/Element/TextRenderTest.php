<?php
/*
 * Copyright (c) 2020 - Akademie für Weiterbildung der Universtät Bremen
 *
 * The contents of this file are subject to the vtiger CRM Public License Version 1.0
 * ("License"); You may not use this file except in compliance with the License
 * The Original Code is:  vtiger CRM Open Source
 * The Initial Developer of the Original Code is vtiger.
 * Portions created by vtiger are Copyright (C) vtiger.
 * All Rights eserved.
 * reviewed and modified by Akademie für Weiterbildung der Universtät Bremen
 */

namespace Test\Template\Model\Document\Render;

use ACAT\App\Exception\AppException;
use ACAT\Modul\Setting\Template\Model\Document\ContentPart;
use ACAT\Modul\Setting\Template\Model\Document\Element\TextElement;
use ACAT\Modul\Setting\Template\Model\Parser\ParserConstants;
use ACAT\Modul\Setting\Template\Model\Render\TextRender;
use DOMNodeList;
use Exception;
use PHPUnit\Framework\TestCase;

/**
 * Class TextRenderTest
 * @package Test\Template\Model\Document\Render
 */
class TextRenderTest extends TestCase {

	/**
	 * @covers \ACAT\Modul\Setting\Template\Model\Render\TextRender::__construct
	 * @test
	 */
	public function aTextRenderCanBeCreated() : void {
		$textRender = new TextRender();
		$this->assertInstanceOf(TextRender::class, $textRender);
	}

	/**
	 * @test
	 * @covers \ACAT\Modul\Setting\Template\Model\Render\TextRender::render
	 * @throws AppException
	 * @throws Exception
	 */
	public function textFieldsCanBeRendered() : void {

		$contentPart = $this->getContentPart();

		$textRender = new TextRender();
		$this->assertInstanceOf(TextRender::class, $textRender);

		$textRender->render($contentPart->getTextElements());

		$acatTextNodes = $contentPart->getXPath()->query('//' . ParserConstants::ACAT_TEXT_NODES);
		$this->assertInstanceOf(DOMNodeList::class, $acatTextNodes);
		$this->assertEquals(0, $acatTextNodes->length);

	}

	/**
	 * @test
	 * @covers \ACAT\Modul\Setting\Template\Model\Render\TextRender::renderTextField
	 * @throws AppException
	 * @throws Exception
	 */
	public function aFieldCanBeRendered() : void {

		$contentPart = $this->getContentPart();

		$textRender = new TextRender();
		$this->assertInstanceOf(TextRender::class, $textRender);

		$textNodes = $contentPart->getXPath()->query('//' . ParserConstants::ACAT_TEXT_NODES);
		$this->assertInstanceOf(DOMNodeList::class, $textNodes);
		$this->assertEquals(2, $textNodes->length);

		foreach ($textNodes as $textNode) {
			$textRender->renderTextElement(new TextElement($textNode, $contentPart));
		}

		$textNodes = $contentPart->getXPath()->query('//' . ParserConstants::ACAT_TEXT_NODES);
		$this->assertInstanceOf(DOMNodeList::class, $textNodes);
		$this->assertEquals(0, $textNodes->length);

	}

	/**
	 * @return ContentPart
	 * @throws AppException
	 */
	private function getContentPart () : ContentPart {

		$testXMLFile = __DIR__ . '/resources/document.xml';

		$xmlContent = file_get_contents($testXMLFile);
		$this->assertIsString($xmlContent);

		$contentPart = new ContentPart($testXMLFile, $xmlContent);
		$this->assertInstanceOf(ContentPart::class, $contentPart);

		return $contentPart;

	}

}