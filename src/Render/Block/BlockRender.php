<?php
/*
 * Copyright (c) 2020 - Akademie für Weiterbildung der Universtät Bremen
 *
 * The contents of this file are subject to the vtiger CRM Public License Version 1.0
 * ("License"); You may not use this file except in compliance with the License
 * The Original Code is:  vtiger CRM Open Source
 * The Initial Developer of the Original Code is vtiger.
 * Portions created by vtiger are Copyright (C) vtiger.
 * All Rights eserved.
 * reviewed and modified by Akademie für Weiterbildung der Universtät Bremen
 */

namespace ACAT\Modul\Setting\Template\Model\Render;

use ACAT\App\Exception\AppException;
use ACAT\App\Logging;
use ACAT\Modul\Setting\Template\Model\Document\Element\BlockElement;
use ACAT\Modul\Setting\Template\Model\Document\Element\ParagraphBlock;
use ACAT\Modul\Setting\Template\Model\Document\Element\TableCellBlock;
use ACAT\Modul\Setting\Template\Model\Document\Element\TableRowBlock;
use ACAT\Modul\Setting\Template\Model\Document\Element\TextBlock;
use DOMNode;
use Exception;

/**
 * Class BlockRender
 * @package ACAT\Modul\Setting\Template\Model\Render
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
     * @throws AppException
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
            throw new AppException('unknown block render');
        }

    }

    /**
     * @throws AppException
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
     * @return mixed|void
     * @throws Exception
     */
    public function render(array $elements, array $values = []): void {

        foreach ($elements as $key => $blockElement) {

            try {

                $blockValues = $this->getValues($key, $values);
                $blockRender = $this->getBlockRender($blockElement, $blockValues);

                $blockRender->renderBlock();
                $blockRender->cleanUpBlock();

            }
            catch (AppException $e) {
                Logging::getFormLogger()->warn($e);
            }

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