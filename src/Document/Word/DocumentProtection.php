<?php
/*
 * Copyright (c) 2021 - Akademie für Weiterbildung der Universtät Bremen
 *
 * The contents of this file are subject to the vtiger CRM Public License Version 1.0
 * ("License"); You may not use this file except in compliance with the License
 * The Original Code is:  vtiger CRM Open Source
 * The Initial Developer of the Original Code is vtiger.
 * Portions created by vtiger are Copyright (C) vtiger.
 * All Rights eserved.
 * reviewed and modified by Akademie für Weiterbildung der Universtät Bremen
 */

namespace ACAT\Modul\Setting\Template\Model\Document;

use ACAT\App\Exception\AppException;
use Exception;

/**
 * Class DocumentProtection
 * @package ACAT\Modul\Setting\Template\Model\Document\Element
 */
class DocumentProtection {

	/**
	 * @var string|null
	 */
	private ?string $editing = "readOnly";

	/**
	 * @var string|null
	 */
	private ?string $password;

	/**
	 * @var int
	 */
	private int $spinCount = 100000;

	/**
	 * @var string
	 */
	private string $algorithm = PasswordEncoder::ALGORITHM_SHA_1;

	/**
	 * @var string|null
	 */
	private ?string $salt;

	/**
	 * DocumentProtection constructor.
	 * @param string|null $password
	 * @throws Exception
	 */
	public function __construct(?string $password = null) {

		if (!$password) {
			$randomBytes = random_bytes(32);
			$password = bin2hex($randomBytes);
		}

		$this->password = $password;

	}

	/**
	 * @return string|null
	 */
	public function getEditing() : ?string {
		return $this->editing;
	}

	/**
	 * @return string
	 */
	public function getPassword() : string {
		return PasswordEncoder::hashPassword($this->password, $this->algorithm, $this->getSalt(), $this->spinCount);
	}

	/**
	 * @return int
	 */
	public function getSpinCount() : int {
		return $this->spinCount;
	}

	/**
	 * @param int $spinCount
	 */
	public function setSpinCount(int $spinCount) : void {
		$this->spinCount = $spinCount;
	}

	/**
	 * @return string
	 */
	public function getAlgorithm() : string {
		return $this->algorithm;
	}

	/**
	 * @return string
	 */
	public function getAlgorithmId() : string {
		return PasswordEncoder::getAlgorithmId($this->algorithm);
	}

	/**
	 * @param string $algorithm
	 */
	public function setAlgorithm(string $algorithm) : void {
		$this->algorithm = $algorithm;
	}

	/**
	 * @return mixed
	 */
	public function getSalt() : string {
		if (empty($this->salt)) {
			$this->salt = openssl_random_pseudo_bytes(16);
		}
		return $this->salt;
	}

	/**
	 * @param $salt
	 * @throws AppException
	 */
	public function setSalt($salt) : void {

		if ($salt !== null && strlen($salt) !== 16) {
			throw new AppException('salt has to be of exactly 16 bytes length');
		}

		$this->salt = $salt;

	}

}