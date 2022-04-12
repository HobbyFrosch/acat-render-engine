<?php

namespace ACAT\Render\Element;

use ACAT\Exception\ElementException;
use ACAT\Exception\RenderException;
use ACAT\Parser\Element\ViewElement;
use ACAT\Parser\Placeholder\WordTextPlaceholder;
use ACAT\Render\Render;
use DOMException;

/**
 *
 */
class ViewElementRender extends Render {

	/**
	 * @param ViewElement $viewElement
	 * @param array $values
	 * @return void
	 * @throws RenderException
	 * @throws ElementException
	 * @throws DOMException
	 */
    private function renderViewElement(ViewElement $viewElement, array $values) : void {

        $viewId = $viewElement->getFieldId();

        if ($viewId && array_key_exists($viewId, $values)) {

            $wordTextNode = new WordTextPlaceholder($values[$viewId]);
			$this->appendRenderedNode($viewElement->getElement(), $wordTextNode->getDOMNode($viewElement->getDomDocument()));

        }
        else {
            throw new RenderException($viewElement->getElement()->nodeName . ' does not contains a field id');
        }

        $viewElement->delete();

    }

	/**
	 * @param array $elements
	 * @param array $values
	 * @return void
	 * @throws DOMException
	 * @throws ElementException
	 * @throws RenderException
	 */
    public function render(array $elements, array $values = []) : void {
        foreach ($elements as $element) {
            $this->renderViewElement($element, $values);
        }
    }
}