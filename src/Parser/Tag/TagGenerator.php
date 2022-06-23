<?php

namespace ACAT\Parser\Tag;

use ACAT\Document\ContentPart;
use ACAT\Document\HTML\HTMLContentPart;
use ACAT\Document\Word\WordContentPart;
use ACAT\Exception\PlaceholderException;
use ACAT\Exception\TagGeneratorException;
use ACAT\Parser\Placeholder\ACatPlaceholder;
use ACAT\Parser\Placeholder\WordTextPlaceholder;
use DOMNodeList;
use Psr\Log\LoggerInterface;
use Psr\Log\LogLevel;

/**
 *
 */
abstract class TagGenerator {

	/**
	 * @var LoggerInterface|null
	 */
	private ?LoggerInterface $logger;

	/**
	 * @var ContentPart
	 */
	protected ContentPart $contentPart;

	/**
	 * @param ContentPart $contentPart
	 * @param LoggerInterface|null $logger
	 */
	private function __construct(ContentPart $contentPart, LoggerInterface $logger = null) {
		$this->logger = $logger;
		$this->contentPart = $contentPart;
	}

	/**
	 * @param ContentPart $contentPart
	 * @param LoggerInterface|null $logger
	 * @return static
	 * @throws TagGeneratorException
	 */
	public static function getInstance(ContentPart $contentPart, LoggerInterface $logger = null) : self {

		if ($contentPart instanceof WordContentPart) {
			return new WordTagGenerator($contentPart, $logger);
		}
		else if ($contentPart instanceof HTMLContentPart) {
			return new HTMLTagGenerator($contentPart, $logger);
		}

		throw new TagGeneratorException('unimplemented content part');

	}

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

			$this->log(LogLevel::DEBUG, 'replacing "' . $nodeValue . '" from "' . $value . '"');

			if ($offset > 0 && $currentIndex < $offset) {

				$currentValue = substr($nodeValue, $currentIndex, ($offset - $currentIndex));
				$node = new WordTextPlaceholder($currentValue);
				$currentIndex = $currentIndex + $node->length();

				$this->log(LogLevel::DEBUG, 'created node ' . $node->getXMLTagAsString());

				$nodes[] = $node;
			}

			try {
				$node = ACatPlaceholder::getPlaceholder($value);
			}
			catch (PlaceholderException $e) {
				$this->log(LogLevel::WARNING, $e);
				$node = new WordTextPlaceholder($value);
			}

			$this->log(LogLevel::DEBUG, 'created node ' . $node->getXMLTagAsString());

			$currentIndex = $currentIndex + $node->length();
			$nodes[] = $node;

		}

		if ($currentIndex > 0 && $currentIndex < strlen($nodeValue)) {

			$currentValue = substr($nodeValue, $currentIndex, (strlen($nodeValue) - $currentIndex));
			$node = new WordTextPlaceholder($currentValue);

			$this->log(LogLevel::DEBUG, 'created node ' . $node->getXMLTagAsString());

			$nodes[] = $node;

		}

		$this->log(LogLevel::INFO, 'created ' . count($nodes) . ' nodes');

		return $nodes;

	}

	/**
	 * @param string $level
	 * @param string $message
	 * @return void
	 */
	protected function log(string $level, string $message) {
		$this->logger?->log($level, $message);
	}

	/**
	 * @return void
	 */
	abstract function generateTags() : void;

	/**
	 * @return DOMNodeList
	 */
	protected abstract function getTextNodes() : DOMNodeList;

}