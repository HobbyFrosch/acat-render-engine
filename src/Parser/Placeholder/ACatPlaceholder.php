<?php

namespace ACAT\Parser\Placeholder;

use DOMDocument;
use DOMNode;
use ACAT\Utils\StringUtils;
use Symfony\Component\Uid\Uuid;
use ACAT\Exception\PlaceholderException;

/**
 *
 */
abstract class ACatPlaceholder {

	/**
	 * @var string
	 */
	protected string $id;

	/**
	 * @var string
	 */
	protected string $namespace = 'https://schemas.acat.akademie.uni-bremen.de';

	/**
	 * ACatNode constructor.
	 */
	public function __construct() {
		$this->id = Uuid::v4();
	}

	/**
	 * @return string
	 */
	public function getId() : string {
		return $this->id;
	}

    /**
     * @param string $placeHolderStr
     * @return ACatPlaceholder
     * @throws PlaceholderException
     */
	static function getPlaceholder(string $placeHolderStr) : ACatPlaceholder {

        $placeHolderStr = str_replace(['$', '{', '}'], '', $placeHolderStr);

		if (StringUtils::startsWith($placeHolderStr, 'F')) {
			$placeHolderObj = self::createFieldPlaceholder($placeHolderStr);
		}
		else if (StringUtils::startsWith($placeHolderStr, 'C')) {
			$placeHolderObj = self::createConditionPlaceholder($placeHolderStr);
		}
		else if (StringUtils::startsWith($placeHolderStr, 'T')) {
			$placeHolderObj = self::createTextPlaceholder($placeHolderStr);
		}
		else if (StringUtils::startsWith($placeHolderStr, 'B')) {
			$placeHolderObj = self::createBlockPlaceholder($placeHolderStr);
		}
        else if (StringUtils::startsWith($placeHolderStr, 'V')) {
            $placeHolderObj = self::createViewPlaceHolder($placeHolderStr);
        }
		else {
			throw new PlaceholderException($placeHolderStr . ' is unsupported');
		}

		return $placeHolderObj;

	}

	/**
	 * @param string $placeholderStr
	 * @return ViewPlaceholder
	 * @throws PlaceholderException
	 */
    private static function createViewPlaceHolder(string $placeholderStr) : ViewPlaceholder {
        $params = explode(':', $placeholderStr);
        if (!$params || count($params) <> 2) {
            throw new PlaceholderException($placeholderStr . ' is malformed');
        }
        return new ViewPlaceholder($params[1]);
    }

	/**
	 * @param string $placeholderStr
	 * @return FieldPlaceholder
	 * @throws PlaceholderException
	 */
	private static function createFieldPlaceholder(string $placeholderStr) : FieldPlaceholder {
		$params = explode(':', $placeholderStr);
		if (!$params || count($params) <> 2) {
			throw new PlaceholderException($placeholderStr . ' is malformed');
		}
		return new FieldPlaceholder($params[1]);
	}

	/**
	 * @param string $placeholderStr
	 * @return TextPlaceholder
	 * @throws PlaceholderException
	 */
	private static function createTextPlaceholder(string $placeholderStr) : TextPlaceholder {
		$params = explode(':', $placeholderStr);
		if (!$params || count($params) <> 2) {
			throw new PlaceholderException($placeholderStr . ' is malformed');
		}
		return new TextPlaceholder($params[1]);
	}

	/**
	 * @param string $placeholderStr
	 * @return ConditionPlaceholder
	 * @throws PlaceholderException
	 */
	private static function createConditionPlaceholder(string $placeholderStr) : ConditionPlaceholder {
		$params = explode(':', $placeholderStr);
		if (count($params) != 4) {
			throw new PlaceholderException($placeholderStr . ' is malformed');
		}
		return new ConditionPlaceholder($params[1], $params[3], $params[2]);
	}

	/**
	 * @param string $placeholderStr
	 * @return BlockPlaceholder
	 * @throws PlaceholderException
	 */
	private static function createBlockPlaceholder(string $placeholderStr) : BlockPlaceholder {

		$params = explode(':', $placeholderStr);

		if (count($params) != 2) {
			throw new PlaceholderException($placeholderStr . ' is malformed');
		}

		if ($params[1] == 0) {
			return new StartBlockPlaceholder();
		}
		else if ($params[1] == 1) {
			return new EndBlockPlaceholder();
		}
		else {
			throw new PlaceholderException('block type ' . $params[1] . ' is not supported');
		}

	}

	/**
	 * @return string
	 */
	abstract function getXMLTagAsString() : string;

	/**
	 * @param DOMDocument $domDocument
	 * @return DOMNode
	 */
	abstract function getDOMNode(DOMDocument $domDocument) : DOMNode;

	/**
	 * @return int
	 */
	abstract function length() : int;

	/**
	 * @return string
	 */
	abstract function getNodeAsString() : string;

}