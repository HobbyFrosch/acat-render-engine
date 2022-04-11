<?php

namespace ACAT\Parser\Element;

use ACAT\Exception\ElementException;
use ACAT\Utils\DOMUtils;
use DOMElement;

/**
 *
 */
abstract class Element {

	/**
	 * @var bool
	 */
	protected bool $blockElement;

	/**
	 * @var DOMElement
	 */
	protected DOMElement $element;

	/**
	 * @param DOMElement $element
	 * @param bool $blockElement
	 */
	public function __construct(DOMElement $element, bool $blockElement = false) {
		$this->element = $element;
		$this->blockElement = $blockElement;
	}

	/**
	 * @return DOMElement
	 */
	public function getElement() : DOMElement {
		return $this->element;
	}

	/**
	 * @return string
	 * @throws ElementException
	 */
	public function getFieldId() : string {
		$fieldId = $this->getAttributeValue('field');
		if (empty($fieldId)) {
			throw new ElementException($this->element->nodeName . ' does not contains a field id');
		}
		return $fieldId;
	}

	/**
	 * @return string
	 * @throws ElementException
	 */
	public function getId() : string {
		$id = $this->getAttributeValue('id');
		if (empty($id)) {
			throw new ElementException($this->element->nodeName . ' does not contains a field id');
		}
		return $id;
	}

	/**
	 * @param string $attribute
	 * @return string|null
	 */
	protected function getAttributeValue(string $attribute) : ?string {
		if ($this->element->hasAttribute($attribute)) {
			return $this->element->getAttribute($attribute);
		}
		return null;
	}

	/**
	 *
	 */
	public function delete() : void {
		if (!DOMUtils::isRemoved($this->element)) {
			$this->element->parentNode->removeChild($this->element);
		}
	}

}