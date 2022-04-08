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
use DOMException;
use DOMNode;

/**
 * Class FieldNode
 * @package ACAT\Modul\Setting\Template\Model
 */
class FieldPlaceholder extends ACatPlaceholder {

	/**
	 * @var int
	 */
	protected int $fieldId;

	/**
	 * FieldNode constructor.
	 * @param int $fieldId
	 */
	public function __construct(int $fieldId) {
		parent::__construct();
		$this->fieldId = $fieldId;
	}

	/**
	 * @return int
	 */
	public function getFieldId() : int {
		return $this->fieldId;
	}

    /**
     * @param DOMDocument $domDocument
     * @return DOMNode
     * @throws DOMException
     */
	public function getDOMNode(DOMDocument $domDocument) : DOMNode {

		$elementNode = $domDocument->createElementNS($this->namespace, 'acat:field');

		$idAttribute = $domDocument->createAttribute('id');
		$idAttribute->value = $this->getId();

		$fieldAttribute = $domDocument->createAttribute('field');
		$fieldAttribute->value = $this->fieldId;

		$elementNode->appendChild($idAttribute);
		$elementNode->appendChild($fieldAttribute);

		return $elementNode;

	}

	/**
	 * @return string
	 */
	public function getXMLTagAsString() : string {
		return "<acat:field id" . $this->getId() . " field=" . $this->fieldId . "/>";
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
		return '${F:' . $this->fieldId . '}';
	}

}