<?php

namespace ACAT\Document\Word;

use ACAT\Document\ContentPart;
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
	 * @var array
	 */
	public array $namespaces = [
		'wpc'   => 'https://schemas.microsoft.com/office/word/2010/wordprocessingCanvas',
		'cx'    => 'https://schemas.microsoft.com/office/drawing/2014/chartex',
		'mc'    => 'https://schemas.openxmlformats.org/markup-compatibility/2006',
		'o'     => 'urn:schemas-microsoft-com:office:office',
		'r'     => 'https://schemas.openxmlformats.org/officeDocument/2006/relationships',
		'm'     => 'https://schemas.openxmlformats.org/officeDocument/2006/math',
		'v'     => 'urn:schemas-microsoft-com:vml',
		'w'     => 'https://schemas.openxmlformats.org/wordprocessingml/2006/main',
		'wp14'  => 'https://schemas.microsoft.com/office/word/2010/wordprocessingDrawing',
		'wp'    => 'https://schemas.openxmlformats.org/drawingml/2006/wordprocessingDrawing',
		'w10'   => 'urn:schemas-microsoft-com:office:word',
		'w14'   => 'https://schemas.microsoft.com/office/word/2010/wordml',
		'w15'   => 'https://schemas.microsoft.com/office/word/2012/wordml',
		'w16se' => 'https://schemas.microsoft.com/office/word/2015/wordml/symex',
		'wpg'   => 'https://schemas.microsoft.com/office/word/2010/wordprocessingGroup',
		'wpi'   => 'https://schemas.microsoft.com/office/word/2010/wordprocessingInk',
		'wne'   => 'https://schemas.microsoft.com/office/word/2006/wordml',
		'wps'   => 'https://schemas.microsoft.com/office/word/2010/wordprocessingShape',
		'a'     => 'https://schemas.openxmlformats.org/drawingml/2006/main',
		'acat'  => 'https://schemas.acat.akademie.uni-bremen.de'
	];

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
		return $this->namespaces;
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