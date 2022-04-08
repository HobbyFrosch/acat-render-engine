<?php

namespace ACAT\Document\Text;

use ACAT\Document\Document;
use ACAT\Exception\DocumentException;

/**
 *
 */
class TextDocument extends Document {

    /**
     * @var string
     */
    private string $path;

    /**
     * @var string
     */
    private string $content;

    /**
     * @param string $path
     * @throws DocumentException
     */
    public function __construct(string $path) {
        if (!is_readable($path) || filesize($path) == 0) {
            throw new DocumentException($path  . ' is not readable');
        }
        $this->path = $path;
        $this->content = file_get_contents($path);
    }

    /**
     * @param string $content
     * @return void
     */
    public function setContent(string $content) : void {
        $this->content = $content;
    }

    /**
     * @return string
     */
    public function getContent(): string {
        return $this->content;
    }

    /**
     * @return void
     * @throws DocumentException
     */
    public function save(): void {
        if (!file_put_contents($this->path, $this->content)) {
            throw new DocumentException($this->path . ' could not be saved');
        }
    }

}