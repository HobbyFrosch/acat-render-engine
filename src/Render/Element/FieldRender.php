<?php

namespace ACAT\Render\Element;

use ACAT\Render\Render;

/**
 *
 */
class FieldRender extends Render {

	/**
	 * @param FieldElement $fieldElement
	 * @param array $values
	 * @return void
	 */
	public function renderFieldElement(FieldElement $fieldElement, array $values) {

		$fieldId = $fieldElement->getFieldId();

		if ($fieldId) {
			if (array_key_exists($fieldId, $values)) {

				$displayValue = $this->getDisplayValue($fieldId, $values[$fieldId]);
				$wordTextNode = new WordTextPlaceholder($displayValue);

				$this->appendRenderedNode($fieldElement->getElement(), $wordTextNode->getDOMNode($fieldElement->getElement()->ownerDocument));

			}
		}
		else {
			Logging::getFormLogger()->warn($fieldElement->getElement()->nodeName . ' does not contains a field id');
		}

		$fieldElement->delete();

	}

	/**
	 * @param string $fieldId
	 * @param string|null $value
	 * @return string|null
	 * @throws Exception
	 */
	private function getDisplayValue(string $fieldId, ?string $value) : ?string {

		if (empty($fieldId) || empty($value)) {
			return null;
		}

		try {
			$coreFieldModel = CoreFieldModel::getInstanceFromFieldId($fieldId);
			if ($coreFieldModel) {
				$value = $coreFieldModel->getUitypeInstance()->getDisplayValue($value, false, false, true);
			}
		}
		catch (Exception | InvalidArgumentException | CacheException $e) {
			Logging::getFormLogger()->warn($e);
		}

		return $value;

	}

	/**
	 * @param array $elements
	 * @param array $values
	 * @throws AppException
	 */
	public function render(array $elements, array $values = []) : void {
		foreach ($elements as $fieldElement) {
			$this->renderFieldElement($fieldElement, $values);
		}
	}

}