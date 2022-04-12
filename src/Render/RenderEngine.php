<?php


namespace ACAT\Render;

use ACAT\Document\ContentPart;
use ACAT\Document\Document;
use ACAT\Exception\RenderException;
use ACAT\Parser\Element\ElementGenerator;
use ACAT\Parser\Normalizer;
use ACAT\Parser\Tag\TagGenerator;
use ACAT\Parser\Tag\WordTagGenerator;
use ACAT\Render\Element\FieldRender;

/**
 *
 */
class RenderEngine {

	/**
	 * @var Document|null
	 */
	private ?Document $document;

	/**
	 * @var array
	 */
	private array $values = ['fields' => [], 'blocks' => []];

	/**
	 * @var Normalizer
	 */
	private Normalizer $normalizer;

	/**
	 * @var TagGenerator
	 */
	private TagGenerator $tagGenerator;

	/**
	 * @var ElementGenerator
	 */
	private ElementGenerator $elementGenerator;

	/**
	 * @param Document|null $document
	 */
	public function __construct(Document $document = null) {

		$this->document = $document;

		$this->normalizer = new Normalizer();
		$this->tagGenerator = new WordTagGenerator();
	}

	/**
	 * @param ContentPart $contentPart
	 * @return void
	 */
	public function renderContentPart(ContentPart $contentPart) : void {

	}

	/**
	 * @param string|null $pkValue
	 * @throws AppException
	 * @throws CacheException
	 * @throws DatabaseException
	 * @throws InvalidArgumentException
	 * @throws Exception
	 */
	public function render(?string $pkValue = null) : void {

		$this->getValues($pkValue);
		
		$this->renderConditionElements();
		$this->renderFieldElements();
		$this->renderTextElements();
        $this->renderViewElements();

		$this->renderBlocks();

	}

	/**
	 * @param String|null $pkValue
	 * @throws AppException
	 * @throws CacheException
	 * @throws DatabaseException
	 * @throws InvalidArgumentException
	 * @throws Exception
	 */
	private function getValues(?string $pkValue) : void {

		$adb = PDODatabase::getInstance();
		$queries = $this->contentPart->getQuery($this->templateModel);

		if ($pkValue) {
		    $params[] = $pkValue;
        }
		else {
		    $params = [];
        }

        if (!empty($queries['views'])) {
            foreach ($queries['views'] as $viewId => $viewQuery) {
                $this->values['views'][$viewId] = $adb->query($viewQuery, $params)->getResultSet();
              }
        }

		if (!empty($queries['fields'])) {
			$fieldResults = $adb->pquery($queries['fields']['query'], $params, true);
			if ($fieldResults->hasResultSet()) {
				$this->values['fields'] = $fieldResults->getResultSet()[0];
			}
		}

		if (!empty($queries['blocks'])) {
			foreach ($queries['blocks'] as $key => $blockQuery) {
                if (array_key_exists('fields', $blockQuery)) {
                    $blockResult = $adb->pquery($blockQuery['fields']['query'], $params, true);
                    foreach ($blockResult->getResultSet() as $row) {
                        $normalizedRow = $this->normalizeValues($row, [$this->templateModel->getModule()->basetableid]);
                        if (!empty($normalizedRow)) {
                            $this->values['blocks'][$key]['fields'][] = $normalizedRow;
                        }
                    }
                }
                if (array_key_exists('views', $blockQuery)) {
                    foreach ($blockQuery['views'] as $view) {
                        $result = $adb->query($view['query'], $params);
                        $this->values['blocks'][$key]['views'][$view['view']] = $result->getResultSet();
                    }
                }
			}
		}

	}

	/**
	 * @param array $values
	 * @param array $skipFields
	 * @return array
	 */
	private function normalizeValues(array $values = [], array $skipFields = []) : array {

		foreach ($values as $field => $value) {
			if (!in_array($field, $skipFields)) {
				if (!empty($value)) {
					return $values;
				}
			}
		}

		return [];

	}

	/**
	 * @throws AppException
	 * @throws Exception
	 */
	public function renderFieldElements() : void {

		$fieldRender = new FieldRender();
		$fieldElements = $this->contentPart->getFieldElements();

		if ($fieldElements) {
			$fieldRender->render($fieldElements, $this->values['fields']);
		}

	}

	/**
	 * @throws Exception
	 */
	public function renderTextElements() : void {

		$textRender = new TextRender();
		$textElements = $this->contentPart->getTextElements();

		if ($textElements) {
			$textRender->render($textElements);
		}

	}

    /**
     * @return void
     * @throws AppException
     */
    public function renderViewElements() : void {

        $viewElementRender = new ViewElementRender();
        $viewElements = $this->contentPart->getViewElements();

        if ($viewElements) {
            $viewElementRender->render($viewElements);
        }

    }

	/**
	 * @throws AppException
	 * @throws Exception
	 */
	public function renderConditionElements() : void {

		$conditionRender = new ConditionRender();
		$conditionElements = $this->contentPart->getConditionElements();

		if ($conditionElements) {
			$conditionRender->render($conditionElements, $this->values['fields']);
		}

	}

	/**
	 * @throws Exception
	 */
	public function renderBlocks() : void {

		$blockElements = $this->contentPart->getBlockElements();

		if ($blockElements) {
			$blockRender = new BlockRender();
			$blockRender->render($blockElements, $this->values);
		}

	}

}