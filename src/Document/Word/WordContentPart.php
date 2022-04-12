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
	 *
	 */
	protected string $path;

	/**
	 * @var array|string[]
	 */
	protected array $hierarchy = ['w:t', 'w:r', 'w:p'];

	/**
	 * @param string $path
	 * @param string $content
	 */
	#[Pure]
	public function __construct(string $path, string $content) {
		$this->path = $path;
		parent::__construct($content);
	}

	/**
	 * @return array
	 */
	public function getNamespaces(): array {
		return ParserConstants::$wordNamespaces;
	}

	/**
	 * @return string
	 */
	public function getPath(): string {
		return $this->path;
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