<?php

use kalanis\kw_rules\Exceptions\RuleException;
use kalanis\kw_rules\Interfaces\IRules;
use kalanis\kw_rules\Rules;


class BasicFactoryTest extends CommonTestClass
{
    /**
     * @throws RuleException
     */
    public function testFactory(): void
    {
        $factory = new Rules\Factory();
        $data = $factory->getRule(IRules::SATISFIES_CALLBACK); // known to factory
        $this->assertInstanceOf(Rules\ARule::class, $data);
        $this->expectException(RuleException::class);
        $factory->getRule(IRules::IMAGE_MAX_DIMENSION); // not set in factory
    }

    /**
     * @param string $rule
     * @param bool $gotResult
     * @throws RuleException
     * @dataProvider inputsProvider
     */
    public function testFactoryAvailability(string $rule, bool $gotResult): void
    {
        $factory = new Rules\Factory();
        if (!$gotResult) $this->expectException(RuleException::class);
        $data = $factory->getRule($rule);
        if ($data) $this->assertInstanceOf(Rules\ARule::class, $data);
    }

    public function inputsProvider(): array
    {
        return [
            [IRules::MATCH_ALL, true],
            [IRules::MATCH_ANY, true],
            [IRules::MATCH_ENTRY, true],
            [IRules::ALWAYS, true],
            [IRules::EQUALS, true],
            [IRules::NOT_EQUALS, true],
            [IRules::IN_ARRAY, true],
            [IRules::NOT_IN_ARRAY, true],
            [IRules::IS_GREATER_THAN, true],
            [IRules::IS_LOWER_THAN, true],
            [IRules::IS_GREATER_THAN_EQUALS, true],
            [IRules::IS_LOWER_THAN_EQUALS, true],
            [IRules::IS_NUMERIC, true],
            [IRules::IS_STRING, true],
            [IRules::IS_BOOL, true],
            [IRules::MATCHES_PATTERN, true],
            [IRules::LENGTH_MIN, true],
            [IRules::LENGTH_MAX, true],
            [IRules::LENGTH_EQUALS, true],
            [IRules::IN_RANGE, true],
            [IRules::IN_RANGE_EQUALS, true],
            [IRules::NOT_IN_RANGE, true],
            [IRules::NOT_IN_RANGE_EQUALS, true],
            [IRules::IS_FILLED, true],
            [IRules::IS_NOT_EMPTY, true],
            [IRules::IS_EMPTY, true],
            [IRules::SATISFIES_CALLBACK, true],
            [IRules::IS_EMAIL, true],
            [IRules::IS_DOMAIN, true],
            [IRules::URL_EXISTS, true],
            [IRules::IS_ACTIVE_DOMAIN, true],
            [IRules::IS_JSON_STRING, true],

            [IRules::FILE_EXISTS, false],
            [IRules::FILE_SENT, false],
            [IRules::FILE_RECEIVED, false],
            [IRules::FILE_MAX_SIZE, false],
            [IRules::FILE_MIMETYPE_IN_LIST, false],
            [IRules::FILE_MIMETYPE_EQUALS, false],
            [IRules::IS_IMAGE, false],
            [IRules::IMAGE_DIMENSION_EQUALS, false],
            [IRules::IMAGE_DIMENSION_IN_LIST, false],
            [IRules::IMAGE_MAX_DIMENSION, false],
            [IRules::IMAGE_MIN_DIMENSION, false],

            [IRules::IS_POST_CODE, false],
            [IRules::IS_TELEPHONE, false],
            [IRules::IS_EU_VAT, false],
            [IRules::IS_DATE, true],
            [IRules::IS_DATE_REGEX, true],

            [IRules::SAFE_EQUALS_BASIC, true],
            [IRules::SAFE_EQUALS_FUNC, true],
            [IRules::SAFE_EQUALS_PASS, true],
        ];
    }

    /**
     * @throws RuleException
     */
    public function testShittySettingInstance(): void
    {
        $factory = new XFactory();
        $this->expectException(RuleException::class);
        $factory->getRule('not_instance');
    }

    /**
     * @throws RuleException
     */
    public function testShittySettingClass(): void
    {
        $factory = new XFactory();
        $this->expectException(RuleException::class);
        $factory->getRule('not_class');
    }
}


class XFactory extends Rules\Factory
{
    /** @var array<string, string> */
    protected static $map = [
        IRules::EQUALS => Rules\Equals::class,
        'not_instance' => \stdClass::class,
        'not_class'    => 'this_is_not_a_class',
    ];
}
