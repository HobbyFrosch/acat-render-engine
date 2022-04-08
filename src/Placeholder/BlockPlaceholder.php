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


use ACAT\App\Exception\AppException;
use DOMDocument;
use DOMNode;

/**
 * Class BlockNode
 * @package ACAT\Modul\Setting\Template\Model\Node
 */
abstract class BlockPlaceholder extends ACatPlaceholder {

	/**
	 * @var int
	 */
	private int $type = 0;

	/**
	 * BlockNode constructor.
	 * @param int $type
	 * @throws AppException
	 */
	public function __construct(int $type) {

		if ($type < 0 || $type > 1) {
			throw new AppException('unsupported block type');
		}

		$this->type = $type;
		parent::__construct();

	}

	/**
	 * @return bool
	 */
	public function isStart() : bool {
		return $this->type == 0;
	}

	/**
	 * @return bool
	 */
	public function isEnd() : bool {
		return $this->type == 1;
	}

	/**
	 * @return string
	 * @throws AppException
	 */
	public function getType() : string {
		if ($this->isStart()) {
			return "start";
		}
		else if ($this->isEnd()) {
			return "end";
		}
		throw new AppException("unsupported block type");
	}

	/**
	 * @return string
	 * @throws AppException
	 */
	public function getXMLTagAsString() : string {
		return '<acat:block type="' . $this->getType() . '" />';
	}

	/**
	 * @param DOMDocument $domDocument
	 * @return DOMNode
	 * @throws AppException
	 */
	public function getDOMNode(DOMDocument $domDocument) : DOMNode {

		$elementNode = $domDocument->createElementNS($this->namespace, 'acat:block');

		$idAttribute = $domDocument->createAttribute('id');
		$idAttribute->value = $this->getId();

		$typeAttribute = $domDocument->createAttribute('type');
		$typeAttribute->value = $this->getType();

		$elementNode->appendChild($idAttribute);
		$elementNode->appendChild($typeAttribute);

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
		return '${B:' . $this->type . '}';
	}
}