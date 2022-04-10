<?php

namespace Tests\Parser;

use ACAT\Document\Word\WordContentPart;
use ACAT\Document\Word\WordDocument;
use ACAT\Exception\DocumentException;
use ACAT\Exception\PlaceholderException;
use ACAT\Parser\Normalizer;
use ACAT\Parser\ParserConstants;
use ACAT\Parser\Placeholder\ACatPlaceholder;
use ACAT\Parser\Placeholder\ConditionPlaceholder;
use ACAT\Parser\Placeholder\EndBlockPlaceholder;
use ACAT\Parser\Placeholder\FieldPlaceholder;
use ACAT\Parser\Placeholder\StartBlockPlaceholder;
use ACAT\Parser\Placeholder\TextPlaceholder;
use DOMNodeList;
use PhpOffice\PhpWord\IOFactory;
use PhpOffice\PhpWord\PhpWord;
use PHPUnit\Framework\TestCase;

/**
 * Class NormalizerTest
 * @package Test\Template\Model\Parser
 */
class NormalizerTest extends TestCase {

	/**
	 * @test
	 *
	 * @return void
	 * @throws DocumentException
	 * @throws PlaceholderException
	 */
	public function normalizeWordDocument() : void {

		$currentDocument = __DIR__ . "/Resources/normalizer_test_document_lck.docx";

		//create a copy from original file
		copy(__DIR__ . '/Resources/normalizer_test_document.docx', $currentDocument);

		//create new instance
		$wordDocument = new WordDocument($currentDocument);

		//check instance
		$this->assertInstanceOf(WordDocument::class, $wordDocument);

		//normalizer
		$normalizer = new Normalizer();

		//check instance
		$this->assertInstanceOf(Normalizer::class, $normalizer);

		//open document
		$wordDocument->open();

		//get content parts
		foreach ($wordDocument->getContentParts() as $contentPart) {

			//check content part
			$this->assertInstanceOf(WordContentPart::class, $contentPart);

			//normalize the content part
			$normalizer->normalize($contentPart);

		}

		//save document
		$wordDocument->save();

		//get 'word/document.xml' ContentPart
		$contentPart = $wordDocument->getContentParts()['word/document.xml'];
		$this->assertInstanceOf(WordContentPart::class, $contentPart);

		//get all text nodes
		$textNodes = $contentPart->getXPath()->query('//w:t');
		$this->assertInstanceOf(DOMNodeList::class, $textNodes);
		$this->assertGreaterThan(0, count($textNodes));

		//get placeholders
		$aCatNodes = [];
		$aCatTextNodes = [];
		$acatFieldNodes = [];
		$acatConditionNodes = [];
		$acatStartBlockNodes = [];
		$acatEndBlockNodes = [];

		foreach ($textNodes as $textNode) {
			preg_match_all(ParserConstants::MARKER_REG_EX, $textNode->nodeValue, $matches, PREG_SET_ORDER, 0);
			foreach ($matches as $match) {
				try {
					$aCatNodes[] = ACatPlaceholder::getPlaceholder($match[0]);
				}
				catch (PlaceholderException $e) {
					$this->assertStringContainsString('C:12', $e->getMessage());
				}
			}
		}

		//we need exactly 57 Nodes
		$this->assertCount(57, $aCatNodes);

		foreach ($aCatNodes as $aCatNode) {
			if ($aCatNode instanceof FieldPlaceholder) {
				$acatFieldNodes[] = $aCatNode;
			}
			else if ($aCatNode instanceof ConditionPlaceholder) {
				$acatConditionNodes[] = $aCatNode;
			}
			else if ($aCatNode instanceof TextPlaceholder) {
				$aCatTextNodes[] = $aCatNode;
			}
			else if($aCatNode instanceof StartBlockPlaceholder) {
				$acatStartBlockNodes[] = $aCatNode;
			}
			else if($aCatNode instanceof EndBlockPlaceholder) {
				$acatEndBlockNodes[] = $aCatNode;
			}
		}

		//29 field nodes
		$this->assertCount(29, $acatFieldNodes);

		//14 condition nodes
		$this->assertCount(14, $acatConditionNodes);

		//2 text nodes
		$this->assertCount(2, $aCatTextNodes);

		//6 start block nodes
		$this->assertCount(6, $acatStartBlockNodes);

		//6 end block nodes
		$this->assertCount(6, $acatEndBlockNodes);

	}

}