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

namespace ACAT\Modul\Setting\Template\Model\Placeholder;


use ACAT\App\Exception\AppException;

/**
 * Class StartBlockNode
 * @package ACAT\Modul\Setting\Template\Model\Node
 */
class StartBlockPlaceholder extends BlockPlaceholder {

	/**
	 * StartBlockNode constructor.
	 * @throws AppException
	 */
	public function __construct() {
		parent::__construct(0);
	}

}