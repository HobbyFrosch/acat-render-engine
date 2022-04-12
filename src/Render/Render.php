<?php

namespace ACAT\Render;

use ACAT\Utils\DOMUtils;
use DOMElement;

/**
 *
 */
abstract class Render {

	/**
	 * @param DOMElement $element
	 * @param DOMElement $wordTextNode
	 */
	public function appendRenderedNode(DOMElement $element, DOMElement $wordTextNode) : void {
		if (!DOMUtils::isRemoved($element)) {
			$element->parentNode->insertBefore($wordTextNode, $element);
		}
	}

	/**
	 * @param DOMElement $element
	 */
	public function deleteNode(DOMElement $element) : void {
		if (!DOMUtils::isRemoved($element)) {
			$element->parentNode->removeChild($element);
		}
	}

	/**
	 * @param array $elements
	 * @param array $values
	 * @return void
	 */
    abstract function render(array $elements, array $values = []) : void;

}