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

use ACAT\Modul\Setting\Template\Model\Document\Element\BlockElement;

/**
 * Class TextBlockRender
 * @package ACAT\Modul\Setting\Template\Model\Render
 */
class TextBlockRender extends BlockRender {

	/**
	 * TextBlockRender constructor.
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

		$parentEndNode = $this->blockElement->getEnd()->parentNode;
		$parentStartNode = $this->blockElement->getStart()->parentNode;

		if ($parentStartNode) {
			$parentStartNode->removeChild($this->blockElement->getStart());
		}

		if ($parentEndNode) {
			$parentEndNode->removeChild($this->blockElement->getEnd());
		}

	}

}