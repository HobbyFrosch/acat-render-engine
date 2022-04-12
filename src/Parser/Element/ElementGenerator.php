<?php

namespace ACAT\Parser\Element;

use ACAT\Document\ContentPart;
use ACAT\Exception\ElementException;
use ACAT\Parser\ParserConstants;
use ACAT\Utils\DOMUtils;
use DOMNode;
use DOMNodeList;

/**
 *
 */
abstract class ElementGenerator {

	/**
	 * @var ContentPart
	 */
	private ContentPart $contentPart;

	/**
	 * @var array
	 */
	private array $blocks = [];

	/**
	 * @var array
	 */
	private array $textBlocks = [];

	/**
	 * @var array
	 */
	private array $paragraphBlocks = [];

	/**
	 * @var array
	 */
	private array $tableCellBlocks = [];

	/**
	 * @var array
	 */
	private array $tableRowBlocks = [];

	/**
	 * @param ContentPart $contentPart
	 * @throws ElementException
	 */
	public function __construct(ContentPart $contentPart) {
		$this->contentPart = $contentPart;
		$this->generateElements();
	}

	/**
	 * @return ContentPart
	 */
	public function getContentPart(): ContentPart {
		return $this->contentPart;
	}

	/**
	 * @return array
	 */
	public function getBlockTypes(): array {
		return $this->blockTypes;
	}

	/**
	 * @return array
	 */
	public function getTextBlocks(): array {
		return $this->textBlocks;
	}

	/**
	 * @return array
	 */
	public function getParagraphBlocks(): array {
		return $this->paragraphBlocks;
	}

	/**
	 * @return array
	 */
	public function getTableCellBlocks(): array {
		return $this->tableCellBlocks;
	}

	/**
	 * @return array
	 */
	public function getTableRowBlocks(): array {
		return $this->tableRowBlocks;
	}

	/**
	 * @return void
	 * @throws ElementException
	 */
	public function generateElements(): void {
		$this->blocks = $this->getBlocks();
	}

	/**
	 * @return array
	 * @throws ElementException
	 */
	public function getTextElements(): array {

		$found = false;
		$textElements = [];
		$nodes = $this->getContentPart()->getXPath()->query('//' . ParserConstants::ACAT_TEXT_NODES);

		foreach ($nodes as $node) {
			foreach ($this->getBlocks() as $block) {
				foreach ($block->getTextElements() as $textElement) {
					if ($node->isSameNode($textElement->getElement())) {
						$found = true;
						break;
					}
				}
				if ($found) {
					break;
				}
			}
			if (!$found) {
				$textElements[] = new TextElement($node);
			}
			else {
				$found = false;
			}
		}

		return $textElements;

	}

	/**
	 * @param string $nodeType
	 * @return array
	 * @throws ElementException
	 */
	public function getContentElements(string $nodeType = ParserConstants::ACAT_FIELD_NODE): array {

		$found = false;
		$fieldElements = [];

		$nodes = $this->getContentPart()->getXPath()->query('//' . $nodeType);

		foreach ($nodes as $node) {
			if ($nodeType === ParserConstants::ACAT_VIEW_NODE) {
				$fieldElement = new ViewElement($node);
			}
			else {
				$fieldElement = new FieldElement($node);
			}
			foreach ($this->getBlocks() as $block) {
				if (in_array($fieldElement->getId(), $block->getElementIds())) {
					$found = true;
					break;
				}
			}
			if (!$found) {
				$fieldElements[] = $fieldElement;
			}
			else {
				$found = false;
			}
		}

		return $fieldElements;

	}

	/**
	 * @return array
	 * @throws ElementException
	 */
	public function getFieldElements(): array {
		return $this->getContentElements();
	}

	/**
	 * @return array
	 * @throws ElementException
	 */
	public function getViewElements(): array {
		return $this->getContentElements(ParserConstants::ACAT_VIEW_NODE);
	}

	/**
	 * @return array
	 * @throws ElementException
	 */
	public function getConditionElements(): array {

		$found = false;
		$conditionElements = [];

		$nodes = $this->getContentPart()->getXPath()->query('//' . ParserConstants::ACAT_CONDITION_NODE);

		foreach ($nodes as $node) {
			$conditionElement = new ConditionElement($node);
			foreach ($this->getBlocks() as $block) {
				if (in_array($conditionElement->getId(), $block->getElementIds())) {
					$found = true;
					break;
				}
			}
			if (!$found) {
				$conditionElements[] = $conditionElement;
			}
			else {
				$found = false;
			}
		}

		return $conditionElements;

	}

	/**
	 * @return array
	 * @throws ElementException
	 */
	public function getBlocks(): array {

		if (!empty($this->blocks)) {
			return $this->blocks;
		}

		foreach ($this->getStartBlockNodes() as $startNode) {
			foreach ($this->blockTypes as $blockType) {
				$block = $this->getBlock($startNode, $blockType);
				if ($block) {
					$this->blocks[] = $block;
					if ($block instanceof TextBlock) {
						$this->textBlocks[] = $block;
					}
					else if ($block instanceof ParagraphBlock) {
						$this->paragraphBlocks[] = $block;
					}
					else if ($block instanceof TableCellBlock) {
						$this->tableCellBlocks[] = $block;
					}
					else if ($block instanceof TableRowBlock) {
						$this->tableRowBlocks[] = $block;
					}
					break;
				}
			}
		}

		return $this->blocks;

	}

	/**
	 * @return DOMNodeList
	 */
	protected function getStartBlockNodes(): DOMNodeList {
		return $this->contentPart->getXPath()->query("//acat:block[@type='start']");
	}

	/**
	 * @param DOMNode $contextNode
	 * @return DOMNode|null
	 */
	protected function getEndBlockNode(DOMNode $contextNode): ?DOMNode {

		if ($contextNode->nodeName == 'acat:block') {
			return $contextNode;
		}

		$nodeList = $this->contentPart->getXPath()->query(".//acat:block[@type]", $contextNode);

		if ($nodeList->item(0)) {
			return $nodeList->item(0);
		}

		return null;

	}

	/**
	 * @param DOMNode $contextNode
	 * @return DOMNodeList
	 */
	protected function getSiblings(DOMNode $contextNode): DOMNodeList {
		return $this->contentPart->getXPath()->query("following-sibling::*", $contextNode);
	}

	/**
	 * @param DOMNode $contextNode
	 * @param string $blockType
	 * @return BlockElement|null
	 * @throws ElementException
	 */
	protected function getBlock(DOMNode $contextNode, string $blockType): ?BlockElement {

		$children = [];
		$documentBlock = null;

		if ($blockType === 'w:t') {
			$parentNode = $contextNode;
		}
		else {
			$parentNode = DOMUtils::getParentNode($contextNode, $blockType);
		}

		if ($parentNode) {
			foreach ($this->getSiblings($parentNode) as $sibling) {
				$endBlockNode = $this->getEndBlockNode($sibling);
				if ($endBlockNode) {
					if ($endBlockNode->getAttribute('type') == 'end') {
						$documentBlock = match ($blockType) {
							'w:t' => new TextBlock($contextNode, $endBlockNode),
							'w:p' => new ParagraphBlock($contextNode, $endBlockNode),
							'w:tc' => new TableCellBlock($contextNode, $endBlockNode),
							'w:tr' => new TableRowBlock($contextNode, $endBlockNode),
							default => throw new ElementException($blockType . ' is not supported'),
						};
						$documentBlock->setChildren($children);
						break;
					}
					else {
						throw new ElementException('invalid block type');
					}
				}
				else {
					$children[] = new ChildBlockElement($sibling);
				}
			}
		}

		if ($documentBlock) {
			$documentBlock->setTextElements($this->getElements(ParserConstants::ACAT_TEXT_NODES, $documentBlock->getChildren()));
			$documentBlock->setFieldElements($this->getElements(ParserConstants::ACAT_FIELD_NODE, $documentBlock->getChildren()));
			$documentBlock->setViewElements($this->getElements(ParserConstants::ACAT_VIEW_NODE, $documentBlock->getChildren()));
			$documentBlock->setConditionElements($this->getElements(ParserConstants::ACAT_CONDITION_NODE, $documentBlock->getChildren()));
		}

		return $documentBlock;

	}

	/**
	 * @param string $elementType
	 * @param array $children
	 * @return array
	 * @throws ElementException
	 */
	protected function getElements(string $elementType, array $children): array {

		$elements = [];

		foreach ($children as $child) {
			if ($elementType == ParserConstants::ACAT_TEXT_NODES) {
				$elements = array_merge($elements, $child->getTextElements());
			}
			else if ($elementType == ParserConstants::ACAT_FIELD_NODE) {
				$elements = array_merge($elements, $child->getFieldElements());
			}
			else if ($elementType == ParserConstants::ACAT_CONDITION_NODE) {
				$elements = array_merge($elements, $child->getConditionElements());
			}
			else if ($elementType == ParserConstants::ACAT_VIEW_NODE) {
				$elements = array_merge($elements, $child->getViewElements());
			}
			else {
				throw new ElementException('type ' . $elementType . 'is not known');
			}
		}

		return $elements;

	}

}