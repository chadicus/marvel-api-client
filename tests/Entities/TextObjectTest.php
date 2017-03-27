<?php
namespace Chadicus\Marvel\Api\Entities;

/**
 * Unit tests for the TextObject class.
 *
 * @coversDefaultClass \Chadicus\Marvel\Api\Entities\TextObject
 * @covers ::<protected>
 */
final class TextObjectTest extends \PHPUnit\Framework\TestCase
{
    /**
     * Verify basic behavior of fromArray().
     *
     * @test
     *
     * @return void
     */
    public function fromArray()
    {
        $input = [
            'language' => 'a language',
            'type' => 'a type',
            'text' => 'some text',
        ];

        $textObject = TextObject::fromArray($input);

        $this->assertSame($input['language'], $textObject->getLanguage());
        $this->assertSame($input['type'], $textObject->getType());
        $this->assertSame($input['text'], $textObject->getText());
    }
}
