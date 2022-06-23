<?php

namespace ACAT\Parser\Tag;

use ACAT\Document\ContentPart;
use ACAT\Parser\ParserConstants;
use DOMNode;
use DOMNodeList;
use Psr\Log\LogLevel;

/**
 *
 */
class WordTagGenerator extends TagGenerator {

	/**
	 * @return DOMNodeList
	 */
	protected function getTextNodes() : DOMNodeList {
		return $this->contentPart->getXPath()->query(ParserConstants::WORD_TEXT_NODES);
	}

	/**
	 * @return void
	 */
	public function generateTags(): void {

		$this->log(LogLevel::INFO, 'starting tag generator for content part ' . $this->contentPart->getPath());

		$textNodes = $this->getTextNodes();

		$this->log(LogLevel::DEBUG, 'content part hast ' . $textNodes->length . ' text nodes');

		foreach ($textNodes as $textNode) {
			preg_match_all(ParserConstants::MARKER_REG_EX, $textNode->nodeValue, $matches, PREG_OFFSET_CAPTURE, 0);
			if ($matches[0]) {
				$nodes = $this->getTags($matches, $textNode->nodeValue);
				$this->insertNodes($nodes, $textNode);
			}
		}

		$this->log(LogLevel::DEBUG, $this->contentPart->getDomDocument()->saveXML());
		$this->log(LogLevel::INFO, 'finished tag generator');

	}

	/**
	 * @param array $nodes
	 * @param DOMNode $textNode
	 */
	private function insertNodes(array $nodes, DOMNode $textNode): void {

		$this->log(LogLevel::INFO, 'inserting tags in content part');

		$beforeNode = $textNode;

		for ($i = count($nodes) - 1; $i >= 0; $i--) {

			$this->log(LogLevel::DEBUG, 'inserting tag ' . $nodes[$i]->getXMLTagAsString());

			$insertNode = $nodes[$i]->getDOMNode($this->contentPart->getDomDocument());
			$beforeNode = $textNode->parentNode->insertBefore($insertNode, $beforeNode);
		}

		$textNode->parentNode->removeChild($textNode);

	}

}