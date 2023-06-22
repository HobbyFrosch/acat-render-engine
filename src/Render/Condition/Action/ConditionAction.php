<?php

namespace ACAT\Render\Condition\Action;

use ACAT\Exception\ElementException;
use ACAT\Exception\RenderException;
use ACAT\Parser\Element\ConditionElement;

/**
 *
 */
abstract class ConditionAction {

	/**
	 * @var ConditionElement
	 */
	protected ConditionElement $conditionElement;

	/**
	 * ConditionAction constructor.
	 * @param ConditionElement $conditionElement
	 */
	public function __construct(ConditionElement $conditionElement) {
		$this->conditionElement = $conditionElement;
	}

	/**
	 * @param ConditionElement $conditionElement
	 * @return ConditionAction
	 * @throws RenderException
	 * @throws ElementException
	 */
	static function getAction(ConditionElement $conditionElement) : ConditionAction {

		if ($conditionElement->getAction() == '0') {
			$action = new DeleteParagraphAction($conditionElement);
		}
		else if ($conditionElement->getAction() == '1') {
			$action = new DeleteRemainingElementsAction($conditionElement);
		}
		else if ($conditionElement->getAction() == '2') {
			$action = new DeleteNextElementAction($conditionElement);
		}
		else if ($conditionElement->getAction() == '3') {
			$action = new DeleteRestAction($conditionElement);
		}
		else if ($conditionElement->getAction() == '4') {
			$action = new DeleteUntilNextElementAction($conditionElement);
		}
		else {
			throw new RenderException('unsupported action');
		}

		return $action;

	}

	/**
	 *
	 */
	abstract public function execute() : void;

}