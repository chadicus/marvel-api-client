<?php
namespace Chadicus\Marvel\Api\Entities;

use DominionEnterprises\Util;

/**
 * Represents the Marvel API TextObject enttity.
 */
class TextObject extends AbstractEntity implements EntityInterface
{

    /**
     * The string description of the text object (e.g. solicit text, preview text, etc.).
     *
     * @var string
     */
    private $type;

    /**
     * A language code denoting which language the text object is written in.
     *
     * @var string
     */
    private $language;

    /**
     * The text of the text object.
     *
     * @var string
     */
    private $text;

    /**
     * Construct a new TextObject.
     *
     * @param string $type     The string description of the text object (e.g. solicit text, preview text, etc.).
     * @param string $language A language code denoting which language the text object is written in.
     * @param string $text     The text of the text object.
     */
    final public function __construct($type, $language, $text)
    {
        Util::throwIfNotType(['string' => [$type, $language, $text]], true);
        $this->type = $type;
        $this->language = $language;
        $this->text = $text;
    }

    /**
     * Returns the string description of the text object (e.g. solicit text, preview text, etc.).
     *
     * @return string
     */
    final public function getType()
    {
        return $this->type;
    }

    /**
     * Returns the language code denoting which language the text object is written in.
     *
     * @return string
     */
    final public function getLanguage()
    {
        return $this->language;
    }

    /**
     * Returns the text of the text object.
     *
     * @return string
     */
    final public function getText()
    {
        return $this->text;
    }

    /**
     * Filters the given array $input into a TextObject.
     *
     * @param array $input The value to be filtered.
     *
     * @return TextObject
     */
    final public static function fromArray(array $input)
    {
        $filters = [
            'type' => ['default' => null, ['string']],
            'language' => ['default' => null, ['string']],
            'text' => ['default' => null, ['string']]
        ];

        list($success, $result, $error) = \DominionEnterprises\Filterer::filter($filters, $input);
        Util::ensure(true, $success, $error);

        return new TextObject($result['type'], $result['language'], $result['text']);
    }
}
