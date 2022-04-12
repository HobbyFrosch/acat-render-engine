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

namespace Test\Template\Model\Document\Render;


use ACAT\App\Exception\AppException;
use ACAT\App\Runtime\Globals;
use ACAT\Modul\Setting\Template\Model\Document\ContentPart;
use ACAT\Modul\Setting\Template\Model\Document\Element\TableRowBlock;
use ACAT\Modul\Setting\Template\Model\Render\TableRowBlockRender;
use Exception;
use PHPUnit\Framework\TestCase;

/**
 * Class TableRowBlockRenderTest
 * @package Test\Template\Model\Document\Render
 */
class TableRowBlockRenderTest extends TestCase {

	/**
	 * @test
	 * @throws AppException
	 * @throws Exception
	 */
	public function aTableRowBlockRenderCanBeCreated() : void {

		$blockElements = $this->getContentPart()->getBlockElements();

		$this->assertCount(1, $blockElements);
		$this->assertInstanceOf(TableRowBlock::class, $blockElements[0]);

		$tableRowBlockRender = new TableRowBlockRender($blockElements[0], []);
		$this->assertInstanceOf(TableRowBlockRender::class, $tableRowBlockRender);

	}

	/**
	 * @test
	 * @throws AppException
	 * @throws Exception
	 */
	public function renderTableRowBlock() : void {

		$contentPart = $this->getContentPart();
		$blockElements = $contentPart->getBlockElements();

		$this->assertCount(1, $blockElements);
		$this->assertInstanceOf(TableRowBlock::class, $blockElements[0]);

		$tableRowBlockRender = new TableRowBlockRender($blockElements[0], $this->getValues());
		$this->assertInstanceOf(TableRowBlockRender::class, $tableRowBlockRender);

		$tableRowBlockRender->render($blockElements, $this->getValues());

		/* must not exist */
		$startBlock = $contentPart->getXPath()->query('//w:tr[@id="b_start"]');
		$this->assertEquals(0, $startBlock->length);

		/* must not exist */
		$endBlock = $contentPart->getXPath()->query('//w:tr[@id="b_end"]');
		$this->assertEquals(0, $endBlock->length);

		/* there must be exactly two */
		$endBlock = $contentPart->getXPath()->query('//w:tr[@type="b_content"]');
		$this->assertEquals(2, $endBlock->length);

		/* there must be exactly two */
		$endBlock = $contentPart->getXPath()->query('//w:tr[@type="content"]');
		$this->assertEquals(2, $endBlock->length);

	}

	/**
	 * @test
	 * @throws AppException
	 * @throws Exception
	 */
	public function renderTableRowInCorrectSequence() : void {

		$expectedSequence = ['b_content', 'b_content', 'content', 'content'];

		$contentPart = $this->getContentPart();
		$blockElements = $contentPart->getBlockElements();

		$this->assertCount(1, $blockElements);
		$this->assertInstanceOf(TableRowBlock::class, $blockElements[0]);

		$tableRowBlockRender = new TableRowBlockRender($blockElements[0], $this->getValues());
		$this->assertInstanceOf(TableRowBlockRender::class, $tableRowBlockRender);

		$tableRowBlockRender->render($blockElements, $this->getValues());
		$tableRows = $contentPart->getXPath()->query('//w:tr');

		/* there must be exactly four */
		$this->assertEquals(4, $tableRows->length);

		/* check correct sequence */
		for ($i = 0; $i < $tableRows->length; $i++) {
			$type = $tableRows->item($i)->getAttribute('type');
			$this->assertEquals($expectedSequence[$i], $type);
		}

	}

	/**
	 * @return ContentPart
	 * @throws AppException
	 */
	private function getContentPart () : ContentPart {

		$testXMLFile = __DIR__ . '/resources/TableRowBlock.xml';

		$xmlContent = file_get_contents($testXMLFile);
		$this->assertIsString($xmlContent);

		$contentPart = new ContentPart($testXMLFile, $xmlContent);
		$this->assertInstanceOf(ContentPart::class, $contentPart);

		return $contentPart;

	}

	/**
	 * @return array
	 */
	private function getValues() : array {

		$values['fields']["rechnung_id"] = 2739;
		$values['fields']["1757"] = null;
		$values['fields']["1744"] = "Frau";
		$values['fields']["1752"] = null;
		$values['fields']["1745"] = "Michaela";
		$values['fields']["1747"] = "H\u00fcneke";
		$values['fields']["1748"] = "Am Edelhof 7a";
		$values['fields']["1749"] = "28832";
		$values['fields']["1750"] = "Achim";
		$values['fields']["1858"] = null;
		$values['fields']["1860"] = null;
		$values['fields']["1862"] = null;
		$values['fields']["1863"] = null;
		$values['fields']["1760"] = null;
		$values['fields']["1761"] = null;
		$values['fields']["1762"] = null;
		$values['fields']["1741"] = null;
		$values['fields']["1742"] = null;
		$values['fields']["1768"] = null;
		$values['fields']["1765"] = "Grundlagen der Medieniformatik 1 [WiSe2020-21]";
		$values['fields']["1921"] = null;
		$values['fields']["1771"] = 45000;
		$values['fields']["1918"] = 1822;
		$values['fields']["1769"] = "2020-10-01";
		$values['fields']["1770"] = "2021-03-31";
		$values['fields']["1772"] = 20047;
		$values['fields']["1780"] = "0";
		$values['fields']["1922"] = null;
		$values['fields']["1783"] = "1";
		$values['fields']["1676"] = "PL - 10098";
		$values['fields']["68"] = 30174;

		$values['blocks'][0][0]["1909"] = "Kammercard";
		$values['blocks'][0][0]["1910"] = "1212321";
		$values['blocks'][0][0]["1914"] = 2250;

		$values['blocks'][0][1]["1909"] = "Kammercard";
		$values['blocks'][0][1]["1910"] = "1212321";
		$values['blocks'][0][1]["1914"] = 2250;

		return $values;

	}

}