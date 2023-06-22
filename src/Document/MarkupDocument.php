<?php

namespace ACAT\Document;

/**
 *
 */
abstract class MarkupDocument extends Document {

	/**
	 * @return array
	 */
	abstract function getContentParts() : array;

}