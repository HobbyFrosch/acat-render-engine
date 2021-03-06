<?php

namespace Tests\Parser\Element;

use ACAT\Exception\ElementException;
use ACAT\Parser\Element\TextElement;
use ACAT\Parser\ParserConstants;
use DOMNodeList;
use Test\Template\Model\Document\Element\AbstractElementTest;

/**
 *
 */
class TextElementTest extends AbstractElementTest {

	/**
	 * @test
	 *
	 * @return void
	 */
	public function aTextElementCanBeCreated() : void {

		$wordContentPart = $this->getWordContentPart();
		$fieldNodes = $wordContentPart->getXPath()->query('//' . ParserConstants::ACAT_TEXT_NODES);

		$this->assertInstanceOf(DOMNodeList::class, $fieldNodes);
		$this->assertEquals(2, $fieldNodes->length);

		$fieldElement = new TextElement($fieldNodes->item(0));
		$nodeValue = $fieldNodes->item(0)->nodeValue;

		$this->assertInstanceOf(TextElement::class, $fieldElement);
		$this->assertEquals($nodeValue, $fieldElement->getText());

	}

	/**
	 * @test
	 *
	 * @return void
	 * @throws ElementException
	 */
	public function aTextElementFieldIdThrowsAppException() : void {

		$this->expectException(ElementException::class);

		$wordContentPart = $this->getWordContentPart();
		$fieldNodes = $wordContentPart->getXPath()->query('//' . ParserConstants::ACAT_TEXT_NODES);

		$this->assertInstanceOf(DOMNodeList::class, $fieldNodes);
		$this->assertEquals(2, $fieldNodes->length);

		$fieldElement = new TextElement($fieldNodes->item(0));
		$this->assertInstanceOf(TextElement::class, $fieldElement);

		$fieldElement->getFieldId();

	}

}