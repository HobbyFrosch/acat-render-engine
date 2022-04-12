<?php

namespace ACAT\Parser\Element;

use ACAT\Exception\ElementException;
use JetBrains\PhpStorm\Pure;

/**
 *
 */
class TextElement extends Element {

	/**
	 * @return string|null
	 */
	#[Pure]
	public function getText() :?string {
		return $this->getElement()->nodeValue;
	}

	/**
	 * @return string
	 * @throws ElementException
	 */
	public function getFieldId() : string {
		throw new ElementException('text element never contains a field id');
	}

}