<?php

namespace ACAT\Modul\Setting\Template\Model\Render;

use ACAT\App\Exception\AppException;
use ACAT\App\Exception\DatabaseException;
use ACAT\App\Logging;
use ACAT\Modul\Core\Model\CoreFieldModel;
use ACAT\Modul\Setting\Template\Model\Document\Element\ViewElement;
use ACAT\Modul\Setting\Template\Model\Placeholder\WordTextPlaceholder;
use Exception;
use Psr\Cache\CacheException;
use Psr\Cache\InvalidArgumentException;

/**
 *
 */
class ViewElementRender extends Render {

    /**
     * @param ViewElement $viewElement
     * @param array $values
     * @return void
     * @throws AppException
     * @throws CacheException
     * @throws DatabaseException
     * @throws InvalidArgumentException
     * @throws Exception
     */
    private function renderViewElement(ViewElement $viewElement, array $values) : void {

        $viewId = $viewElement->getFieldId();

        if ($viewId && array_key_exists($viewId, $values)) {

            $displayValue = $this->getViewDisplayValue($values[$viewId]);
            $wordTextNode = new WordTextPlaceholder($displayValue);

            $this->appendRenderedNode($viewElement->getElement(), $wordTextNode->getDOMNode($viewElement->getElement()->ownerDocument));

        }
        else {
            Logging::getFormLogger()->warn($viewElement->getElement()->nodeName . ' does not contains a field id');
        }

        $viewElement->delete();

    }

    /**
     * @param array $value
     * @return string
     * @throws DatabaseException
     * @throws CacheException
     * @throws InvalidArgumentException
     */
    private function getViewDisplayValue(array $value) : string {

        $displayValues = [];

        foreach ($value as $row) {
            $rowValues = [];
            foreach ($row as $fieldId => $fieldValue) {
                $coreFieldModel = CoreFieldModel::getInstanceFromFieldId($fieldId);
                if ($coreFieldModel) {
                    $rowValues[] = $coreFieldModel->getUitypeInstance()->getDisplayValue($fieldValue, false, false, true);
                }
                else {
                    $rowValues[] = $fieldValue;
                }
            }
            $displayValues[] = implode(" ", $rowValues);
        }

        return implode(" " , $displayValues);

    }

    /**
     * @param array $elements
     * @param array $values
     * @return void
     * @throws AppException
     */
    public function render(array $elements, array $values = []) : void {
        foreach ($elements as $element) {
            $this->renderViewElement($element, $values);
        }
    }
}