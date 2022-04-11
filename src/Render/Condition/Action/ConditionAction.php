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

namespace ACAT\Modul\Setting\Template\Model\Parser\Condition;

use ACAT\App\Exception\AppException;
use ACAT\Modul\Setting\Template\Model\Document\Element\ConditionElement;

/**
 * Class ConditionAction
 * @package ACAT\Modul\Setting\Template\Model\Condition
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
	 * @throws AppException
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
			throw new AppException('unsupported action');
		}

		return $action;

	}

	/**
	 *
	 */
	abstract public function execute() : void;

}