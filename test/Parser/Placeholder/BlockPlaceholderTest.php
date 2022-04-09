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
use ACAT\Modul\Setting\Template\Model\Placeholder\EndBlockPlaceholder;
use ACAT\Modul\Setting\Template\Model\Placeholder\StartBlockPlaceholder;
use DOMDocument;
use DOMNode;
use PHPUnit\Framework\TestCase;

class BlockPlaceholderTest extends TestCase {

	/**
	 * @test
	 * @throws AppException
	 */
	public function aStartBlockNodeCanBeCreated() : void {

		$startBlock = new StartBlockPlaceholder();
		$expectedXmlString = '<acat:block xmlns:acat="http://schemas.acat.akademie.uni-bremen.de" id="' . $startBlock->getId() . '" type="start"/>';

		$this->assertInstanceOf(StartBlockPlaceholder::class, $startBlock);
		$this->assertIsString($startBlock->getType());
		$this->assertEquals('start', $startBlock->getType());

		$domDocument = new DOMDocument('1.0', "UTF-8");

		$node = $startBlock->getDOMNode($domDocument);

		$this->assertInstanceOf(DOMNode::class, $node);
		$this->assertStringContainsString('acat', $node->prefix);

		$this->assertEquals($expectedXmlString, $domDocument->saveXML($node));

	}

	/**
	 * @test
	 * @throws AppException
	 */
	public function aEndBlockNodeCanBeCreated() : void {

		$endBlock = new EndBlockPlaceholder();
		$expectedXmlString = '<acat:block xmlns:acat="http://schemas.acat.akademie.uni-bremen.de" id="' . $endBlock->getId() . '" type="end"/>';

		$this->assertInstanceOf(EndBlockPlaceholder::class, $endBlock);
		$this->assertIsString($endBlock->getType());
		$this->assertEquals('end', $endBlock->getType());

		$domDocument = new DOMDocument('1.0', "UTF-8");

		$node = $endBlock->getDOMNode($domDocument);

		$this->assertInstanceOf(DOMNode::class, $node);
		$this->assertStringContainsString('acat', $node->prefix);

		$this->assertEquals($expectedXmlString, $domDocument->saveXML($node));

	}

}