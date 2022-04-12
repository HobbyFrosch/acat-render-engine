<?php

namespace ACAT\Render\Condition;

use ACAT\Parser\Element\ConditionElement;
use ACAT\Render\Render;

class ConditionRender extends Render {

	/**
	 * @param ConditionElement $conditionElement
	 * @param array $values
	 * @throws Exception
	 */
	public function renderConditionElement(ConditionElement $conditionElement, array $values) : void {

		$conditionParser = new ConditionParser();

		try {

			if ($conditionParser->evaluateCondition($conditionElement, $values)) {
				$conditionAction = ConditionAction::getAction($conditionElement);
				$conditionAction->execute();
			}

		} catch (AppException $e) {
			Logging::getFormLogger()->warn($e);
		}

		$conditionElement->delete();

	}

	/**
	 * @param array $elements
	 * @param array $values
	 * @return mixed|void
	 * @throws Exception
	 */
	public function render(array $elements = [], array $values = []) {
		foreach ($elements as $conditionElement) {
			$this->renderConditionElement($conditionElement, $values);
		}
	}
}