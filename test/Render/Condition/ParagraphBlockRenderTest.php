<?php


namespace Tests\Render\Condition;

use JetBrains\PhpStorm\ArrayShape;

/**
 *
 */
class ParagraphBlockRenderTest extends TestCase {

	/**
	 * @test
	 * @throws AppException
	 * @throws Exception
	 */
	public function aParagraphBlockRenderCanBeCreated(): void {

		$blockElements = $this->getContentPart()->getBlockElements();

		$this->assertCount(1, $blockElements);
		$this->assertInstanceOf(ParagraphBlock::class, $blockElements[0]);

		$paragraphBlockRender = new ParagraphBlockRender($blockElements[0], []);
		$this->assertInstanceOf(ParagraphBlockRender::class, $paragraphBlockRender);

	}

	/**
	 * @test
	 * @throws AppException
	 * @throws Exception
	 */
	public function renderParagraphBlock(): void {

		$contentPart = $this->getContentPart();
		$blockElements = $contentPart->getBlockElements();

		$this->assertCount(1, $blockElements);
		$this->assertInstanceOf(ParagraphBlock::class, $blockElements[0]);

		$paragraphBlockRender = new ParagraphBlockRender($blockElements[0], []);
		$this->assertInstanceOf(ParagraphBlockRender::class, $paragraphBlockRender);

		$paragraphBlockRender->render($blockElements, $this->getValues());

		/* must not exist */
		$startParagraph = $contentPart->getXPath()->query('//w:p[@id="start"]');
		$this->assertEquals(0, $startParagraph->length);

		/* must not exist */
		$endParagraph = $contentPart->getXPath()->query('//w:p[@id="end"]');
		$this->assertEquals(0, $endParagraph->length);

		/* there must be exactly 22 */
		$endBlock = $contentPart->getXPath()->query('//w:p[@type="content"]');
		$this->assertEquals(22, $endBlock->length);

	}

	/**
	 * @test
	 * @throws AppException
	 * @throws Exception
	 */
	public function renderParagraphBlockInCorrectSequence(): void {

		$values = $this->getValues();
		$contentPart = $this->getContentPart();
		$blockElements = $contentPart->getBlockElements();

		$this->assertCount(1, $blockElements);
		$this->assertInstanceOf(ParagraphBlock::class, $blockElements[0]);

		$paragraphBlockRender = new ParagraphBlockRender($blockElements[0], []);
		$this->assertInstanceOf(ParagraphBlockRender::class, $paragraphBlockRender);

		$paragraphBlockRender->render($blockElements, $this->getValues());

		/* there must be exactly 22 */
		$contentElements = $contentPart->getXPath()->query('//w:p[@type="content"]');
		$this->assertEquals(22, $contentElements->length);

		/* check correct sequence */
		for ($i = 0; $i < $contentElements->length; $i++) {
			$this->assertTrue(StringUtils::contains(trim($contentElements->item($i)->nodeValue), $values['blocks'][0][$i][1786]));
		}

	}

	/**
	 * @return array
	 */
	#[ArrayShape(['fields' => "array", 'blocks' => "\array[][]"])]
	private function getValues(): array {
		return [
			'fields' =>
				[
					'rechnung_id' => 2796,
					1757          => null,
					1744          => 'Frau',
					1752          => null,
					1745          => 'Michaela',
					1747          => 'Hüneke',
					1748          => 'Am Edelhof 7a',
					1749          => '28832',
					1750          => 'Achim',
					1858          => null,
					1860          => null,
					1862          => null,
					1863          => null,
					1760          => null,
					1761          => null,
					1762          => null,
					1741          => '4200007',
					1742          => '2021-02-11',
					1768          => null,
					1765          => 'Pflegedidaktik',
					1921          => null,
					1771          => 45000,
					1918          => 4500,
					1769          => '2020-04-01',
					1770          => '2022-02-19',
					1772          => 49500,
					1780          => '1',
					1922          => null,
					1783          => '1',
					1676          => 'PL - 10116',
					68            => null,
				],
			'blocks' =>
				[
					0 =>
						[
							0  =>
								[
									'rechnung_id' => 2796,
									1787          => 2250,
									1786          => '2020-04-01',
								],
							1  =>
								[
									'rechnung_id' => 2796,
									1787          => 2250,
									1786          => '2020-05-01',
								],
							2  =>
								[
									'rechnung_id' => 2796,
									1787          => 2250,
									1786          => '2020-06-01',
								],
							3  =>
								[
									'rechnung_id' => 2796,
									1787          => 2250,
									1786          => '2020-07-01',
								],
							4  =>
								[
									'rechnung_id' => 2796,
									1787          => 2250,
									1786          => '2020-08-01',
								],
							5  =>
								[
									'rechnung_id' => 2796,
									1787          => 2250,
									1786          => '2020-09-01',
								],
							6  =>
								[
									'rechnung_id' => 2796,
									1787          => 2250,
									1786          => '2020-10-01',
								],
							7  =>
								[
									'rechnung_id' => 2796,
									1787          => 2250,
									1786          => '2020-11-01',
								],
							8  =>
								[
									'rechnung_id' => 2796,
									1787          => 2250,
									1786          => '2020-12-01',
								],
							9  =>
								[
									'rechnung_id' => 2796,
									1787          => 2250,
									1786          => '2021-01-01',
								],
							10 =>
								[
									'rechnung_id' => 2796,
									1787          => 2250,
									1786          => '2021-02-01',
								],
							11 =>
								[
									'rechnung_id' => 2796,
									1787          => 2250,
									1786          => '2021-03-01',
								],
							12 =>
								[
									'rechnung_id' => 2796,
									1787          => 2250,
									1786          => '2021-04-01',
								],
							13 =>
								[
									'rechnung_id' => 2796,
									1787          => 2250,
									1786          => '2021-05-01',
								],
							14 =>
								[
									'rechnung_id' => 2796,
									1787          => 2250,
									1786          => '2021-06-01',
								],
							15 =>
								[
									'rechnung_id' => 2796,
									1787          => 2250,
									1786          => '2021-07-01',
								],
							16 =>
								[
									'rechnung_id' => 2796,
									1787          => 2250,
									1786          => '2021-08-01',
								],
							17 =>
								[
									'rechnung_id' => 2796,
									1787          => 2250,
									1786          => '2021-09-01',
								],
							18 =>
								[
									'rechnung_id' => 2796,
									1787          => 2250,
									1786          => '2021-10-01',
								],
							19 =>
								[
									'rechnung_id' => 2796,
									1787          => 2250,
									1786          => '2021-11-01',
								],
							20 =>
								[
									'rechnung_id' => 2796,
									1787          => 2250,
									1786          => '2021-12-01',
								],
							21 =>
								[
									'rechnung_id' => 2796,
									1787          => 2250,
									1786          => '2022-01-01',
								],
						],
				],
		];
	}

}