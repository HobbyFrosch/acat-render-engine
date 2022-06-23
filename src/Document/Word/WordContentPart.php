<?php

namespace ACAT\Document\Word;

use ACAT\Document\ContentPart;
use ACAT\Parser\ParserConstants;
use JetBrains\PhpStorm\Pure;

/**
 *
 */
class WordContentPart extends ContentPart {

	/**
	 * @var array|string[]
	 */
	protected array $hierarchy = ['w:t', 'w:r', 'w:p'];

	/**
	 * @return array
	 */
	public function getNamespaces(): array {
		return ParserConstants::$wordNamespaces;
	}

	/**
	 * @return array
	 */
	public function getHierarchy(): array {
		return $this->hierarchy;
	}

	/**
	 * @return string
	 */
	public function getContent(): string {
		return $this->getDomDocument()->saveXML();
	}

}