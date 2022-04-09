<?php
/*
 * Copyright (c) 2021 - Akademie für Weiterbildung der Universtät Bremen
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


use ACAT\Modul\Setting\Template\Model\Document\DocumentProtection;
use ACAT\Modul\Setting\Template\Model\Placeholder\WordDocumentProtectionPlaceholder;
use DOMDocument;
use DOMNode;
use PHPUnit\Framework\TestCase;

/**
 * Class WordDocumentProtectionPlaceholderTest
 * @package Test\Template\Model\Placeholder
 */
class WordDocumentProtectionPlaceholderTest extends TestCase {

	/**
	 * @test
	 */
	public function aWordDocumentProtectionPlaceholderCanBeCreated() : void {

		$documentProtection = new DocumentProtection();

		$this->assertInstanceOf(DocumentProtection::class, $documentProtection);
		$this->assertNotNull($documentProtection->getPassword());
		$this->assertNotNull($documentProtection->getSalt());

		$wordDocumentProtectionPlaceholder = new WordDocumentProtectionPlaceholder($documentProtection);
		$this->assertInstanceOf(WordDocumentProtectionPlaceholder::class, $wordDocumentProtectionPlaceholder);

		$this->assertIsString($wordDocumentProtectionPlaceholder->getXMLTagAsString());
		$this->assertIsInt($wordDocumentProtectionPlaceholder->length());
		$this->assertGreaterThan(0, $wordDocumentProtectionPlaceholder->length());

		$domDocument = new DOMDocument('1.0', "UTF-8");
		$domNode = $wordDocumentProtectionPlaceholder->getDOMNode($domDocument);

		$this->assertInstanceOf(DOMNode::class, $domNode);

		$this->assertNotNull($domNode->getAttribute('w:salt'));
		$this->assertNotNull($domNode->getAttribute('w:hash'));
		$this->assertNotNull($domNode->getAttribute('w:edit'));

		$this->assertNotNull($domNode->getAttribute('w:cryptSpinCount'));
		$this->assertEquals(100000, $domNode->getAttribute('w:cryptSpinCount'));

		$this->assertNotNull($domNode->getAttribute('w:cryptAlgorithmSid'));

		$this->assertNotNull($domNode->getAttribute('w:cryptAlgorithmType'));
		$this->assertEquals("typeAny", $domNode->getAttribute('w:cryptAlgorithmType'));

		$this->assertNotNull($domNode->getAttribute('w:cryptAlgorithmClass'));
		$this->assertEquals("hash", $domNode->getAttribute('w:cryptAlgorithmClass'));

		$this->assertNotNull($domNode->getAttribute('w:cryptProviderType'));
		$this->assertEquals("rsaFull", $domNode->getAttribute('w:cryptProviderType'));

		$this->assertNotNull($domNode->getAttribute('w:enforcement'));
		$this->assertEquals("1", $domNode->getAttribute('w:enforcement'));

	}

}