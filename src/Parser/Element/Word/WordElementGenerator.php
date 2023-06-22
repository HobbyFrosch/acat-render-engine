<?php

namespace ACAT\Parser\Element\Word;

use ACAT\Parser\Element\ElementGenerator;

/**
 *
 */
class WordElementGenerator extends ElementGenerator {

	/**
	 * @var array|string[]
	 */
	protected array $blockTypes = ['w:t', 'w:p', 'w:tc', 'w:tr'];

}