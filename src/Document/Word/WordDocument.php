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

namespace ACAT\Modul\Setting\Template\Model\Document;

use ACAT\App\Exception\AppException;
use ACAT\App\Exception\DatabaseException;
use ACAT\App\Exception\ParseException;
use ACAT\App\Logging;
use ACAT\App\Util\FileUtils;
use ACAT\Modul\Core\Model\CoreRecordModel;
use ACAT\Modul\Core\Model\CoreTemplateModel;
use ACAT\Modul\Setting\Template\Model\Render\RenderEngine;
use ACAT\Modul\Setting\Template\Model\SettingTemplateRecordModel;
use Exception;
use Monolog\Logger;
use Psr\Cache\CacheException;
use Psr\Cache\InvalidArgumentException;
use ZipArchive;

/**
 * Class WordDocument
 * @package ACAT\Modul\Setting\Template\Model\Document
 */
class WordDocument extends ACatDocument {

    /**
	 * @var ZipArchive|null
	 */
	private ?ZipArchive $zipArchive = null;

	/**
	 * @var string
	 */
	private string $path;

	/**
	 * @var mixed|Logger
	 */
	private Logger $logger;

	/**
	 * @var array
	 */
	private array $contentParts = [];

	/**
	 *
	 */
	private const ROOT = "[Content_Types].xml";

	/**
	 * WordDocument constructor.
	 * @param string $path
	 * @throws AppException
	 * @throws Exception
	 */
	public function __construct(string $path) {

		if (empty($path) || !file_exists($path)) {
			throw new AppException('invalid document path ' . $path);
		}

		$this->path = $path;
		$this->logger = Logging::getFormLogger();

	}

	/**
	 * @param string $module
	 * @return array
	 * @throws AppException
	 */
	public function getDocumentQueries(string $module = 'core') : array {

		$queries = [];

		$this->open();

		foreach ($this->getContentParts() as $contentPart) {
			$queries[$contentPart->getPath()] = $contentPart->getQuery($module);
		}

		$this->save();
		$this->close();

		return $queries;

	}

	/**
	 * @return string
	 */
	public function getPath() : string {
		return $this->path;
	}

	/**
	 * @return array
	 * @throws AppException
	 */
	public function getContentParts() : array {

		if (!$this->zipArchive) {
			throw new AppException('document is not open');
		}

		if (!empty($this->contentParts)) {
			return $this->contentParts;
		}

		$domDocument = $this->getDomDocument($this->readFromFile(self::ROOT));
		$nodes = $domDocument->getElementsByTagName('Override');

		if (count($nodes) == 0) {
			throw new ParseException('no related xml documents found');
		}

		foreach ($nodes as $node) {

			if ($node->hasAttributes()) {

				$path = FileUtils::stripTrailingSlash($node->attributes->getNamedItem('PartName')->nodeValue);
				$content = $this->readFromFile($path);

				if ($path === 'word/settings.xml') {
					$contentPart = new SettingsContentPart($path, $content);
				}
				else {
					$contentPart = new ContentPart($path, $content);
				}

				$this->contentParts[$path] = $contentPart;

			}

		}

		return $this->contentParts;

	}

	/**
	 * @param string|null $password
	 * @throws Exception
	 */
	public function protect(string $password = null) : void {

		$settingsContentPart = $this->getContentParts()['word/settings.xml'];

		if (!$settingsContentPart) {
			throw new AppException($this->path . ' has no settings');
		}

		$settingsContentPart->protect(new DocumentProtection($password));

	}

    /**
     *
     */
	public function removeAddIns() : void {
	    $this->zipArchive->deleteName('word/webextensions/');
    }

    /**
     * @param CoreRecordModel|null $recordModel
     * @param CoreTemplateModel $settingTemplateRecordModel
     * @throws AppException
     * @throws CacheException
     * @throws DatabaseException
     * @throws InvalidArgumentException
     */
	public function render(?CoreRecordModel $recordModel, CoreTemplateModel $settingTemplateRecordModel) : void {

	    foreach ($this->getContentParts() as $contentPart) {
	        $renderEngine = new RenderEngine($contentPart, $settingTemplateRecordModel);
	        if ($recordModel) {
	            $renderEngine->render($recordModel->getId());
            }
	        else {
	            $renderEngine->render();
            }
	    }
	}

	/**
	 * @param string $file
	 * @return string
	 * @throws AppException
	 */
	private function readFromFile(string $file) : string {

		if (!$this->zipArchive) {
			throw new AppException('document ' . $this->path . ' is not open');
		}

		return $this->zipArchive->getFromName($file);

	}

	/**
	 * @throws AppException
	 */
	public function open() : void {
		if (!$this->zipArchive) {
			$zipArchive = new ZipArchive();
			if (($result = $zipArchive->open($this->path)) === true) {
				$this->zipArchive = $zipArchive;
			}
			else {
				throw new AppException('could not open document ' . $this->path. ' (' . $result . ')');
			}
		}
	}

	/**
	 * @param bool $save
	 * @throws AppException
	 */
	public function close(bool $save = false) : void {

		if ($save) {
			$this->save();
		}

		if (!$this->zipArchive) {
			throw new AppException('no document is open');
		}

		$this->zipArchive->close();
		$this->zipArchive = null;

	}

	/**
	 * @throws AppException
	 */
	public function save() : void {

		if (!$this->zipArchive) {
			throw new AppException('no document is open');
		}

		foreach ($this->contentParts as $contentPart) {

			if (!$this->zipArchive->deleteName($contentPart->getPath())) {
				throw new AppException('could not delete content part ' . $contentPart->getPath() . ' in document ' . $this->path);
			}

			if (!$this->zipArchive->addFromString($contentPart->getPath(), $contentPart->getContent())) {
				throw new AppException('could not save content part ' . $contentPart->getPath() . ' in document ' . $this->path);
			}

		}
	}

	/**
	 *
	 */
	public function __destruct() {
		try {
			$this->close();
		} catch (AppException $e) {
			//
		}
	}

}