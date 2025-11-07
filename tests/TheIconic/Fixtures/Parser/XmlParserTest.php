<?php

namespace TheIconic\Fixtures\Test\Parser;

use PHPUnit\Framework\TestCase;
use TheIconic\Fixtures\Parser\XmlParser;

class XmlParserTest extends TestCase
{
    const TESTS_FIXTURES_DIRECTORY = './tests/Support/TestsFixtures/';

    /**
     * @var XMLParser
     */
    private $parserInstance;

    /**
     *
     */
    public function setUp(): void
    {
        $this->parserInstance = new XmlParser();
    }

    /**
     *
     */
    public function testParse()
    {
        $fixtureFile = self::TESTS_FIXTURES_DIRECTORY . 'customer_address_region_suburb.xml';

        $fixture = $this->parserInstance->parse($fixtureFile);

        $this->assertCount(16644, $fixture);
        $this->assertEquals('customer_address_region_suburb', $fixture->getName());
        $this->assertInstanceOf('TheIconic\Fixtures\Fixture\Fixture', $fixture);
    }

    public function testParseException()
    {
        $this->expectException(\TheIconic\Fixtures\Exception\InvalidParserException::class);

        $fixtureFile = self::TESTS_FIXTURES_DIRECTORY . 'currency_conversion_damage.xml';

        $this->parserInstance->parse($fixtureFile);
    }
}
