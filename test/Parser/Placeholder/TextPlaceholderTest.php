<?php
/*
 * Copyright (c) 2020 - Akademie für Weiterbildung der Universtät Bremen
 *
 * The contents of this file are subject to the vtiger CRM Public License Version 1.0
 * ("License"); You may not use this file except in compliance with the License
 * The Original Code is:  vtiger CRM Open Source
 * The Initial Developer of the Original Code is vtiger.
 * Portions created by vtiger are Copyright (C) vtiger.
 * All Rights eserved.
 * reviewed and modified by Akademie für Weiterbildung der Universtät Bremen
 */

namespace Test\Template\Model\Placeholder;

use ACAT\Modul\Setting\Template\Model\Placeholder\TextPlaceholder;
use DOMDocument;
use DOMNode;
use PHPUnit\Framework\TestCase;

/**
 * Class TextNodeTest
 * @package Test\Template\Model\Node
 */
class TextPlaceholderTest extends TestCase {

	/**
	 * @test
	 */
	public function aTextNodeCanBeCreated() : void {

		$text = 'Ich bin der krasse Text';
		$textNode = new TextPlaceholder($text);

		$expectedXmlString = '<acat:text xmlns:acat="http://schemas.acat.akademie.uni-bremen.de" id="' . $textNode->getId() . '" space="preserve">' . $text . '</acat:text>';

		$this->assertInstanceOf(TextPlaceholder::class, $textNode);
		$this->assertNotNull($textNode->getText());
		$this->assertNotNull($textNode->getXMLTagAsString());
		$this->assertIsString($textNode->getXMLTagAsString());
		$this->assertStringContainsString($text, $textNode->getXMLTagAsString());

		$domDocument = new DOMDocument('1.0', "UTF-8");

		$node = $textNode->getDOMNode($domDocument);

		$this->assertInstanceOf(DOMNode::class, $node);
		$this->assertStringContainsString('acat', $node->prefix);

		$this->assertEquals($expectedXmlString, $domDocument->saveXML($node));

	}
}