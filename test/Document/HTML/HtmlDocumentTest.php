<?php

namespace Tests\Document\HTML;

use ACAT\Document\HTML\HTMLDocument;
use ACAT\Exception\DocumentException;
use DOMDocument;
use DOMXPath;
use PHPUnit\Framework\TestCase;

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
		$htmlDocument = new HTMLDocument($path);

		$this->assertInstanceOf(HTMLDocument::class, $htmlDocument);
		$this->assertInstanceOf(DOMXPath::class, $htmlDocument->getXPath());
		$this->assertInstanceOf(DOMDocument::class, $htmlDocument->getDomDocument());

    }

    /**
     * @test
     *
     * @return void
     */
    public function wrongPath() : void {
        $this->expectException(DocumentException::class);
        new HTMLDocument('/foo/foo');
    }

}