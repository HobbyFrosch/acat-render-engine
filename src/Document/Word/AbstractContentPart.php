<?php

namespace  ACAT\Document;

use DOMDocument;
use DOMXPath;

/**
 *
 */
abstract class ACatDocument {

	/**
	 * @var DOMDocument|null
	 */
	protected ?DOMDocument $domDocument = null;

	/**
	 * @var DOMXPath|null
	 */
	protected ?DOMXPath $domXPath = null;

	/**
	 * @var array
	 */
	public array $namespaces = [
		'wpc'   => 'http://schemas.microsoft.com/office/word/2010/wordprocessingCanvas',
		'cx'    => 'http://schemas.microsoft.com/office/drawing/2014/chartex',
		'mc'    => 'http://schemas.openxmlformats.org/markup-compatibility/2006',
		'o'     => 'urn:schemas-microsoft-com:office:office',
		'r'     => 'http://schemas.openxmlformats.org/officeDocument/2006/relationships',
		'm'     => 'http://schemas.openxmlformats.org/officeDocument/2006/math',
		'v'     => 'urn:schemas-microsoft-com:vml',
		'w'		=> 'http://schemas.openxmlformats.org/wordprocessingml/2006/main',
		'wp14'  => 'http://schemas.microsoft.com/office/word/2010/wordprocessingDrawing',
		'wp'    => 'http://schemas.openxmlformats.org/drawingml/2006/wordprocessingDrawing',
		'w10'   => 'urn:schemas-microsoft-com:office:word',
		'w14'   => 'http://schemas.microsoft.com/office/word/2010/wordml',
		'w15'   => 'http://schemas.microsoft.com/office/word/2012/wordml',
		'w16se' => 'http://schemas.microsoft.com/office/word/2015/wordml/symex',
		'wpg'   => 'http://schemas.microsoft.com/office/word/2010/wordprocessingGroup',
		'wpi'   => 'http://schemas.microsoft.com/office/word/2010/wordprocessingInk',
		'wne'   => 'http://schemas.microsoft.com/office/word/2006/wordml',
		'wps'   => 'http://schemas.microsoft.com/office/word/2010/wordprocessingShape',
		'a'     => 'http://schemas.openxmlformats.org/drawingml/2006/main',
		'acat'	=> 'http://schemas.acat.akademie.uni-bremen.de'
	];

	/**
	 * @param string $content
	 * @return DOMDocument
	 * @throws AppException
	 */
	protected function getDomDocument(string $content) : DOMDocument {

		if (empty($content)) {
			throw new AppException('invalid content');
		}

		if ($this->domDocument) {
			return $this->domDocument;
		}

		$this->domDocument = new DOMDocument('1.0', 'utf-8');
		$this->domDocument->loadXML($content);

		return $this->domDocument;

	}

	/**
	 * @return DOMXPath
	 */
	protected function getXPath() : DOMXPath {

		if ($this->domXPath) {
			return $this->domXPath;
		}

		$this->domXPath = new DOMXPath($this->domDocument);

		foreach ($this->namespaces as $prefix => $url) {
			$this->domXPath->registerNamespace($prefix, $url);
		}

		return $this->domXPath;

	}
}