<?php

namespace ACAT\Document\Word;

use ACAT\Document\MarkupDocument;
use DOMDocument;
use Exception;
use ZipArchive;
use ACAT\Utils\FileUtils;
use ACAT\Exception\DocumentException;

/**
 *
 */
class WordDocument extends MarkupDocument {

	/**
	 * @var ZipArchive|null
	 */
	private ?ZipArchive $zipArchive = null;

	/**
	 * @var array
	 */
	private array $contentParts = [];

	/**
	 *
	 */
	private const ROOT = "[Content_Types].xml";

	/**
	 * @return array
	 * @throws DocumentException
	 */
	public function getContentParts(): array {

		if (!$this->zipArchive) {
			$this->open();
		}

		if (!empty($this->contentParts)) {
			return $this->contentParts;
		}

		$domDocument = $this->getDomDocument($this->readFromFile(self::ROOT));
		$nodes = $domDocument->getElementsByTagName('Override');

		if (count($nodes) == 0) {
			throw new DocumentException('no related xml documents found');
		}

		foreach ($nodes as $node) {

			if ($node->hasAttributes()) {

				$path = FileUtils::stripTrailingSlash($node->attributes->getNamedItem('PartName')->nodeValue);
				$content = $this->readFromFile($path);

				if ($path === 'word/settings.xml') {
					$contentPart = new SettingsContentPart($content, $path);
				}
				else {
					$contentPart = new WordContentPart($content, $path);
				}

				$this->contentParts[$path] = $contentPart;

			}

		}

		return $this->contentParts;

	}

	/**
	 * @param string|null $password
	 * @return void
	 * @throws DocumentException
	 * @throws Exception
	 */
	public function protect(string $password = null): void {

		$settingsContentPart = $this->getContentParts()['word/settings.xml'];

		if (!$settingsContentPart) {
			throw new DocumentException($this->path . ' has no settings');
		}

		$settingsContentPart->protect(new DocumentProtection($password));

	}

	/**
	 * @param string $file
	 * @return string
	 * @throws DocumentException
	 */
	private function readFromFile(string $file): string {
		if (!$this->zipArchive) {
			throw new DocumentException('document ' . $this->path . ' is not open');
		}
		return $this->zipArchive->getFromName($file);
	}

	/**
	 * @return void
	 * @throws DocumentException
	 */
	public function open(): void {
		if (!$this->zipArchive) {
			$zipArchive = new ZipArchive();
			if (($result = $zipArchive->open($this->path)) === true) {
				$this->zipArchive = $zipArchive;
			}
			else {
				throw new DocumentException('could not open document ' . $this->path . ' (' . $result . ')');
			}
		}
	}

	/**
	 * @param bool $save
	 * @return void
	 * @throws DocumentException
	 */
	public function close(bool $save = false): void {

		if ($save) {
			$this->save();
		}

		if (!$this->zipArchive) {
			throw new DocumentException('no document is open');
		}

		$this->zipArchive->close();
		$this->zipArchive = null;

	}

	/**
	 * @return void
	 * @throws DocumentException
	 */
	public function save(): void {

		if (!$this->zipArchive) {
			throw new DocumentException('document is not open');
		}

		foreach ($this->contentParts as $contentPart) {

			if (!$this->zipArchive->deleteName($contentPart->getPath())) {
				throw new DocumentException('could not delete content part ' . $contentPart->getPath() . ' in document ' . $this->path);
			}

			if (!$this->zipArchive->addFromString($contentPart->getPath(), $contentPart->getContent())) {
				throw new DocumentException('could not save content part ' . $contentPart->getPath() . ' in document ' . $this->path);
			}

		}
	}

	/**
	 * @param string $content
	 * @return DOMDocument
	 */
	private function getDomDocument(string $content) : DOMDocument {

		$domDocument = new DOMDocument('1.0', 'utf-8');
		$domDocument->loadXML($content);

		return $domDocument;

	}

	/**
	 *
	 */
	public function __destruct() {
		try {
			$this->close();
		}
		catch (DocumentException) {
			//
		}
	}
}