<?php


namespace ACAT\Document\Word;

use DOMException;
use ACAT\Exception\DocumentException;
use ACAT\Placeholder\WordDocumentProtectionPlaceholder;

/**
 *
 */
class SettingsContentPart extends WordContentPart {

    /**
     * @param DocumentProtectionn $documentProtection
     * @return void
     * @throws DocumentException
     * @throws DOMException
     */
	public function protect(DocumentProtectionn $documentProtection) {

		$wordDocumentProtectionPlaceHolder = new WordDocumentProtectionPlaceholder($documentProtection);
		$nodes = $this->getXPath()->query('//w:settings');

		if (!$nodes || $nodes->length <> 1) {
			throw new DocumentException($this->path . ' has no root node');
		}

		$nodes->item(0)->appendChild($wordDocumentProtectionPlaceHolder->getDOMNode($this->domDocument));

	}
}