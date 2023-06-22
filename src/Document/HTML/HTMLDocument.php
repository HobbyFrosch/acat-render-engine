<?php

namespace ACAT\Document\HTML;

use ACAT\Document\Document;
use ACAT\Document\MarkupDocument;
use ACAT\Exception\DocumentException;
use DOMDocument;
use DOMXPath;
use JetBrains\PhpStorm\Pure;
use phpDocumentor\Reflection\Utils;

/**
 *
 */
class HTMLDocument extends MarkupDocument {

	/**
	 * @var HTMLContentPart
	 */
	private HTMLContentPart $contentPart;

	/**
	 * @param string $path
	 * @throws DocumentException
	 */
	public function __construct(string $path) {
		parent::__construct($path);
		$this->contentPart = new HTMLContentPart(file_get_contents($path));
	}

	/**
	 * @return DOMDocument
	 */
	public function getDomDocument() : DOMDocument {
		return $this->contentPart->getDomDocument();
	}

	/**
	 * @return DOMXPath
	 */
	public function getXPath() : DOMXPath {
		return $this->contentPart->getXPath();
	}

	/**
	 * @param string $content
	 * @return void
	 */
	public function setContent(string $content): void {
		$this->contentPart->setContent($content);
	}

	/**
	 * @return string
	 * @throws DocumentException
	 */
	public function getContent() : string {
		if (!$content = $this->contentPart->getDomDocument()->saveHTML()) {
			throw new DocumentException($this->path . ' is not a valid html document');
		}
		return $content;
	}

	/**
	 * @return void
	 * @throws DocumentException
	 */
	public function save(): void {
		if (!file_put_contents($this->path, $this->getContent())) {
			throw new DocumentException($this->path . ' could not be saved');
		}
	}

	/**
	 * @return HTMLContentPart
	 */
	public function getContentPart(): HTMLContentPart {
		return $this->contentPart;
	}

	/**
	 * @return array
	 */
	#[Pure]
	public function getContentParts(): array {
		return [$this->contentPart];
	}
}