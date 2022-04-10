<?php

namespace ACAT\Parser;

use ACAT\Document\Word\WordContentPart;
use ACAT\Utils\StringUtils;
use DOMNode;

/**
 *
 */
class Normalizer {

	/**
	 * @var array
	 */
	private array $textNodes = [];

	/**
	 * @var WordContentPart
	 */
	private WordContentPart $contentPart;

	/**
	 * @param WordContentPart $contentPart
	 * @return void
	 */
	public function normalize(WordContentPart $contentPart): void {

		$this->contentPart = $contentPart;
		$textNodes = $this->contentPart->getXPath()->query(ParserConstants::WORD_TEXT_NODES);

		foreach ($textNodes as $textNode) {
			$this->processTextNode($textNode);
		}

	}

	/**
	 * @param DOMNode $textNode
	 */
	private function processTextNode(DOMNode $textNode): void {

		$nodeValue = $textNode->nodeValue;

		preg_match_all(ParserConstants::MARKER_REG_EX, $nodeValue, $matches);
		$nodeValue = $this->removePlaceHoldersFromNodeValue($matches, $nodeValue);

		if ((!empty($this->textNodes) || StringUtils::contains($nodeValue, '$') || StringUtils::contains($nodeValue, '}'))) {

			if (!empty($this->textNodes) && StringUtils::contains($nodeValue, '$') && !StringUtils::contains($nodeValue, '}')) {
				$this->textNodes = [];
			}

			if (!StringUtils::contains($nodeValue, '}')) {
				$this->textNodes[] = $textNode;
			}
			else if (!empty($this->textNodes) && StringUtils::contains($nodeValue, '$')) {

				[$end] = explode('$', $nodeValue);
				$this->textNodes[count($this->textNodes) - 1]->nodeValue .= $end;

				$this->mergeNodes();

				$textNode->nodeValue = str_replace($end, '', $textNode->nodeValue);
				$this->textNodes[] = $textNode;

			}
			else {

				$this->textNodes[] = $textNode;
				$this->mergeNodes();

			}
		}
	}

	/**
	 *
	 */
	private function mergeNodes(): void {

		$placeHolder = $this->getPlaceHolderValue();
		preg_match_all(ParserConstants::MARKER_REG_EX, $placeHolder, $matches);

		if (!empty($matches)) {
			$this->textNodes[0]->nodeValue = $placeHolder;
			for ($i = 1; $i < count($this->textNodes); $i++) {
				$this->cleanUpNodes($this->textNodes[$i]);
			}
		}

		$this->textNodes = [];

	}

	/**
	 * @param array $placeHolders
	 * @param string $nodeValue
	 * @return string
	 */
	private function removePlaceHoldersFromNodeValue(array $placeHolders, string $nodeValue): string {
		foreach ($placeHolders as $placeHolder) {
			if(array_key_exists(0, $placeHolder)) {
				$nodeValue = str_replace($placeHolder[0], '', $nodeValue);
			}
		}
		return $nodeValue;
	}

	/**
	 * @return string
	 */
	private function getPlaceHolderValue(): string {
		$value = "";
		foreach ($this->textNodes as $textNode) {
			$value .= $textNode->nodeValue;
		}
		return $value;
	}

	/**
	 * @param DOMNode $node
	 */
	private function cleanUpNodes(DOMNode $node): void {

		foreach ($this->contentPart->getHierarchy() as $level) {
			if ($node->parentNode) {
				$parentNode = $node->parentNode;
				$node->parentNode->removeChild($node);
				if ($this->contentPart->getXPath()->evaluate('count(' . $parentNode->getNodePath() . '/' . $level . ')') > 0) {
					return;
				}
				$node = $parentNode;
			}
			else {
				return;
			}
		}

	}

}