<?php

namespace Tests\Document\Text;

use PHPUnit\Framework\TestCase;
use ACAT\Document\Text\TextDocument;
use ACAT\Document\Text\HtmlDocument;
use ACAT\Exception\DocumentException;

/**
 *
 */
class HtmlDocumentTest extends TestCase {

    /**
     * @test
     *
     * @return void
     * @throws DocumentException
     */
    public function createHTMLDocument() : void {

        $path = __DIR__ . '/test.html';
        $content = file_get_contents($path);

        $textDocument = new HtmlDocument($path);
        $this->assertEquals($content, $textDocument->getContent());

        $content .= " new content";

        $textDocument->setContent($content);
        $textDocument->save();

        $savedContent = file_get_contents($path);
        $this->assertEquals($content, $savedContent);

    }

    /**
     * @test
     *
     * @return void
     */
    public function wrongPath() : void {
        $this->expectException(DocumentException::class);
        new TextDocument('/foo/foo');
    }

}