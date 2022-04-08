<?php

namespace ACAT\Document;


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
     * @param string $path
     */
    public function __construct(string $path) {
        $this->path = $path;
    }

    /**
     * @return string
     */
    abstract function getContent() : string;

    /**
     * @param string $content
     * @return void
     */
    abstract function setContent(string $content) : void;

}