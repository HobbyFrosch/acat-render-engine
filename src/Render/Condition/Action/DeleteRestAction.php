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

use ACAT\App\Logging;
use ACAT\App\Util\DOMUtils;
use Exception;

/**
 * Class DeleteRemainingElements
 * @package ACAT\Modul\Setting\Template\Model\Parser\Condition
 */
class DeleteRestAction extends ConditionAction {

	/**
	 * @throws Exception
	 */
	public function execute() : void {

		$parentRunNode = DOMUtils::getParentNode($this->conditionElement->getElement(), 'w:r');

		if ($parentRunNode) {
			$elements = $this->conditionElement->getContentPart()->getXPath()->query("following-sibling::*", $parentRunNode);
			foreach ($elements as $element) {
				$removedNode = $element->parentNode->removeChild($element);
				if ($removedNode != $element) {
					Logging::getFormLogger()->warn('error while executing delete rest action -> could not delete node ' . $element->nodeName);
				}
			}
		}
	}
}