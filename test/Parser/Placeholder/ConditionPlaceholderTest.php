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


use ACAT\App\Exception\AppException;
use ACAT\Modul\Setting\Template\Model\Placeholder\ConditionPlaceholder;
use DOMDocument;
use DOMNode;
use PHPUnit\Framework\TestCase;

/**
 * Class ConditionNodeTest
 * @package Test\Template\Model\Node
 */
class ConditionPlaceholderTest extends TestCase {

	/**
	 * @test
	 */
	public function aConditionNodeCanBeCreated() : void {

		$conditionNode = new ConditionPlaceholder(1, 2, '<>2');
		$xmlOutput = '<acat:condition xmlns:acat="http://schemas.acat.akademie.uni-bremen.de" id="' . $conditionNode->getId() . '" field="' . $conditionNode->getFieldId() . '" action="2"><![CDATA[<>2]]></acat:condition>';

		$this->assertInstanceOf(ConditionPlaceholder::class, $conditionNode);

		$this->assertNotNull($conditionNode->getId());
		$this->assertNotNull($conditionNode->getAction());
		$this->assertNotNull($conditionNode->getExpression());
		$this->assertNotNull($conditionNode->getXMLTagAsString());

		$this->assertIsString($conditionNode->getId());
		$this->assertIsString($conditionNode->getAction());
		$this->assertIsString($conditionNode->getExpression());
		$this->assertIsString($conditionNode->getXMLTagAsString());

		$domDocument = new DOMDocument('1.0', "UTF-8");

		$node = $conditionNode->getDOMNode($domDocument);

		$this->assertInstanceOf(DOMNode::class, $node);
		$this->assertStringContainsString('acat', $node->prefix);

		$this->assertEquals($xmlOutput, $domDocument->saveXML($node));

	}

	/**
	 * @test
	 * @throws AppException
	 */
	public function aConditionNodeCanBeCreateWithWrongAction() : void {
		$this->expectException(AppException::class);
		new ConditionPlaceholder(1, 5, '<>2');
	}

	/**
	 * @test
	 * @throws AppException
	 */
	public function aConditionNodeCanNotBeCreateWithWrongExpression() : void {
		$this->expectException(AppException::class);
		new ConditionPlaceholder(1, 1, '!2');
	}

}