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
	 * @return WordDocument
	 * @throws DocumentException
	 */
	protected function getZulassung() : WordDocument {

		$currentDocument = __DIR__ . "/resources/Zulassung_not_empty_lck.docx";
		copy(__DIR__ . '/resources/Zulassung.docx', $currentDocument);

		$wordDocument = new WordDocument($currentDocument);
		$this->assertInstanceOf(WordDocument::class, $wordDocument);

		return $wordDocument;

	}

	/**
	 * @return array
	 */
	protected function getData() : array {
		return json_decode('{"word/document.xml":{"fields":{"68":"30860","1676":"PL - 10150","1741":"4200026","1742":"20.07.2022","1744":"Herr","1745":"Jörg","1747":"Knop","1748":"Hofstraße 39D","1749":"33607","1750":"Bielefeld","1752":null,"1757":"","1760":null,"1761":null,"1762":null,"1765":"Weiterbildendes Studium mit Zertifikatsabschluss „Arbeitsbezogene Beratung“ 2022/2023","1768":"85511013","1769":"01.09.2022","1770":"31.08.2023","1771":"5.600,00","1772":"5.600,00","1780":true,"1783":"3","1858":"","1860":null,"1862":null,"1863":null,"1868":"","1869":"","1918":0,"1921":null,"1922":null,"1923":0,"1964":null,"1965":null,"rechnung_id":5467},"blocks":{"3":{"fields":[{"1772":"5.600,00","1786":"01.09.2022","1787":"1.400,00","rechnung_id":5467},{"1772":"5.600,00","1786":"01.12.2022","1787":"1.400,00","rechnung_id":5467},{"1772":"5.600,00","1786":"01.03.2023","1787":"1.400,00","rechnung_id":5467},{"1772":"5.600,00","1786":"01.06.2023","1787":"1.400,00","rechnung_id":5467}]}}},"customXml/itemProps1.xml":{"fields":{"rechnung_id":5467}},"word/numbering.xml":{"fields":{"rechnung_id":5467}},"word/styles.xml":{"fields":{"rechnung_id":5467}},"word/settings.xml":{"fields":{"rechnung_id":5467}},"word/webSettings.xml":{"fields":{"rechnung_id":5467}},"word/footnotes.xml":{"fields":{"rechnung_id":5467}},"word/endnotes.xml":{"fields":{"rechnung_id":5467}},"word/header1.xml":{"fields":{"rechnung_id":5467}},"word/header2.xml":{"fields":{"rechnung_id":5467}},"word/footer1.xml":{"fields":{"rechnung_id":5467}},"word/footer2.xml":{"fields":{"rechnung_id":5467}},"word/header3.xml":{"fields":{"478":"Marlis","479":"Glomba","481":"mglomba@uni-bremen.de","489":"+49 421 218 616 22","490":"ZB B 1360","495":"+49 421 218 98 616 22","501":"Bibliothekstraße 2a","rechnung_id":5467}},"word/footer3.xml":{"fields":{"rechnung_id":5467}},"word/fontTable.xml":{"fields":{"rechnung_id":5467}},"word/webextensions/taskpanes.xml":{"fields":{"rechnung_id":5467}},"word/webextensions/webextension1.xml":{"fields":{"rechnung_id":5467}},"word/theme/theme1.xml":{"fields":{"rechnung_id":5467}},"docProps/core.xml":{"fields":{"rechnung_id":5467}},"docProps/app.xml":{"fields":{"rechnung_id":5467}}}', JSON_OBJECT_AS_ARRAY);
	}

	/**
	 * @return array
	 */
	protected function getZData() : array {
		return json_decode('{"word\/document.xml":{"fields":{"bewerbung_id":5284,"478":"Marlis","479":"Glomba","501":"Bibliothekstra\u00dfe 2a - Zentralbereich","489":"+49 421 218 616 22","495":"+49 421 218 98 616 22","481":"mglomba@uni-bremen.de","499":"2-2","1981":"mabo@uni-bremen.de","1982":"https:\/\/www.uni-bremen.de\/mabo\/studium\/arbeitsbezogene-beratung","1674":"Weiterbildungskurs Arbeitsbezogene Beratung","67":"Natalie","70":"Kaluzny","1698":"Lange Stra\u00dfe 60","1699":"27211","1700":"Bassum","66":"Frau","1707":null,"1801":null,"1961":"27.06.2022"},"views":{"V_TITLE":null,"V_P_BEZEICHNUNG":"zum","V_SALUTATION":"Sehr geehrte Frau","V_TERMINATE_DATE_1":null,"V_TERMINATE_DATE_2":null}},"customXml\/itemProps1.xml":{"fields":{"bewerbung_id":5284}},"word\/numbering.xml":{"fields":{"bewerbung_id":5284}},"word\/styles.xml":{"fields":{"bewerbung_id":5284}},"word\/settings.xml":{"fields":{"bewerbung_id":5284}},"word\/webSettings.xml":{"fields":{"bewerbung_id":5284}},"word\/footnotes.xml":{"fields":{"bewerbung_id":5284}},"word\/endnotes.xml":{"fields":{"bewerbung_id":5284}},"word\/header1.xml":{"fields":{"bewerbung_id":5284}},"word\/footer1.xml":{"fields":{"bewerbung_id":5284}},"word\/header2.xml":{"fields":{"bewerbung_id":5284}},"word\/footer2.xml":{"fields":{"bewerbung_id":5284}},"word\/header3.xml":{"fields":{"bewerbung_id":5284}},"word\/fontTable.xml":{"fields":{"bewerbung_id":5284}},"word\/webextensions\/taskpanes.xml":{"fields":{"bewerbung_id":5284}},"word\/webextensions\/webextension1.xml":{"fields":{"bewerbung_id":5284}},"word\/theme\/theme1.xml":{"fields":{"bewerbung_id":5284}},"docProps\/core.xml":{"fields":{"bewerbung_id":5284}},"docProps\/app.xml":{"fields":{"bewerbung_id":5284}}}', JSON_OBJECT_AS_ARRAY);
	}

	/**
	 * @return array
	 */
	protected function getEZDate() : array {
		return json_decode('{"word/document.xml":{"fields":{"66":null,"67":"Emma1","70":"Emma1","478":"Ronny","479":"Krämer","481":"ronny.kraemer@uni-bremen.de","488":null,"489":"+49 421 218 61629","490":null,"495":null,"499":"RK","501":"Mary-Somerville Str. 3","502":"Bremen","504":"28359","1698":null,"1699":null,"1700":null,"1736":0,"1801":null,"1981":"emma@uni-bremen.de","1982":null,"bewerbung_id":5391},"views":{"V_TITLE":null,"V_P_BEZEICHNUNG":"zum Weiterbildenden Masterstudiengang \"Entscheidungsmanagement (Professional Public Decision Making)\" Master","V_SALUTATION":"Guten Tag Emma1","V_WBTITLE":"Weiterbildungsstudierende:n","V_GROUP_2":true,"V_GROUP_1":false,"V_GROUP_3":false}},"customXml/itemProps1.xml":{"fields":{"bewerbung_id":5391}},"word/numbering.xml":{"fields":{"bewerbung_id":5391}},"word/styles.xml":{"fields":{"bewerbung_id":5391}},"word/settings.xml":{"fields":{"bewerbung_id":5391}},"word/webSettings.xml":{"fields":{"bewerbung_id":5391}},"word/footnotes.xml":{"fields":{"bewerbung_id":5391}},"word/endnotes.xml":{"fields":{"bewerbung_id":5391}},"word/comments.xml":{"fields":{"bewerbung_id":5391}},"word/commentsExtended.xml":{"fields":{"bewerbung_id":5391}},"word/commentsIds.xml":{"fields":{"bewerbung_id":5391}},"word/commentsExtensible.xml":{"fields":{"bewerbung_id":5391}},"word/header1.xml":{"fields":{"bewerbung_id":5391}},"word/footer1.xml":{"fields":{"bewerbung_id":5391}},"word/header2.xml":{"fields":{"bewerbung_id":5391}},"word/footer2.xml":{"fields":{"bewerbung_id":5391}},"word/header3.xml":{"fields":{"bewerbung_id":5391}},"word/fontTable.xml":{"fields":{"bewerbung_id":5391}},"word/people.xml":{"fields":{"bewerbung_id":5391}},"word/webextensions/taskpanes.xml":{"fields":{"bewerbung_id":5391}},"word/webextensions/webextension1.xml":{"fields":{"bewerbung_id":5391}},"word/theme/theme1.xml":{"fields":{"bewerbung_id":5391}},"docProps/core.xml":{"fields":{"bewerbung_id":5391}},"docProps/app.xml":{"fields":{"bewerbung_id":5391}}}', JSON_OBJECT_AS_ARRAY);
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