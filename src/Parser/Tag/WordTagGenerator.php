<?php

namespace ACAT\Parser\Tag;

use ACAT\Document\ContentPart;
use ACAT\Parser\ParserConstants;
use DOMNode;
use DOMNodeList;

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

		$textNodes = $this->getTextNodes();

		foreach ($textNodes as $textNode) {
			preg_match_all(ParserConstants::MARKER_REG_EX, $textNode->nodeValue, $matches, PREG_OFFSET_CAPTURE, 0);
			if ($matches[0]) {
				$nodes = $this->getTags($matches, $textNode->nodeValue);
				$this->insertNodes($nodes, $textNode);
			}
		}

	}

	/**
	 * @param array $nodes
	 * @param DOMNode $textNode
	 */
	private function insertNodes(array $nodes, DOMNode $textNode): void {

		$beforeNode = $textNode;

		for ($i = count($nodes) - 1; $i >= 0; $i--) {
			$insertNode = $nodes[$i]->getDOMNode($this->contentPart->getDomDocument());
			$beforeNode = $textNode->parentNode->insertBefore($insertNode, $beforeNode);
		}

		$textNode->parentNode->removeChild($textNode);

	}

}