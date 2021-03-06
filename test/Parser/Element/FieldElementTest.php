<?php

namespace Tests\Parser\Element;

use ACAT\Exception\ElementException;
use ACAT\Parser\Element\FieldElement;
use ACAT\Parser\ParserConstants;
use DOMNode;
use DOMNodeList;
use Test\Template\Model\Document\Element\AbstractElementTest;

/**
 *
 */
class FieldElementTest extends AbstractElementTest {

	/**
	 * @test
	 *
	 * @return void
	 * @throws ElementException
	 */
	public function aFieldElementCanBeCreated() : void {

		$wordContentPart = $this->getWordContentPart();
		$fieldNodes = $wordContentPart->getXPath()->query('//' . ParserConstants::ACAT_FIELD_NODE);

		$this->assertInstanceOf(DOMNodeList::class, $fieldNodes);
		$this->assertEquals(29, $fieldNodes->length);

		$fieldElement = new FieldElement($fieldNodes->item(0));
		$fieldId = $fieldNodes->item(0)->getAttribute('field');

		$this->assertInstanceOf(FieldElement::class, $fieldElement);
		$this->assertEquals($fieldId, $fieldElement->getFieldId());

	}

	/**
	 * @test
	 *
	 * @return void
	 */
	public function getElementFromFieldElement() : void {

		$wordContentPart = $this->getWordContentPart();
		$fieldNodes = $wordContentPart->getXPath()->query('//' . ParserConstants::ACAT_FIELD_NODE);

		$this->assertInstanceOf(DOMNodeList::class, $fieldNodes);
		$this->assertEquals(29, $fieldNodes->length);

		$fieldElement = new FieldElement($fieldNodes->item(0));
		$this->assertInstanceOf(DOMNode::class, $fieldElement->getElement());

	}

	/**
	 * @test
	 *
	 * @return void
	 * @throws ElementException
	 */
	public function aFieldElementWithoutFieldIdThrowsAnException() : void {

		$this->expectException(ElementException::class);

		$wordContentPart = $this->getWordContentPart();
		$fieldNodes = $wordContentPart->getXPath()->query('//' . ParserConstants::ACAT_FIELD_NODE);

		$this->assertInstanceOf(DOMNodeList::class, $fieldNodes);
		$this->assertEquals(29, $fieldNodes->length);

		$fieldElement = new FieldElement($fieldNodes->item(0));
		$fieldNodes->item(0)->removeAttribute('field');

		$fieldElement->getFieldId();

	}

}