<?php

require_once('./SomeClass.php');

class SomeClassTest extends PHPUnit\Framework\TestCase {

    private $someClass;

    protected function setUp() : void 
    {
        $this->someClass = new SomeClass();
    }

    protected function tearDown() : void
    {
        $this->someClass = NULL;
    }

    public function revertCharactersProvider() 
    {
        return [
            ['Съешь этих мягких французских булок, dude!', 'Ьшеъс хитэ хикгям хиксзуцнарф колуб, edud!'],
            ['Hello, World!', 'Olleh, Dlrow!'],
            ['Здрасте', 'Етсардз'],
            ['Programming is fun!', 'Gnimmargorp si nuf!'],
            ['12345!?@#', '54321!?@#'],
            ['AaBbCcDdEe', 'EeDdCcBbAa'],
            ['Testing 123... Testing 123...', 'Gnitset 321... Gnitset 321...'],
            ['!.,,... ', '!.,,... '],
            ['     ', '     '],
            ['AaЫы', 'ЫыAa'],
            [' oPopop !$%^ ', ' pOpopo !$%^ '],
            ['🐱🐱🐱', '🐱🐱🐱'],
        ];
    }


    /**
     * @dataProvider revertCharactersProvider
     */
    public function testRevertCharacters($str, $expected) 
    {
        $result = $this->someClass->revertCharacters($str);
        $this->assertEquals($expected, $result);
    }

}

