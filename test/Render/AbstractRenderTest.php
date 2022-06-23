<?php

namespace Tests\Render;

use ACAT\Document\Word\WordContentPart;
use ACAT\Document\Word\WordDocument;
use ACAT\Exception\DocumentException;
use ACAT\Exception\ElementException;
use ACAT\Parser\Element\ElementGenerator;
use ACAT\Parser\Element\Word\WordElementGenerator;
use Monolog\Formatter\LineFormatter;
use Monolog\Handler\StreamHandler;
use Monolog\Logger;
use Monolog\Processor\UidProcessor;
use PHPUnit\Framework\TestCase;
use Psr\Log\LoggerInterface;

/**
 *
 */
class AbstractRenderTest extends TestCase {

	/**
	 * @test
	 *
	 * @return WordContentPart
	 */
	public function getWordContentPart(): WordContentPart {

		$testXMLFile = __DIR__ . '/Resources/document.xml';

		$xmlContent = file_get_contents($testXMLFile);
		$this->assertIsString($xmlContent);

		return new WordContentPart($xmlContent, $testXMLFile);

	}

	/**
	 * @return array
	 */
	protected function getValues(): array {

		$values['156'] = 156;
		$values['1121'] = 1121;
		$values['1744'] = 1744;
		$values['1745'] = 1745;
		$values['1747'] = 1747;
		$values['1748'] = 1748;

		return $values;

	}

	/**
	 * @return WordElementGenerator
	 * @throws ElementException
	 */
	public function getWordElementGenerator(): WordElementGenerator {
		$wordContentPart = $this->getWordContentPart();
		return ElementGenerator::getInstance($wordContentPart);
	}

	/**
	 * @return WordContentPart
	 */
	private function getParagraphBlockContentPart(): WordContentPart {

		$testXMLFile = __DIR__ . '/Resources/ParagraphBlock.xml';

		$xmlContent = file_get_contents($testXMLFile);
		$this->assertIsString($xmlContent);

		$contentPart = new WordContentPart($xmlContent, $testXMLFile);
		$this->assertInstanceOf(WordContentPart::class, $contentPart);

		return $contentPart;

	}

	/**
	 * @return WordElementGenerator
	 * @throws ElementException
	 */
	public function getWordParagraphElementGenerator() : WordElementGenerator {
		return ElementGenerator::getInstance($this->getParagraphBlockContentPart());
	}

	/**
	 * @return WordContentPart
	 */
	private function getTableRowBlockContentPart(): WordContentPart {

		$testXMLFile = __DIR__ . '/Resources/TableRowBlock.xml';

		$xmlContent = file_get_contents($testXMLFile);
		$this->assertIsString($xmlContent);

		$contentPart = new WordContentPart($xmlContent, $testXMLFile);
		$this->assertInstanceOf(WordContentPart::class, $contentPart);

		return $contentPart;

	}

	/**
	 * @return WordElementGenerator
	 * @throws ElementException
	 */
	public function getWordTableRowElementGenerator() : WordElementGenerator {
		return ElementGenerator::getInstance($this->getTableRowBlockContentPart());
	}

	/**
	 * @return WordContentPart
	 */
	protected function getConditionContentPart(): WordContentPart {

		$testXMLFile = __DIR__ . '/Resources/Conditions.xml';

		$xmlContent = file_get_contents($testXMLFile);
		$this->assertIsString($xmlContent);

		$contentPart = new WordContentPart($xmlContent, $testXMLFile);
		$this->assertInstanceOf(WordContentPart::class, $contentPart);

		return $contentPart;

	}

	/**
	 * @return WordElementGenerator
	 * @throws ElementException
	 */
	public function getConditionElementGenerator() : WordElementGenerator {
		return ElementGenerator::getInstance($this->getConditionContentPart());
	}

	/**
	 * @return WordDocument
	 * @throws DocumentException
	 */
	protected function getWordDocument() : WordDocument {

		$currentDocument = __DIR__ . "/resources/Rechnung_not_empty_lck.docx";
		copy(__DIR__ . '/resources/Rechnung.docx', $currentDocument);

		$wordDocument = new WordDocument($currentDocument);
		$this->assertInstanceOf(WordDocument::class, $wordDocument);

		return $wordDocument;

	}

	/**
	 * @return array
	 */
	protected function getData() : array {
		return json_decode('{"word/document.xml":{"fields":{"68":"30804","1676":"PL - 10155","1741":"4200004","1742":"17.05.2022","1744":"","1745":"Box56ler","1747":"Petra","1748":"","1749":"","1750":"","1752":"","1757":"","1760":"","1761":"","1762":"","1765":"DEMO Process Programm Master","1768":"","1769":"01.06.2022","1770":"31.05.2023","1771":"5.600,00 €","1772":"5.600,00 €","1780":"x","1783":"","1858":"","1860":"","1862":"","1863":"","1868":"<a href=\"index.php?module=rechnung&view=Detail&record=\"></a>","1869":"<a href=\"index.php?module=rechnung&view=Detail&record=\"></a>","1918":"0,00 €","1921":"","1922":null,"1923":"0,00 €","1964":"","1965":"","rechnung_id":5156},"blocks":{"2":{"fields":[{"1772":"5.600,00 €","1786":"01.06.2022","1787":"5.600,00 €","rechnung_id":5156}]}}},"customXml/itemProps1.xml":{"fields":{"rechnung_id":5156}},"word/numbering.xml":{"fields":{"rechnung_id":5156}},"word/styles.xml":{"fields":{"rechnung_id":5156}},"word/settings.xml":{"fields":{"rechnung_id":5156}},"word/webSettings.xml":{"fields":{"rechnung_id":5156}},"word/footnotes.xml":{"fields":{"rechnung_id":5156}},"word/endnotes.xml":{"fields":{"rechnung_id":5156}},"word/header1.xml":{"fields":{"rechnung_id":5156}},"word/header2.xml":{"fields":{"rechnung_id":5156}},"word/footer1.xml":{"fields":{"rechnung_id":5156}},"word/footer2.xml":{"fields":{"rechnung_id":5156}},"word/header3.xml":{"fields":{"478":"Ronny","479":"Krämer","481":"ronny.kraemer@uni-bremen.de","489":"+49 421 218 61629","490":"3.030","495":null,"501":"Mary-Somerville Str. 3","rechnung_id":5156}},"word/footer3.xml":{"fields":{"rechnung_id":5156}},"word/fontTable.xml":{"fields":{"rechnung_id":5156}},"word/webextensions/taskpanes.xml":{"fields":{"rechnung_id":5156}},"word/webextensions/webextension1.xml":{"fields":{"rechnung_id":5156}},"word/theme/theme1.xml":{"fields":{"rechnung_id":5156}},"docProps/core.xml":{"fields":{"rechnung_id":5156}},"docProps/app.xml":{"fields":{"rechnung_id":5156}}}', "JSON_OBJECT_AS_ARRAY");
	}

	public function getLogger() : LoggerInterface {

		$name = 'acat-render-engine';
		$path = __DIR__ . '/../logs/acat-render-engine.log';
		$level = Logger::DEBUG;

		$logger = new Logger($name);

		$dateFormat = "d.m.Y - H:i:s";
		$output = "[%datetime%] %channel%.%level_name%: %message% \n";

		$formatter = new LineFormatter($output, $dateFormat, true);
		$formatter->includeStacktraces(true);

		$processor = new UidProcessor();
		$logger->pushProcessor($processor);

		$handler = new StreamHandler($path, $level);
		$handler->setFormatter($formatter);

		$logger->pushHandler($handler);

		return $logger;


	}

}