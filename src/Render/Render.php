<?php

namespace ACAT\Render;

use ACAT\Utils\DOMUtils;
use DOMElement;
use Psr\Log\LoggerInterface;

/**
 *
 */
abstract class Render {

	/**
	 * @var LoggerInterface|null
	 */
	private ?LoggerInterface $logger;

	/**
	 * @param LoggerInterface|null $logger
	 */
	public function __construct(?LoggerInterface $logger = null) {
		$this->logger = $logger;
	}

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
	 * @param string $level
	 * @param string $message
	 * @return void
	 */
	protected function log(string $level, string $message) {
		$this->logger?->log($level, $message);
	}

	/**
	 * @param array $elements
	 * @param array $values
	 * @return void
	 */
    abstract function render(array $elements, array $values = []) : void;

}