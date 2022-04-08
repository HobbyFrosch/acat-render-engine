<?php

namespace Tests\Document\Text;

use PHPUnit\Framework\TestCase;
use ACAT\Document\Text\TextDocument;
use ACAT\Exception\DocumentException;

/**
 *
 */
class TextDocumentTest extends TestCase {

    /**
     * @test
     *
     * @return void
     * @throws DocumentException
     */
    public function createTextDocument() : void {

        $path = __DIR__ . '/file.txt';
        $content = file_get_contents($path);

        $textDocument = new TextDocument($path);
        $this->assertEquals($content, $textDocument->getContent());

        $content .= "new content";

        $textDocument->setContent($content);
        $textDocument->save();

        $savedContent = file_get_contents($path);
        $this->assertEquals($content, $savedContent);

    }

}