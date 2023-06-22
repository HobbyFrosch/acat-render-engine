<?php

namespace ACAT\Parser\Tag;

use ACAT\Parser\ParserConstants;
use DOMNodeList;

/**
 *
 */
class HTMLTagGenerator extends WordTagGenerator {

	/**
	 * @return DOMNodeList
	 */
	protected function getTextNodes() : DOMNodeList {
		return $this->contentPart->getXPath()->query(ParserConstants::HTML_TEXT_NODES);
	}

}