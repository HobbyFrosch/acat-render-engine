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

namespace ACAT\Modul\Setting\Template\Model\Placeholder;


use DOMDocument;
use DOMNode;

/**
 * Class WordTextNode
 * @package ACAT\Modul\Setting\Template\Model\Node
 */
class WordTextPlaceholder extends ACatPlaceholder {

	/**
	 * @var string
	 */
	protected string $namespace = 'http://schemas.openxmlformats.org/wordprocessingml/2006/main';

	/**
	 * @var string|null
	 */
	private ?string $text;

	/**
	 * WordTextNode constructor.
	 * @param string|null $text
	 */
	public function __construct(?string $text) {
		$this->text = $text;
	}

	/**
	 * @return string|null
	 */
	public function getText() : ?string {
		return $this->text;
	}

	/**
	 * @return string
	 */
	public function getXMLTagAsString() : string {
		return '<w:t xml:space="preserve">' . $this->text . '</w:t>';
	}

	/**
	 * @param DOMDocument $domDocument
	 * @return DOMNode
	 */
	public function getDOMNode(DOMDocument $domDocument) : DOMNode {

		$elementNode = $domDocument->createElement('w:t');
		$textNode = $domDocument->createTextNode($this->text);

		$preserverAttribute = $domDocument->createAttribute('xml:space');
		$preserverAttribute->value = "preserve";

		$elementNode->appendChild($preserverAttribute);
		$elementNode->appendChild($textNode);

		return $elementNode;

	}

	/**
	 * @return int
	 */
	public function length() : int {
		return strlen($this->getNodeAsString());
	}

	/**
	 * @return string
	 */
	public function getNodeAsString() : string {
		return $this->text;
	}
}