<?php

namespace ACAT\Render\Condition\Action;


use ACAT\Exception\RenderException;
use ACAT\Utils\DOMUtils;

/**
 *
 */
class DeleteUntilNextElementAction extends ConditionAction {

	/**
	 * @var array|string[]
	 */
	private array $validNodeNames = ['acat:condition', 'acat:field', 'acat:text', 'acat:view'];

	/**
	 * @return void
	 * @throws RenderException
	 */
	public function execute() : void {
		foreach ($this->getNodesToDelete() as $nodeToDelete) {
			$nodeToDelete->parentNode->removeChild($nodeToDelete);
		}
	}

	/**
	 * @return array
	 * @throws RenderException
	 */
	private function getNodesToDelete() : array {

		$count = 0;
		$found = false;
		$nodesToDelete = [];

		$runNode = DOMUtils::getParentNode($this->conditionElement->getElement(), 'w:r');

		if (!$runNode) {
			throw new RenderException('malformed content part');
		}

		$runNodes = $this->conditionElement->getXPath()->query('self::*|following-sibling::*', $runNode);

		foreach ($runNodes as $runNode) {
			$nodes = $this->conditionElement->getXPath()->query('child::*', $runNode);
			foreach ($nodes as $node) {
				if ($node->isSameNode($this->conditionElement->getElement())) {
					$found = true;
				}
				else if ($found) {
					if (in_array($node->nodeName, $this->validNodeNames)) {
						if ($count == 0) {
							$nodesToDelete[] = $node;
							$count = 1;
						}
						else if ($count == 1) {
							return $nodesToDelete;
						}
					}
					else {
						$nodesToDelete[] = $node;
					}
				}
			}
		}

		return $nodesToDelete;

	}
}