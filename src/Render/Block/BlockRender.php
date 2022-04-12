<?php

namespace ACAT\Render\Block;

use ACAT\Exception\RenderException;
use ACAT\Modul\Setting\Template\Model\Render\ConditionRender;
use ACAT\Modul\Setting\Template\Model\Render\ViewElementRender;
use ACAT\Parser\Element\BlockElement;
use ACAT\Parser\Element\ParagraphBlock;
use ACAT\Parser\Element\TableCellBlock;
use ACAT\Parser\Element\TableRowBlock;
use ACAT\Parser\Element\TextBlock;
use ACAT\Render\Element\FieldRender;
use ACAT\Render\Element\TextRender;
use ACAT\Render\Render;
use Exception;

/**
 *
 */
class BlockRender extends Render {

	/**
	 * @var BlockElement
	 */
    protected BlockElement $blockElement;

    /**
     * @var array
     */
    protected array $values = [];

	/**
	 * @param BlockElement $blockElement
	 * @param array $values
	 * @return BlockRender
	 * @throws RenderException
	 */
    public function getBlockRender(BlockElement $blockElement, array $values): BlockRender {

        if ($blockElement instanceof TextBlock) {
            return new TextBlockRender($blockElement, $values);
        }
        else if ($blockElement instanceof ParagraphBlock) {
            return new ParagraphBlockRender($blockElement, $values);
        }
        else if ($blockElement instanceof TableCellBlock) {
            return new TableCellBlockRender($blockElement, $values);
        }
        else if ($blockElement instanceof TableRowBlock) {
            return new TableRowBlockRender($blockElement, $values);
        }
        else {
            throw new RenderException('unknown block render');
        }

    }

	/**
	 * @throws AppException
	 * @throws Exception
	 * @throws Exception
	 */
    private function renderBlock(): void {

        $textRender = new TextRender();
        $fieldRender = new FieldRender();
        $conditionRender = new ConditionRender();
        $viewElementRender = new ViewElementRender();

        /* render field elements */
        if (array_key_exists('fields', $this->values)) {
            foreach ($this->values['fields'] as $blockValues) {

                /* get children */
                foreach ($this->blockElement->getChildren() as $childElement) {

                    /* create a clone */
                    $clonedChildElement = $childElement->getClonedChildBlockElement($this->blockElement->getContextNode());

                    /* render condition elements */
                    $conditionElements = $clonedChildElement->getConditionElements();
                    $conditionRender->render($conditionElements, $blockValues);

                    /* render text elements */
                    $textElements = $clonedChildElement->getTextElements();
                    $textRender->render($textElements, $blockValues);

                    /* render field elements */
                    $fieldElements = $clonedChildElement->getFieldElements();
                    $fieldRender->render($fieldElements, $blockValues);

                    /* render view elements */
                    $viewElements = $clonedChildElement->getViewElements();

                    if (array_key_exists('views', $this->values)) {
                        $viewValues = $this->values['views'];
                    }
                    else {
                        $viewValues = [];
                    }

                    $viewElementRender->render($viewElements, $viewValues);

                }
            }
        }

    }

	/**
	 * @param array $elements
	 * @param array $values
	 * @return void
	 * @throws RenderException
	 * @throws Exception
	 */
    public function render(array $elements, array $values = []): void {

        foreach ($elements as $key => $blockElement) {

			$blockValues = $this->getValues($key, $values);
			$blockRender = $this->getBlockRender($blockElement, $blockValues);

			$blockRender->renderBlock();
			$blockRender->cleanUpBlock();

        }

    }

    /**
     * @param $blockKey
     * @param $values
     * @return array
     */
    private function getValues($blockKey, $values): array {

        if (array_key_exists($blockKey, $values['blocks'])) {
            $blockValues = $values['blocks'][$blockKey];
        }
        else {
            $blockValues = [];
        }

        foreach ($blockValues['fields'] as $blockKey => $blockValue) {
            foreach ($values['fields'] as $fieldKey => $fieldValue) {
                if (!array_key_exists($fieldKey, $blockValues['fields'][$blockKey])) {
                    $blockValues['fields'][$blockKey][$fieldKey] = $fieldValue;
                }
            }
        }

        return $blockValues;

    }

    /**
     *
     */
    public function cleanUpBlock(): void {

        foreach ($this->blockElement->getChildren() as $child) {
            foreach ($child->getTextElements() as $textElement) {
                $textElement->delete();
            }
            foreach ($child->getFieldElements() as $fieldElement) {
                $fieldElement->delete();
            }
            foreach ($child->getConditionElements() as $conditionElement) {
                $conditionElement->delete();
            }
            $child->delete();
        }

    }

}