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

use ACAT\App\Util\DOMUtils;
use ACAT\Modul\Setting\Template\Model\Document\Element\BlockElement;

/**
 * Class TableRowBlockRender
 * @package ACAT\Modul\Setting\Template\Model\Render
 */
class TableRowBlockRender extends BlockRender {

	/**
	 * TableRowBlockRender constructor.
	 * @param BlockElement $blockElement
	 * @param array $values
	 */
	public function __construct(BlockElement $blockElement, array $values) {
        $this->values = $values;
        $this->blockElement = $blockElement;
	}

	/**
	 *
	 */
	public function cleanUpBlock() : void {

		parent::cleanUpBlock();

		$parentEndRow = DOMUtils::getParentNode($this->blockElement->getEnd(), 'w:tr');
		$parentStartRow = DOMUtils::getParentNode($this->blockElement->getStart(), 'w:tr');

		if ($parentEndRow) {
			$parentEndRow->parentNode->removeChild($parentEndRow);
		}
		else {
			$this->blockElement->getEnd()->parentNode->removeChild($this->blockElement->getEnd());
		}

		if ($parentStartRow) {
			$parentStartRow->parentNode->removeChild($parentStartRow);
		}
		else {
			$this->blockElement->getEnd()->parentNode->removeChild($this->blockElement->getStart());
		}

	}
}