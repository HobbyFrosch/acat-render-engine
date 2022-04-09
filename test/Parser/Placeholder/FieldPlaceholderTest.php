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

use ACAT\Modul\Setting\Template\Model\Placeholder\FieldPlaceholder;
use DOMDocument;
use DOMNode;
use PHPUnit\Framework\TestCase;

/**
 * Class FieldNodeTest
 * @package Test\Template\Model\Node
 */
class FieldPlaceholderTest extends TestCase {

	/**
	 * @test
	 */
	public function aFieldNodeCanBeCreated() : void {

		$fieldNode = new FieldPlaceholder('12');
		$xmlOutput = '<acat:field xmlns:acat="http://schemas.acat.akademie.uni-bremen.de" id="' . $fieldNode->getId() . '" field="12"/>';

		$this->assertInstanceOf(FieldPlaceholder::class, $fieldNode);
		$this->assertNotNull($fieldNode->getId());
		$this->assertNotNull($fieldNode->getXMLTagAsString());
		$this->assertIsString($fieldNode->getXMLTagAsString());

		$this->assertEquals('${F:12}', $fieldNode->getNodeAsString());

		$domDocument = new DOMDocument('1.0', "UTF-8");

		$node = $fieldNode->getDOMNode($domDocument);

		$this->assertInstanceOf(DOMNode::class, $node);
		$this->assertStringContainsString('acat', $node->prefix);

		$this->assertEquals($xmlOutput, $domDocument->saveXML($node));

	}

}