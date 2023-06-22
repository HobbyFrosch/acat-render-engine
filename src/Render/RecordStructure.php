<?php

namespace ACAT\Render;

use ACAT\Document\ContentPart;
use ACAT\Document\MarkupDocument;
use ACAT\Exception\ElementException;
use ACAT\Exception\TagGeneratorException;
use ACAT\Parser\Element\ConditionElement;
use ACAT\Parser\Element\ElementGenerator;
use ACAT\Parser\Normalizer;
use ACAT\Parser\Tag\TagGenerator;
use JetBrains\PhpStorm\ArrayShape;
use JetBrains\PhpStorm\Pure;
use Psr\Log\LoggerInterface;

/**
 *
 */
final class RecordStructure {

	/**
	 * @var LoggerInterface|null
	 */
	private ?LoggerInterface $logger;

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
	 * @param LoggerInterface|null $logger
	 */
	#[Pure]
	public function __construct(MarkupDocument $document, LoggerInterface $logger = null) {
		$this->logger = $logger;
		$this->document = $document;
		$this->normalizer = new Normalizer($this->logger);
	}

	/**
	 * @return array
	 * @throws ElementException
	 * @throws TagGeneratorException
	 */
	public function getRecordStructure(): array {
		$recordStructure = [];
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
	public function getContentPartRecordStructure(ContentPart $contentPart): array {

		$recordStructure = [];

		$tagGenerator = TagGenerator::getInstance($contentPart, $this->logger);
		$this->elementGenerator = ElementGenerator::getInstance($contentPart, $this->logger);

		$this->normalizer->normalize($contentPart);
		$tagGenerator->generateTags();

		$recordStructure['views'] = $this->getViewStructure();
		$recordStructure['fields'] = $this->getFieldStructure();
		$recordStructure['blocks'] = $this->getBlockStructure();
		$recordStructure['conditions'] = $this->getConditionStructure();

		foreach ($recordStructure['conditions'] as $condition) {
			if (str_starts_with($condition['field'], 'V') && !in_array($condition['field'], $recordStructure['views'])) {
				$recordStructure['views'][] = $condition['field'];
			}
		}

		return $recordStructure;

	}

	/**
	 * @return array
	 * @throws ElementException
	 */
	private function getBlockStructure(): array {
		$blockStructure = [];
		foreach ($this->elementGenerator->getBlocks() as $key => $block) {
			$blockStructure[$key] = [
				'fields'     => $this->parseStructure($block->getFieldElements()),
				'conditions' => $this->parseConditionElements($block->getConditionElements()),
				'views'      => $this->parseStructure($block->getViewElements())
			];
		}
		return $blockStructure;
	}

	/**
	 * @return array
	 * @throws ElementException
	 */
	private function getConditionStructure(): array {
		return $this->parseConditionElements($this->elementGenerator->getConditionElements());
	}

	/**
	 * @param array $conditionElements
	 * @return array
	 */
	#[ArrayShape(['field' => "", 'operator' => "", 'action' => ""])]
	private function parseConditionElements(array $conditionElements): array {
		$structure = [];
		foreach ($conditionElements as $conditionElement) {
			$structure[] = [
				'field'    => $conditionElement->getFieldId(),
				'operator' => $conditionElement->getExpression(),
				'action'   => $conditionElement->getAction()
			];
		}
		return $structure;
	}

	/**
	 * @return array
	 * @throws ElementException
	 */
	private function getFieldStructure(): array {
		return $this->parseStructure($this->elementGenerator->getFieldElements());
	}

	/**
	 * @return array
	 * @throws ElementException
	 */
	private function getViewStructure(): array {
		return $this->parseStructure($this->elementGenerator->getViewElements());
	}

	/**
	 * @param array $elements
	 * @return array
	 */
	private function parseStructure(array $elements): array {
		$structure = [];
		foreach ($elements as $fieldElement) {
			$structure[] = $fieldElement->getFieldId();
		}
		return array_unique($structure, SORT_STRING);
	}
}