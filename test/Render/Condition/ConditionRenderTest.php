<?php

namespace Tests\Render\Block;

use ACAT\Exception\ConditionParserException;
use ACAT\Exception\ElementException;
use ACAT\Exception\RenderException;
use ACAT\Parser\Element\ConditionElement;
use ACAT\Parser\ParserConstants;
use ACAT\Render\Condition\ConditionRender;
use DOMNodeList;
use Tests\Render\AbstractRenderTest;

/**
 *
 */
class ConditionRenderTest extends AbstractRenderTest {

	/**
	 * @test
	 */
	public function aConditionRenderCanBeCreated() : void {
		$conditionRender = new ConditionRender();
		$this->assertInstanceOf(ConditionRender::class, $conditionRender);
	}

	/**
	 * @test
	 *
	 * @return void
	 * @throws ConditionParserException
	 * @throws ElementException
	 * @throws RenderException
	 */
	public function renderConditions() : void {

		$values['151'] = 2;

		$conditionRender = new ConditionRender();
		$conditionElementGenerator = $this->getConditionElementGenerator();

		$nodes = $conditionElementGenerator->getContentPart()->getXPath()->query('//' . ParserConstants::ACAT_CONDITION_NODE);

		$this->assertInstanceOf(DOMNodeList::class, $nodes);
		$this->assertEquals(6, $nodes->length);

		$conditionElements = $conditionElementGenerator->getConditionElements();
		$this->assertCount(6, $conditionElements);

		$conditionRender->render($conditionElements, $values);

		$nodes = $conditionElementGenerator->getContentPart()->getXPath()->query('//' . ParserConstants::ACAT_CONDITION_NODE);

		$this->assertInstanceOf(DOMNodeList::class, $nodes);
		$this->assertEquals(0, $nodes->length);

	}

	/**
	 * @test
	 *
	 * @return void
	 * @throws ConditionParserException
	 * @throws ElementException
	 * @throws RenderException
	 */
	public function renderCondition() : void {

		$values['151'] = 2;

		$conditionRender = new ConditionRender();
		$conditionElementGenerator = $this->getConditionElementGenerator();

		$nodes = $conditionElementGenerator->getContentPart()->getXPath()->query('//' . ParserConstants::ACAT_CONDITION_NODE);

		$this->assertInstanceOf(DOMNodeList::class, $nodes);
		$this->assertEquals(6, $nodes->length);

		foreach ($nodes as $node) {
			$conditionRender->renderConditionElement(new ConditionElement($node), $values);
		}

		$nodes = $conditionElementGenerator->getContentPart()->getXPath()->query('//' . ParserConstants::ACAT_CONDITION_NODE);

		$this->assertInstanceOf(DOMNodeList::class, $nodes);
		$this->assertEquals(0, $nodes->length);

	}

}