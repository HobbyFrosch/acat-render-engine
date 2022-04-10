<?php

namespace ACAT\Parser\Element;

use ACAT\Document\ContentPart;
use ACAT\Parser\ParserConstants;

/**
 *
 */
abstract class Generator {

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
	 * @throws Exception
	 */
	public function generateElements(): void {
		$this->blocks = $this->getBlocks();
	}

	/**
	 * @return array
	 * @throws Exception
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
				$textElements[] = new TextElement($node, $this->contentPart);
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
	 * @throws AppException
	 * @throws Exception
	 */
	public function getContentElements(string $nodeType = ParserConstants::ACAT_FIELD_NODE): array {

		$found = false;
		$fieldElements = [];

		$nodes = $this->getContentPart()->getXPath()->query('//' . $nodeType);

		foreach ($nodes as $node) {
			if ($nodeType === ParserConstants::ACAT_VIEW_NODE) {
				$fieldElement = new ViewElement($node, $this->contentPart);
			}
			else {
				$fieldElement = new FieldElement($node, $this->contentPart);
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
	 * @throws AppException
	 */
	public function getFieldElements(): array {
		return $this->getContentElements();
	}

	/**
	 * @return array
	 * @throws AppException
	 */
	public function getViewElements(): array {
		return $this->getContentElements(ParserConstants::ACAT_VIEW_NODE);
	}

	/**
	 * @return array
	 * @throws AppException
	 * @throws Exception
	 */
	public function getConditionElements(): array {

		$found = false;
		$conditionElements = [];

		$nodes = $this->getContentPart()->getXPath()->query('//' . ParserConstants::ACAT_CONDITION_NODE);

		foreach ($nodes as $node) {
			$conditionElement = new ConditionElement($node, $this->contentPart);
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
	 * @throws Exception
	 */
	public function getBlocks(): array {

		if (!empty($this->blocks)) {
			return $this->blocks;
		}

		foreach ($this->getStartBlockNodes() as $startNode) {
			foreach ($this->blockTypes as $blockType) {
				try {
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
				catch (AppException $e) {
					Logging::getFormLogger()->warn($e);
				}
			}
		}

		return $this->blocks;

	}

	/**
	 * @return DOMNodeList
	 */
	private function getStartBlockNodes(): DOMNodeList {
		return $this->contentPart->getXPath()->query("//acat:block[@type='start']");
	}

	/**
	 * @param DOMNode $contextNode
	 * @return DOMNode|null
	 */
	private function getEndBlockNode(DOMNode $contextNode): ?DOMNode {

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
	private function getSiblings(DOMNode $contextNode): DOMNodeList {
		return $this->contentPart->getXPath()->query("following-sibling::*", $contextNode);
	}

	/**
	 * @param DOMNode $contextNode
	 * @param string $blockType
	 * @return BlockElement|null
	 * @throws AppException
	 * @throws Exception
	 */
	private function getBlock(DOMNode $contextNode, string $blockType): ?BlockElement {

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
						switch ($blockType) {
							case 'w:t':
								$documentBlock = new TextBlock($contextNode, $endBlockNode, $this->contentPart);
								break;
							case 'w:p':
								$documentBlock = new ParagraphBlock($contextNode, $endBlockNode, $this->contentPart);
								break;
							case 'w:tc':
								$documentBlock = new TableCellBlock($contextNode, $endBlockNode, $this->contentPart);
								break;
							case 'w:tr':
								$documentBlock = new TableRowBlock($contextNode, $endBlockNode, $this->contentPart);
								break;
							default:
								throw new AppException($blockType . ' is not supported');
						}
						$documentBlock->setChildren($children);
						break;
					}
					else {
						throw new AppException('invalid block type');
					}
				}
				else {
					$children[] = new ChildBlockElement($sibling, $this->contentPart);
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
	 * @throws AppException
	 */
	private function getElements(string $elementType, array $children): array {

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
				throw new AppException('type ' . $elementType . 'is not known');
			}
		}

		return $elements;

	}

}