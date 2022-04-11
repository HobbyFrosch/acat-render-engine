<?php

namespace ACAT\Render\Element;

use ACAT\Exception\ElementException;
use ACAT\Exception\RenderException;
use ACAT\Parser\Element\FieldElement;
use ACAT\Parser\Placeholder\WordTextPlaceholder;
use ACAT\Render\Render;
use DOMException;

/**
 *
 */
class FieldRender extends Render {

	/**
	 * @param FieldElement $fieldElement
	 * @param array $values
	 * @return void
	 * @throws RenderException
	 * @throws ElementException|DOMException
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
			throw new RenderException($fieldElement->getElement()->nodeName . ' does not contains a field id');
		}

		$fieldElement->delete();

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