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
use ACAT\App\Logging;
use ACAT\App\Util\DOMUtils;
use Exception;

/**
 * Class DeleteNextPlaceholderAction
 * @package ACAT\Modul\Setting\Template\Model\Condition
 */
class DeleteNextElementAction extends ConditionAction {

	/**
	 *
	 */
	private string $query = './/acat:field|.//acat:text';

	/**
	 * @throws AppException
	 * @throws Exception
	 */
	public function execute() : void {

		$nodeToDelete = null;

		$element = $this->conditionElement->getElement();
		$elements = $this->conditionElement->getContentPart()->getXPath()->query($this->query, $element);

		if ($elements->length > 0) {
			$nodeToDelete = $elements->item(0);
		}
		else {
			$parentNode = DOMUtils::getParentNode($element, 'w:r');
			if (!$parentNode) {
				throw new AppException($element->nodeName . ' has no parent w:r');
			}
			$rNodes = $this->conditionElement->getContentPart()->getXPath()->query('following-sibling::w:r', $parentNode);
			foreach ($rNodes as $rNode) {
				$elements = $this->conditionElement->getContentPart()->getXPath()->query($this->query, $rNode);
				if ($elements->length > 0) {
					$nodeToDelete = $elements->item(0);
					break;
				}
			}
		}

		if ($nodeToDelete) {
			$nodeToDelete->parentNode->removeChild($nodeToDelete);
		}
		else {
			Logging::getFormLogger()->warn($this->conditionElement->getElement()->nodeName . ' has no following placeholders');
		}

	}
}