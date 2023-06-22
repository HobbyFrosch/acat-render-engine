<?php

namespace ACAT\Parser\Element;


use ACAT\Utils\DOMUtils;
use DOMNode;

/**
 *
 */
class TableRowBlock extends BlockElement {

	/**
	 * @return DOMNode
	 */
	public function getContextNode() : DOMNode {
		return DOMUtils::getParentNode($this->getEnd(), 'w:tr');
	}

}