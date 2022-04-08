<?php

namespace ACAT\Document\Word;


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
     * @var DOMXPath
     */
    protected DOMXPath $domXpath;

    /**
     * @var DOMDocument
     */
    protected DOMDocument $domDocument;

    /**
     * @param string $path
     * @param DOMDocument $domDocument
     */
    public function __construct(string $path, DOMDocument $domDocument) {
        $this->path = $path;
        $this->domDocument = $domDocument;
    }

    /**
     * @return string
     */
    public function getContent(): string {
        return $this->content;
    }

    /**
     * @param string $content
     * @return void
     */
    public function setContent(string $content): void {
        $this->content = $content;
    }

}