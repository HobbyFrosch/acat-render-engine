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

namespace ACAT\Modul\Setting\Template\Model\Document\Element;

use ACAT\App\Util\DOMUtils;
use DOMNode;

/**
 * Class ParagraphBlock
 * @package ACAT\Modul\Setting\Template\Model\Document
 */
class ParagraphBlock extends BlockElement {

	/**
	 * @return DOMNode
	 */
	public function getContextNode() : DOMNode {
		return DOMUtils::getParentNode($this->getEnd(), 'w:p');
	}
}