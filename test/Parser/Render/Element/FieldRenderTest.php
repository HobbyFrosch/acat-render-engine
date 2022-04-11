<?php

namespace Tests\Parser\Render\Element;

use ACAT\Exception\ElementException;
use ACAT\Exception\RenderException;
use ACAT\Parser\Element\FieldElement;
use ACAT\Parser\ParserConstants;
use ACAT\Render\Element\FieldRender;
use DOMException;
use DOMNodeList;
use Tests\Parser\Render\AbstractRenderTest;

/**
 *
 */
class FieldRenderTest extends AbstractRenderTest {

	/**
	 * @test
	 *
	 * @return void
	 */
	public function aFieldRenderCanBeCreated(): void {
		$fieldRender = new FieldRender();
		$this->assertInstanceOf(FieldRender::class, $fieldRender);
	}

	/**
	 * @test
	 *
	 * @return void
	 */
	public function fieldsCanBeRendered(): void {

		$values = $this->getValues();
		$contentPart = $this->getWordContentPart();

		$fieldRender = new FieldRender();
		$this->assertInstanceOf(FieldRender::class, $fieldRender);

		$fieldRender->render($contentPart->getFieldElements(), $values);
		$acatFieldNodes = $contentPart->getXPath()->query('//' . ParserConstants::ACAT_FIELD_NODE);

		$this->assertInstanceOf(DOMNodeList::class, $acatFieldNodes);
		$this->assertEquals(10, $acatFieldNodes->length);

	}

	/**
	 * @test
	 *
	 * @return void
	 * @throws ElementException
	 * @throws RenderException
	 * @throws DOMException
	 */
	public function aFieldCanBeRendered(): void {

		$values = $this->getValues();
		$contentPart = $this->getWordContentPart();

		$fieldRender = new FieldRender();
		$this->assertInstanceOf(FieldRender::class, $fieldRender);

		$fieldNodes = $contentPart->getXPath()->query('//' . ParserConstants::ACAT_FIELD_NODE);
		$this->assertInstanceOf(DOMNodeList::class, $fieldNodes);
		$this->assertEquals(29, $fieldNodes->length);

		foreach ($fieldNodes as $fieldNode) {
			$fieldRender->renderFieldElement(new FieldElement($fieldNode, $contentPart), $values);
		}

		$fieldNodes = $contentPart->getXPath()->query('//' . ParserConstants::ACAT_FIELD_NODE);
		$this->assertInstanceOf(DOMNodeList::class, $fieldNodes);
		$this->assertEquals(0, $fieldNodes->length);

	}


}