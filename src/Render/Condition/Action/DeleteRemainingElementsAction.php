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

/**
 * Class DeleteRemainingPlaceholderAction
 * @package ACAT\Modul\Setting\Template\Model\Condition
 */
class DeleteRemainingElementsAction extends ConditionAction {

	/**
	 * @throws AppException
	 */
	public function execute() : void {
		$nodes = $this->conditionElement->getContentPart()->getXPath()->query('.//acat:field|.//acat:text', $this->conditionElement->getElement());
		foreach ($nodes as $node) {
			$deletedNode = $node->parentNode->removeChild($node);
			if (!$deletedNode->isSameNode($node)) {
				throw new AppException($node->nodeName . ' could not removed');
			}
		}

	}
}