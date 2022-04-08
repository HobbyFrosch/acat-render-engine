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
 * Class TextNode
 * @package ACAT\Modul\Setting\Template\Model\Node
 */
class TextPlaceholder extends ACatPlaceholder {

	/**
	 * @var string|null
	 */
	private ?string $text;

	/**
	 * TextNode constructor.
	 * @param string|null $text
	 */
	public function __construct(?string $text) {
		$this->text = $text;
		parent::__construct();
	}

	/**
	 * @return string|null
	 */
	public function getText() : ?string {
		return $this->text;
	}

	/**
	 * @param string|null $text
	 */
	public function setText(?string $text) : void {
		$this->text = $text;
	}

	/**
	 * @return string
	 */
	public function getXMLTagAsString() : string {
		return '<acat:text space="preserve">' . $this->text . '</w:t>';
	}

	/**
	 * @param DOMDocument $domDocument
	 * @return DOMNode
	 */
	public function getDOMNode(DOMDocument $domDocument) : DOMNode {

		$elementNode = $domDocument->createElementNS($this->namespace, 'acat:text');
		$textNode = $domDocument->createTextNode($this->text);

		$idAttribute = $preserverAttribute = $domDocument->createAttribute('id');
		$idAttribute->nodeValue = $this->id;

		$preserverAttribute = $domDocument->createAttribute('space');
		$preserverAttribute->value = "preserve";

		$elementNode->appendChild($idAttribute);
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
		return '${T:' . $this->text . '}';
	}

}