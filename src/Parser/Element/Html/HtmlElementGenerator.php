<?php

namespace ACAT\Parser\Element\Html;

use ACAT\Parser\Element\ElementGenerator;

class HtmlElementGenerator extends ElementGenerator {

	/**
	 * @var array|string[]
	 */
	protected array $blockTypes = ['t', 'tr', 'td'];

}