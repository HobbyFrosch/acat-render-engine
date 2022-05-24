<?php


namespace ACAT\Render;

use ACAT\Document\ContentPart;
use ACAT\Document\Word\WordDocument;
use ACAT\Exception\ConditionParserException;
use ACAT\Exception\DocumentException;
use ACAT\Exception\ElementException;
use ACAT\Exception\RenderException;
use ACAT\Exception\TagGeneratorException;
use ACAT\Parser\Element\ElementGenerator;
use ACAT\Parser\Normalizer;
use ACAT\Parser\Tag\TagGenerator;
use ACAT\Render\Block\BlockRender;
use ACAT\Render\Condition\ConditionRender;
use ACAT\Render\Element\FieldRender;
use ACAT\Render\Element\TextRender;
use ACAT\Render\Element\ViewElementRender;
use DOMException;

/**
 *
 */
class RenderEngine {

	/**
	 * @var array
	 */
	private array $values = [
		'fields' => [],
		'blocks' => []
	];

	/**
	 * @var array
	 */
	private array $documentValues = [];

	/**
	 * @var Normalizer
	 */
	private Normalizer $normalizer;

	/**
	 * @var ElementGenerator
	 */
	private ElementGenerator $elementGenerator;

	/**
	 * @param ContentPart $contentPart
	 * @return void
	 * @throws ConditionParserException
	 * @throws DOMException
	 * @throws ElementException
	 * @throws RenderException
	 * @throws TagGeneratorException
	 */
	public function renderContentPart(ContentPart $contentPart): void {

		if (array_key_exists($contentPart->getPath(), $this->documentValues)) {
			$this->values = $this->documentValues[$contentPart->getPath()];
		}
		else {
			$this->values = [
				'views'  => [],
				'fields' => [],
				'blocks' => [],
			];
		}

		$this->normalizer->normalize($contentPart);

		$tagGenerator = TagGenerator::getInstance($contentPart);
		$tagGenerator->generateTags();

		$this->elementGenerator = ElementGenerator::getInstance($contentPart);

		$this->renderConditionElements();
		$this->renderFieldElements();
		$this->renderTextElements();
		$this->renderViewElements();

		$this->renderBlocks();

	}

	/**
	 * @param WordDocument|null $wordDocument
	 * @param array $values
	 * @return void
	 * @throws ConditionParserException
	 * @throws DOMException
	 * @throws DocumentException
	 * @throws ElementException
	 * @throws RenderException
	 * @throws TagGeneratorException
	 */
	public function render(WordDocument $wordDocument = null, array $values = []): void {

		$wordDocument->open();
		$this->normalizer = new Normalizer();

		if ($values) {
			$this->documentValues = $values;
		}

		foreach ($wordDocument->getContentParts() as $contentPart) {
			$this->renderContentPart($contentPart);
		}

		$wordDocument->close(true);

	}

	/**
	 * @return void
	 * @throws ElementException
	 * @throws RenderException
	 * @throws DOMException
	 */
	public function renderFieldElements(): void {

		$fieldRender = new FieldRender();
		$fieldElements = $this->elementGenerator->getFieldElements();

		if ($fieldElements) {
			$fieldRender->render($fieldElements, $this->values['fields']);
		}

	}

	/**
	 * @return void
	 * @throws ElementException
	 * @throws RenderException
	 */
	public function renderTextElements(): void {

		$textRender = new TextRender();
		$textElements = $this->elementGenerator->getTextElements();

		if ($textElements) {
			$textRender->render($textElements);
		}

	}

	/**
	 * @return void
	 * @throws DOMException
	 * @throws ElementException
	 * @throws RenderException
	 */
	public function renderViewElements(): void {

		$viewElementRender = new ViewElementRender();
		$viewElements = $this->elementGenerator->getViewElements();

		if ($viewElements) {
			$viewElementRender->render($viewElements);
		}

	}

	/**
	 * @return void
	 * @throws ElementException
	 * @throws RenderException
	 * @throws ConditionParserException
	 */
	public function renderConditionElements(): void {

		$conditionRender = new ConditionRender();
		$conditionElements = $this->elementGenerator->getConditionElements();

		if ($conditionElements) {
			$conditionRender->render($conditionElements, $this->values['fields']);
		}

	}

	/**
	 * @return void
	 * @throws ConditionParserException
	 * @throws DOMException
	 * @throws ElementException
	 * @throws RenderException
	 */
	public function renderBlocks(): void {

		$blockElements = $this->elementGenerator->getBlocks();

		if ($blockElements) {
			$blockRender = new BlockRender();
			$blockRender->render($blockElements, $this->values);
		}

	}

}