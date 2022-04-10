<?php

namespace ACAT\Parser;

/**
 *
 */
class ParserConstants {

	/**
	 *
	 */
	public const ACAT_VIEW_NODE = 'acat:view';

	/**
	 *
	 */
	public const ACAT_FIELD_NODE = 'acat:field';

	/**
	 *
	 */
	public const ACAT_BLOCK_NODE = 'acat:block';

	/**
	 *
	 */
	public const ACAT_CONDITION_NODE = 'acat:condition';

	/**
	 *
	 */
	public const ACAT_TEXT_NODES = 'acat:text';

	/**
	 *
	 */
	public const WORD_TEXT_NODES = "//w:t";

	/**
	 *
	 */
	public const HTML_TEXT_NODES= "//text()";

	/**
	 *
	 */
	public const MARKER_REG_EX = '/\${[a-zA-Z\d_\<>=.:\!äÄöÖüÜß&()\s]*}/iu';

}