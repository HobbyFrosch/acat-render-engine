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
	public const TEXT_NODES = "//w:t";

	/**
	 *
	 */
	public const MARKER_REG_EX = '/\${[a-zA-Z\d_\<>=.:\!äÄöÖüÜß&()\s]*}/iu';

}