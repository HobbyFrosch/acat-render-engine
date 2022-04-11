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

namespace ACAT\Modul\Setting\Template\Model\Parser\Condition;

use ACAT\App\Exception\AppException;
use ACAT\App\Util\StringUtils;
use ACAT\Modul\Setting\Template\Model\Document\Element\ConditionElement;

/**
 * Class ConditionParser
 * @package ACAT\Modul\Setting\Template\Model\Condition
 */
class ConditionParser {

	/**
	 * @var array|string[]
	 */
	private array $lex = ['>', '<', '='];

	/**
	 * @var array|string[]
	 */
	private array $operators = ['<>', '=', '<', '>', '>=', '<='];

	/**
	 * @param ConditionElement $conditionElement
	 * @param array $values
	 * @return bool
	 * @throws AppException
	 */
	public function evaluateCondition(ConditionElement $conditionElement, array $values) : bool {

		$fieldValue = null;
		$termValues = $this->getCompareValue($conditionElement->getExpression());

		if (empty($termValues)) {
			throw new AppException('unknown condition ' . $conditionElement->getExpression());
		}

		if (array_key_exists($conditionElement->getFieldId(), $values)) {
			$fieldValue = $values[$conditionElement->getFieldId()];
		}

		$operator = $termValues['operator'];
		$compareValue = $termValues['value'];

		if ($compareValue && StringUtils::startsWith($compareValue, "F")) {
			$compareFieldId = str_replace('F', '', $compareValue);
			if ($compareFieldId && array_key_exists($compareFieldId, $values)) {
				$compareValue = $values[$compareFieldId];
			}
			else {
				$compareValue = null;
			}
		}

		if ($operator === '<>') {
			if (!$compareValue) {
				$termExpression = !empty($fieldValue);
			}
			else {
				$termExpression = ( $compareValue <> $fieldValue );
			}
		}
		else if ($operator === '=') {
			if (!$compareValue) {
				$termExpression = empty($fieldValue);
			}
			else {
				$termExpression = ( $compareValue == $fieldValue );
			}
		}
		else if ($operator === '<') {
			if (!$compareValue) {
				$compareValue = 0;
			}
			$termExpression = ( $fieldValue < $compareValue );
		}
		else if ($operator === '>') {
			if (!$compareValue) {
				$compareValue = 0;
			}
			$termExpression = ( $fieldValue > $compareValue );
		}
		else if ($operator === '>=') {
			if (!$compareValue) {
				$compareValue = 0;
			}
			$termExpression = ( $fieldValue >= $compareValue );
		}
		else if ($operator === '<=') {
			if (!$compareValue) {
				$compareValue = 0;
			}
			$termExpression = ( $fieldValue <= $compareValue );
		}
		else {
			throw new AppException('unknown operator ' . $conditionElement->getExpression());
		}

		return $termExpression;

	}

	/**
	 * @param string $condition
	 * @return array
	 */
	public function getCompareValue(string $condition) : array {

		$term = [];
		$operator = $this->getOperator($condition);

		if (in_array($operator, $this->operators)) {
			$term['operator'] = $operator;
			$value = trim(str_replace($operator, '', $condition));
			if (empty($value)) {
				$term['value'] = null;
			}
			else {
				$term['value'] = $value;
			}
		}

		return $term;

	}

	private function getOperator(string $value) : ?string {

		$operator = null;

		for ($i = 0; $i < strlen($value); $i++) {
			$frac = substr($value,$i, 1);
			if (in_array($frac, $this->lex)) {
				$operator .= $frac;
			}
			else {
				break;
			}
		}

		return $operator;

	}

}