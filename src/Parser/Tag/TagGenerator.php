<?php

namespace ACAT\Parser\Tag;

use ACAT\Document\ContentPart;
use ACAT\Exception\PlaceholderException;
use ACAT\Parser\ParserConstants;
use ACAT\Parser\Placeholder\ACatPlaceholder;
use ACAT\Parser\Placeholder\WordTextPlaceholder;
use DOMNode;
use DOMNodeList;

/**
 *
 */
abstract class TagGenerator {

	/**
	 * @var ContentPart
	 */
	protected ContentPart $contentPart;

	/**
	 * @param array $matches
	 * @param string $nodeValue
	 * @return array
	 */
	public function getTags(array $matches, string $nodeValue): array {

		$nodes = [];
		$currentIndex = 0;

		foreach ($matches[0] as $match) {

			$value = $match[0];
			$offset = $match[1];

			if ($offset > 0 && $currentIndex < $offset) {
				$currentValue = substr($nodeValue, $currentIndex, ($offset - $currentIndex));
				$node = new WordTextPlaceholder($currentValue);
				$currentIndex = $currentIndex + $node->length();
				$nodes[] = $node;
			}

			try {
				$node = ACatPlaceholder::getPlaceholder($value);
			}
			catch (PlaceholderException $e) {
				$node = new WordTextPlaceholder($value);
			}

			$currentIndex = $currentIndex + $node->length();
			$nodes[] = $node;

		}

		if ($currentIndex > 0 && $currentIndex < strlen($nodeValue)) {
			$currentValue = substr($nodeValue, $currentIndex, (strlen($nodeValue) - $currentIndex));
			$nodes[] = new WordTextPlaceholder($currentValue);
		}

		return $nodes;

	}

	/**
	 * @return DOMNodeList
	 */
	protected abstract function getTextNodes() : DOMNodeList;

}