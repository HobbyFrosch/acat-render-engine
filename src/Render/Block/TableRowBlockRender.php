<?php

namespace ACAT\Render\Block;

use ACAT\Parser\Element\BlockElement;
use ACAT\Utils\DOMUtils;

/**
 *
 */
class TableRowBlockRender extends BlockRender {

	/**
	 * @param BlockElement $blockElement
	 * @param array $values
	 */
	public function __construct(BlockElement $blockElement, array $values) {
        $this->values = $values;
        $this->blockElement = $blockElement;
	}

	/**
	 *
	 */
	public function cleanUpBlock() : void {

		parent::cleanUpBlock();

		$parentEndRow = DOMUtils::getParentNode($this->blockElement->getEnd(), 'w:tr');
		$parentStartRow = DOMUtils::getParentNode($this->blockElement->getStart(), 'w:tr');

		if ($parentEndRow) {
			$parentEndRow->parentNode->removeChild($parentEndRow);
		}
		else {
			$this->blockElement->getEnd()->parentNode->removeChild($this->blockElement->getEnd());
		}

		if ($parentStartRow) {
			$parentStartRow->parentNode->removeChild($parentStartRow);
		}
		else {
			$this->blockElement->getEnd()->parentNode->removeChild($this->blockElement->getStart());
		}

	}
}