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

namespace ACAT\Modul\Setting\Template\Model\Render;

use ACAT\App\Exception\AppException;
use ACAT\App\Logging;
use ACAT\Modul\Setting\Template\Model\Document\Element\ConditionElement;
use ACAT\Modul\Setting\Template\Model\Parser\Condition\ConditionAction;
use ACAT\Modul\Setting\Template\Model\Parser\Condition\ConditionParser;
use DOMDocument;
use Exception;

/**
 * Class ConditionRender
 * @package ACAT\Modul\Setting\Template\Model\Render
 */
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