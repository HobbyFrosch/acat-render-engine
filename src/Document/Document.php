<?php

namespace ACAT\Document;

use ACAT\Exception\DocumentException;

/**
 *
 */
abstract class Document {

    /**
     * @var string
     */
    protected string $path;

    /**
     * @param string $path
     * @throws DocumentException
     */
    public function __construct(string $path) {
        if (!is_readable($path) || filesize($path) == 0) {
            throw new DocumentException($path  . ' is not readable');
        }
        $this->path = $path;
    }

    /**
     * @return void
     */
    abstract function save() : void;

}