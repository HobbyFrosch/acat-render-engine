<?php

namespace ACAT\Render\Element;

use ACAT\Parser\ParserConstants;
use ACAT\Exception\RenderException;
use ACAT\Parser\Element\TextElement;
use ACAT\Parser\Placeholder\WordTextPlaceholder;
use ACAT\Render\Render;
use DOMException;

/**
 *
 */
class TextRender extends Render {

	/**
	 * @param TextElement $textElement
	 * @return void
	 * @throws DOMException
	 */
	public function renderTextElement(TextElement $textElement) {

        $wordTextNode = new WordTextPlaceholder($textElement->getText());

		$this->appendRenderedNode($textElement->getElement(), $wordTextNode->getDOMNode($textElement->getDomDocument()));
		$this->deleteNode($textElement->getElement());

	}

	/**
	 * @param array $elements
	 * @param array $values
	 * @return void
	 * @throws RenderException
	 */
	public function render(array $elements, array $values = []) : void {
		foreach ($elements as $textElement) {
			try {
				$this->renderTextElement($textElement);
			}
			catch (DOMException $e) {
				throw new RenderException($e);
			}
		}
	}

}