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
use DOMNode;

/**
 * Class DeletePlaceholderAction
 * @package ACAT\Modul\Setting\Template\Model\Condition
 */
class DeleteParagraphAction extends ConditionAction {

	/**
	 * @throws AppException
	 */
	public function execute() : void {

		$paragraph = DOMUtils::getParentNode($this->conditionElement->getElement(), 'w:p');

		if ($paragraph && !DOMUtils::isRemoved($paragraph)) {
			if ((int) $this->conditionElement->getContentPart()->getXPath()->evaluate('count(.//w:p)', $paragraph->parentNode) == 1 && $paragraph->parentNode->nodeName == 'w:tc') {
				$this->deleteParagraphInaCell($paragraph);
			}
			else {
				$deletedNode = $paragraph->parentNode->removeChild($paragraph);
				if (!$paragraph->isSameNode($deletedNode)) {
					throw new AppException($paragraph->nodeName . ' could not removed');
				}
			}
		}
		else {
			throw new AppException($this->conditionElement->getId() . ' is already deleted');
		}
	}

	/**
	 * @param DOMNode $paragraphNode
	 * @see http://officeopenxml.com/WPtableCell.php
	 */
	public function deleteParagraphInaCell(DOMNode $paragraphNode) : void {
		if ($paragraphNode->nodeName == 'w:p') {
			while ($paragraphNode->hasChildNodes()) {
				$paragraphNode->removeChild($paragraphNode->firstChild);
			}
		}
	}

}