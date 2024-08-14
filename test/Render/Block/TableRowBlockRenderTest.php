<?php

namespace Tests\Render\Block;

use DOMException;
use ACAT\Exception\RenderException;
use ACAT\Exception\ElementException;
use Tests\Render\AbstractRenderTest;
use ACAT\Parser\Element\TableRowBlock;
use ACAT\Render\Block\TableRowBlockRender;
use ACAT\Exception\ConditionParserException;

/**
 *
 */
class TableRowBlockRenderTest extends AbstractRenderTest
{

    /**
     * @test
     *
     * @return void
     * @throws ElementException
     */
    public function aTableRowBlockRenderCanBeCreated() : void
    {
        $wordTableRowElementGenerator = $this->getWordTableRowElementGenerator();
        $blockElements = $wordTableRowElementGenerator->getBlocks();

        $this->assertCount(1, $blockElements);
        $this->assertInstanceOf(TableRowBlock::class, $blockElements[0]);

        $tableRowBlockRender = new TableRowBlockRender($blockElements[0], []);
        $this->assertInstanceOf(TableRowBlockRender::class, $tableRowBlockRender);
    }

    /**
     * @test
     *
     * @return void
     * @throws ElementException
     * @throws ConditionParserException
     * @throws RenderException
     * @throws DOMException
     */
    public function renderTableRowBlock() : void
    {
        $wordTableRowElementGenerator = $this->getWordTableRowElementGenerator();
        $blockElements = $wordTableRowElementGenerator->getBlocks();

        $this->assertCount(1, $blockElements);
        $this->assertInstanceOf(TableRowBlock::class, $blockElements[0]);

        $tableRowBlockRender = new TableRowBlockRender($blockElements[0], $this->getBlockValues());
        $this->assertInstanceOf(TableRowBlockRender::class, $tableRowBlockRender);

        $tableRowBlockRender->render($blockElements, $this->getBlockValues());

        /* must not exist */
        $startBlock = $wordTableRowElementGenerator->getContentPart()->getXPath()->query('//w:tr[@id="b_start"]');
        $this->assertEquals(0, $startBlock->length);

        /* must not exist */
        $endBlock = $wordTableRowElementGenerator->getContentPart()->getXPath()->query('//w:tr[@id="b_end"]');
        $this->assertEquals(0, $endBlock->length);

        /* there must be exactly two */
        $endBlock = $wordTableRowElementGenerator->getContentPart()->getXPath()->query('//w:tr[@type="b_content"]');
        $this->assertEquals(2, $endBlock->length);

        /* there must be exactly two */
        $endBlock = $wordTableRowElementGenerator->getContentPart()->getXPath()->query('//w:tr[@type="content"]');
        $this->assertEquals(2, $endBlock->length);
    }

    /**
     * @return array
     */
    private function getBlockValues() : array
    {
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

        $values['blocks'][0]['fields'][0]["1909"] = "Kammercard";
        $values['blocks'][0]['fields'][0]["1910"] = "1212321";
        $values['blocks'][0]['fields'][0]["1914"] = 2250;

        $values['blocks'][0]['fields'][1]["1909"] = "Kammercard";
        $values['blocks'][0]['fields'][1]["1910"] = "1212321";
        $values['blocks'][0]['fields'][1]["1914"] = 2250;

        return $values;
    }

    /**
     * @test
     *
     * @return void
     * @throws ConditionParserException
     * @throws DOMException
     * @throws ElementException
     * @throws RenderException
     */
    public function renderTableRowInCorrectSequence() : void
    {
        $expectedSequence = ['b_content', 'b_content', 'content', 'content'];

        $wordTableRowElementGenerator = $this->getWordTableRowElementGenerator();
        $blockElements = $wordTableRowElementGenerator->getBlocks();

        $this->assertCount(1, $blockElements);
        $this->assertInstanceOf(TableRowBlock::class, $blockElements[0]);

        $tableRowBlockRender = new TableRowBlockRender($blockElements[0], $this->getBlockValues());
        $this->assertInstanceOf(TableRowBlockRender::class, $tableRowBlockRender);

        $tableRowBlockRender->render($blockElements, $this->getBlockValues());
        $tableRows = $wordTableRowElementGenerator->getContentPart()->getXPath()->query('//w:tr');

        /* there must be exactly four */
        $this->assertEquals(4, $tableRows->length);

        /* check correct sequence */
        for ($i = 0; $i < $tableRows->length; $i++) {
            $type = $tableRows->item($i)->getAttribute('type');
            $this->assertEquals($expectedSequence[$i], $type);
        }
    }

}