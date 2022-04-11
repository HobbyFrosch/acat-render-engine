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
use ACAT\App\Util\DOMUtils;

/**
 * Class DeleteUntilNextElementAction
 * @package ACAT\Modul\Setting\Template\Model\Parser\Condition
 */
class DeleteUntilNextElementAction extends ConditionAction {

	/**
	 * @var array|string[]
	 */
	private array $validNodeNames = ['acat:condition', 'acat:field', 'acat:text'];

	/**
	 * @throws AppException
	 */
	public function execute() : void {
		foreach ($this->getNodesToDelete() as $nodeToDelete) {
			$nodeToDelete->parentNode->removeChild($nodeToDelete);
		}
	}

	/**
	 * @return array
	 * @throws AppException
	 */
	private function getNodesToDelete() : array {

		$found = false;
		$nodesToDelete = [];

		$runNode = DOMUtils::getParentNode($this->conditionElement->getElement(), 'w:r');

		if (!$runNode) {
			throw new AppException('malformed content part');
		}

		$runNodes = $this->conditionElement->getContentPart()->getXPath()->query('self::*|following-sibling::*', $runNode);

		foreach ($runNodes as $runNode) {
			$nodes = $this->conditionElement->getContentPart()->getXPath()->query('child::*', $runNode);
			foreach ($nodes as $node) {
				if (!$node->isSameNode($this->conditionElement->getElement())) {
					if (in_array($node->nodeName, $this->validNodeNames)) {
						if ($found) {
							return $nodesToDelete;
						}
						else {
							$found = true;
						}
					}
					$nodesToDelete[] = $node;
				}
			}
		}

		return $nodesToDelete;

	}
}