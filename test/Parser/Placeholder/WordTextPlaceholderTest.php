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

use ACAT\Modul\Setting\Template\Model\Placeholder\WordTextPlaceholder;
use DOMDocument;
use DOMNode;
use PHPUnit\Framework\TestCase;

/**
 * Class WordTextPlaceholderTest
 * @package Test\Template\Model\Placeholder
 */
class WordTextPlaceholderTest extends TestCase {

	/**
	 * @test
	 */
	public function aNodeCanBeCreated() : void {

		$xmlOutput = '<w:t xml:space="preserve">ff</w:t>';

		$wordNode = new WordTextPlaceholder('ff');

		$this->assertInstanceOf(WordTextPlaceholder::class, $wordNode);
		$this->assertNotNull($wordNode->getText());
		$this->assertIsString($wordNode->getText());
		$this->assertNotNull($wordNode->getXMLTagAsString());
		$this->assertStringContainsString($wordNode->getText(), $wordNode->getXMLTagAsString());

		$domDocument = new DOMDocument('1.0', "UTF-8");

		$node = $wordNode->getDOMNode($domDocument);

		$this->assertInstanceOf(DOMNode::class, $node);
		$this->assertEquals($xmlOutput, $domDocument->saveXML($node));

	}

}