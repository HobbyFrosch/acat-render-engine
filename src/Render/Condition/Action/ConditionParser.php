<?php

namespace ACAT\Render\Condition\Action;

use ACAT\Exception\ConditionParserException;
use ACAT\Exception\ElementException;
use ACAT\Parser\Element\ConditionElement;
use ACAT\Utils\StringUtils;

/**
 *
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
	 * @throws ConditionParserException
	 * @throws ElementException
	 */
	public function evaluateCondition(ConditionElement $conditionElement, array $values) : bool {

		$fieldValue = null;
		$termValues = $this->getCompareValue($conditionElement->getExpression());

		if (empty($termValues)) {
			return false;
			//throw new ConditionParserException('unknown condition ' . $conditionElement->getExpression());
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
			throw new ConditionParserException('unknown operator ' . $conditionElement->getExpression());
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

	/**
	 * @param string $value
	 * @return string|null
	 */
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