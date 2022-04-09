<?php

namespace ACAT\Document\HTML;

use ACAT\Document\ContentPart;
use DOMDocument;

/**
 *
 */
class HTMLContentPart extends ContentPart {

	/**
	 * @return DOMDocument
	 */
	public function getDomDocument(): DOMDocument {

		if (!$this->domDocument) {
			$this->domDocument = new DOMDocument('1.0', 'utf-8');
			$this->domDocument->loadHTML($this->content);
		}

		return $this->domDocument;

	}

	/**
	 * @return array
	 */
	public function getNamespaces(): array {
		return [];
	}

}