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

use ACAT\Modul\Setting\Template\Model\Document\Element\TextElement;
use ACAT\Modul\Setting\Template\Model\Placeholder\WordTextPlaceholder;
use DOMDocument;

/**
 * Class TextRender
 * @package ACAT\Modul\Setting\Template\Model\Render
 */
class TextRender extends Render {

	/**
	 * @param TextElement $textElement
	 */
	public function renderTextElement(TextElement $textElement) {

		$wordTextNode = new WordTextPlaceholder($textElement->getText());

		$this->appendRenderedNode($textElement->getElement(), $wordTextNode->getDOMNode($textElement->getElement()->ownerDocument));
		$this->deleteNode($textElement->getElement());

	}

	/**
	 * @param array $elements
	 * @param array $values
	 * @return mixed|void
	 */
	public function render(array $elements, array $values = []) {
		foreach ($elements as $textElement) {
			$this->renderTextElement($textElement);
		}
	}

}