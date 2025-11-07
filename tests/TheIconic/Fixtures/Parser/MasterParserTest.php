<?php

namespace TheIconic\Fixtures\Test\Parser;

use PHPUnit\Framework\TestCase;
use TheIconic\Fixtures\Parser\MasterParser;
use TheIconic\Fixtures\Parser\ParserInterface;

class MasterParserTest extends TestCase
{
    const TESTS_FIXTURES_DIRECTORY = './tests/Support/TestsFixtures/';

    const CURRENT_NUMBER_OF_PARSERS = 3;

    /**
     * @var MasterParser
     */
    private $parserInstance;

    public function setUp(): void
    {
        $this->parserInstance = new MasterParser();
    }

    public function testYamlParseViaMaster()
    {
        $fixtureFile = self::TESTS_FIXTURES_DIRECTORY . 'country_region.yml';

        $fixtureCountry = $this->parserInstance->parse($fixtureFile);

        $this->assertInstanceOf('TheIconic\Fixtures\Fixture\Fixture', $fixtureCountry);
    }

    public function testXmlParseViaMaster()
    {
        $fixtureFile = self::TESTS_FIXTURES_DIRECTORY . 'customer_address_region_suburb.xml';

        $fixtureAddress = $this->parserInstance->parse($fixtureFile);

        $this->assertInstanceOf('TheIconic\Fixtures\Fixture\Fixture', $fixtureAddress);
    }

    public function testGetAvailableParsers()
    {
        $availableParsers = $this->parserInstance->getAvailableParsers();

        $this->assertCount(self::CURRENT_NUMBER_OF_PARSERS, $availableParsers);

        foreach ($availableParsers as $parser) {
            $this->assertInstanceOf(ParserInterface::class, $parser);
        }
    }

    public function testFakeInvalidParserException()
    {
        $this->expectException(\TheIconic\Fixtures\Exception\InvalidParserException::class);

        $parserInstance = $this->getMockBuilder(MasterParser::class)
                               ->onlyMethods(['getParserNames'])
                               ->disableOriginalConstructor()
                               ->getMock();

        $parserInstance->expects($this->any())
            ->method('getParserNames')
            ->willReturn(['FakeParser']);

        $parserInstance->getAvailableParsers();
    }

    public function testRougueInvalidParserException()
    {
        $this->expectException(\TheIconic\Fixtures\Exception\InvalidParserException::class);

        $parserInstance = $this->getMockBuilder(MasterParser::class)
            ->onlyMethods(['getParserInstance'])
            ->getMock();

        $parserInstance->expects($this->any())
            ->method('getParserInstance')
            ->willReturn(null);

        $parserInstance->getAvailableParsers();
    }

    public function testParserNotFoundException()
    {
        $this->expectException(\TheIconic\Fixtures\Exception\ParserNotFoundException::class);

        $this->parserInstance->parse(self::TESTS_FIXTURES_DIRECTORY . 'fake.txt');
    }
}
