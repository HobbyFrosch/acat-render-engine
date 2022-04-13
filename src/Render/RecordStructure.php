<?php

namespace ACAT\Render;

use ACAT\Document\ContentPart;
use ACAT\Document\MarkupDocument;
use ACAT\Exception\ElementException;
use ACAT\Exception\TagGeneratorException;
use ACAT\Parser\Element\ElementGenerator;
use ACAT\Parser\Normalizer;
use ACAT\Parser\Tag\TagGenerator;
use JetBrains\PhpStorm\Pure;

/**
 *
 */
final class RecordStructure {

	/**
	 * @var MarkupDocument
	 */
	private MarkupDocument $document;

	/**
	 * @var Normalizer
	 */
	private Normalizer $normalizer;

	/**
	 * @var ElementGenerator
	 */
	private ElementGenerator $elementGenerator;

	/**
	 * @param MarkupDocument $document
	 */
	#[Pure]
	public function __construct(MarkupDocument $document) {
		$this->document = $document;
		$this->normalizer = new Normalizer();
	}

	/**
	 * @return array
	 * @throws ElementException
	 * @throws TagGeneratorException
	 */
	private function getRecordStructure() : array {
		$recordStructure  = [];
		foreach ($this->document->getContentParts() as $contentPart) {
			$recordStructure[$contentPart->getPath()] = $this->getContentPartRecordStructure($contentPart);
		}
		return $recordStructure;
	}

	/**
	 * @param ContentPart $contentPart
	 * @return array
	 * @throws ElementException
	 * @throws TagGeneratorException
	 */
	public function getContentPartRecordStructure(ContentPart $contentPart) : array {

		$recordStructure = [];

		$tagGenerator = TagGenerator::getInstance($contentPart);
		$this->elementGenerator = ElementGenerator::getInstance($contentPart);

		$this->normalizer->normalize($contentPart);
		$tagGenerator->generateTags();

		$recordStructure['fields'] = $this->getFieldStructure();

		return $recordStructure;

	}

	/**
	 * @return array
	 * @throws ElementException
	 */
	private function getFieldStructure() : array {
		return array_unique($this->elementGenerator->getFieldElements(), SORT_NUMERIC);
	}
}