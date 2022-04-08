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
use ACAT\App\Util\StringUtils;
use DOMDocument;
use DOMNode;

/**
 * Class ConditionNode
 * @package ACAT\Modul\Setting\Template\Model
 */
class ConditionPlaceholder extends ACatPlaceholder {

	/**
	 * @var string
	 */
	private string $fieldId;

	/**
	 * @var string
	 */
	private string $action;

	/**
	 * @var string
	 */
	private string $expression;

	/**
	 * @var array|string[]
	 */
	private array $availableExpressions = ['>', '<', '=', '<>'];

	/**
	 * @var array|int[]
	 */
	private array $availableActions = [0, 1, 2, 3, 4];

	/**
	 * ConditionNode constructor.
	 * @param $fieldId
	 * @param $action
	 * @param $expression
	 * @throws AppException
	 */
	public function  __construct($fieldId, $action, $expression) {

		if (empty($fieldId) || empty($expression)) {
			throw new AppException("invalid condition node {$fieldId} {$action} {$expression}");
		}

		$this->fieldId = $fieldId;
		$this->setAction($action);
		$this->setExpression($expression);

		parent::__construct();

	}

	/**
	 * @return string
	 */
	public function getFieldId() : string {
		return $this->fieldId;
	}

	/**
	 * @return string
	 */
	public function getAction() : string {
		return $this->action;
	}

	/**
	 * @param string $action
	 * @throws AppException
	 */
	public function setAction(string $action) : void {

		if (!in_array($action, $this->availableActions)) {
			throw new AppException(  "action $action is not implemented");
		}

		$this->action = $action;

	}

	/**
	 * @return string
	 */
	public function getExpression() : string {
		return $this->expression;
	}

	/**
	 * @param string $expression
	 * @throws AppException
	 */
	public function setExpression(string $expression) : void {

		foreach ($this->availableExpressions as $availableExpression) {
			if (StringUtils::contains($expression, $availableExpression)) {
				$this->expression = $expression;
				return;
			}
		}

		throw new AppException('expression ' . $expression . ' is not implemented');

	}

	/**
	 * @param DOMDocument $domDocument
	 * @return DOMNode
	 */
	public function getDOMNode(DOMDocument $domDocument) : DOMNode {

		$elementNode = $domDocument->createElementNS($this->namespace, 'acat:condition');
		$expressionNode = $domDocument->createCDATASection($this->expression);

		$idAttribute = $domDocument->createAttribute('id');
		$idAttribute->value = $this->getId();

		$fieldAttribute = $domDocument->createAttribute('field');
		$fieldAttribute->value = $this->getFieldId();

		$actionAttribute = $domDocument->createAttribute('action');
		$actionAttribute->value = $this->getAction();

		$elementNode->appendChild($idAttribute);
		$elementNode->appendChild($fieldAttribute);
		$elementNode->appendChild($actionAttribute);
		$elementNode->appendChild($expressionNode);

		return $elementNode;

	}

	/**
	 * @param string|null $prefix
	 * @return string
	 */
	public function getXMLTagAsString(string $prefix = 'acat') : string {

		$tag = "<acat:condition field=" . $this->getFieldId() . " id=" . $this->getId() . " action= " . $this->action . ">";
		$tag .= $this->expression;
		$tag .= "/>";

		return $tag;

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
		return '${C:' . $this->fieldId . ':' . $this->expression . ':' . $this->action . '}';
	}

}