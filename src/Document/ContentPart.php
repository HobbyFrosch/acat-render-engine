<?php

namespace ACAT\Document;

use DOMXPath;
use DOMDocument;

/**
 *
 */
abstract class ContentPart {

	/**
	 * @var string
	 */
	protected string $path;

    /**
     * @var string
     */
    protected string $content;

	/**
	 * @var array
	 */
	protected array $namespaces = [];

	/**
	 * @var DOMXPath|null
	 */
    protected ?DOMXPath $domXpath = null;

	/**
	 * @var DOMDocument|null
	 */
    protected ?DOMDocument $domDocument = null;

	/**
	 * @param string $path
	 * @param string $content
	 */
	public function __construct(string $content, string $path = "") {
		$this->path = $path;
		$this->content = $content;
	}

	/**
     * @return DOMXPath
     */
    public function getXPath(): DOMXPath {

        if (!$this->domXpath) {
            $this->domXpath = new DOMXPath($this->getDomDocument());
            foreach ($this->getNamespaces() as $prefix => $url) {
                $this->domXpath->registerNamespace($prefix, $url);
            }
        }

        return $this->domXpath;

    }

    /**
     * @return DOMDocument
     */
    public function getDomDocument(): DOMDocument {

        if (!$this->domDocument) {
            $this->domDocument = new DOMDocument('1.0', 'utf-8');
            $this->domDocument->loadXML($this->content);
        }

        return $this->domDocument;

    }

    /**
     * @param string $content
     * @return void
     */
    public function setContent(string $content): void {
        $this->content = $content;
	    $this->domDocument = new DOMDocument('1.0', 'utf-8');
	    $this->domDocument->loadXML($this->content);
    }

	/**
	 * @return string
	 */
	public function getPath(): string {
		return $this->path;
	}

	/**
	 * @return string
	 */
	abstract function getContent() : string;

    /**
     * @return array
     */
    abstract function getNamespaces() : array;

}