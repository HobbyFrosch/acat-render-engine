<?php

namespace ACAT\Render\Condition;

use ACAT\Exception\ConditionParserException;
use ACAT\Exception\ElementException;
use ACAT\Exception\RenderException;
use ACAT\Parser\Element\ConditionElement;
use ACAT\Render\Condition\Action\ConditionAction;
use ACAT\Render\Condition\Action\ConditionParser;
use ACAT\Render\Render;
use ACAT\Utils\DOMUtils;

/**
 *
 */
class ConditionRender extends Render {

	/**
	 * @param ConditionElement $conditionElement
	 * @param array $values
	 * @return void
	 * @throws ConditionParserException
	 * @throws ElementException
	 * @throws RenderException
	 */
	public function renderConditionElement(ConditionElement $conditionElement, array $values) : void {

		$conditionParser = new ConditionParser();

		if (!DOMUtils::isRemoved($conditionElement->getElement()) && $conditionParser->evaluateCondition($conditionElement, $values)) {
			$conditionAction = ConditionAction::getAction($conditionElement);
			$conditionAction->execute();
		}

		$conditionElement->delete();

	}

	/**
	 * @param array $elements
	 * @param array $values
	 * @return void
	 * @throws ConditionParserException
	 * @throws ElementException
	 * @throws RenderException
	 */
	public function render(array $elements = [], array $values = []) : void {
		foreach ($elements as $conditionElement) {
			$this->renderConditionElement($conditionElement, $values);
		}
	}
}