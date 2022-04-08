<?php
/*
 * Copyright (c) 2021 - Akademie für Weiterbildung der Universtät Bremen
 *
 * The contents of this file are subject to the vtiger CRM Public License Version 1.0
 * ("License"); You may not use this file except in compliance with the License
 * The Original Code is:  vtiger CRM Open Source
 * The Initial Developer of the Original Code is vtiger.
 * Portions created by vtiger are Copyright (C) vtiger.
 * All Rights eserved.
 * reviewed and modified by Akademie für Weiterbildung der Universtät Bremen
 */

namespace ACAT\Modul\Setting\Template\Model\Document;

use ACAT\App\Exception\AppException;
use ACAT\Modul\Setting\Template\Model\Placeholder\WordDocumentProtectionPlaceholder;

/**
 * Class SettingsContentPart
 * @package ACAT\Modul\Setting\Template\Model\Document
 */
class SettingsContentPart extends ContentPart {

	/**
	 * @param DocumentProtection $documentProtection
	 * @throws AppException
	 */
	public function protect(DocumentProtection $documentProtection) {

		$wordDocumentProtectionPlaceHolder = new WordDocumentProtectionPlaceholder($documentProtection);
		$nodes = $this->getXPath()->query('//w:settings');

		if (!$nodes || $nodes->length <> 1) {
			throw new AppException($this->path . ' has no root node');
		}

		$nodes->item(0)->appendChild($wordDocumentProtectionPlaceHolder->getDOMNode($this->domDocument));

	}

}