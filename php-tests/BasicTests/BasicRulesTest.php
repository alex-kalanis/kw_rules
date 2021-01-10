<?php

use kalanis\kw_rules\Rules;
use kalanis\kw_rules\Exceptions\RuleException;


class BasicRulesTest extends CommonTestClass
{
    /**
     * @param string $key
     * @param string $expectedValue
     * @param string $checkValue
     * @param bool $gotResult
     * @throws RuleException
     * @dataProvider equalsProvider
     */
    public function testEquals(string $key, string $expectedValue, string $checkValue, bool $gotResult)
    {
        $data = new Rules\Equals();
        $data->setErrorText('Custom error');
        $data->setAgainstValue($expectedValue);
        $this->assertInstanceOf('\kalanis\kw_rules\Rules\ARule', $data);

        $mock = MockEntry::init($key, $checkValue);
        if (!$gotResult) $this->expectException(RuleException::class);
        $data->validate($mock);
    }

    /**
     * @param string $key
     * @param string $expectedValue
     * @param string $checkValue
     * @param bool $gotResult
     * @throws RuleException
     * @dataProvider equalsProvider
     */
    public function testNotEquals(string $key, string $expectedValue, string $checkValue, bool $gotResult)
    {
        $data = new Rules\NotEquals();
        $data->setAgainstValue($expectedValue);
        $this->assertInstanceOf('\kalanis\kw_rules\Rules\ARule', $data);
        $mock = MockEntry::init($key, $checkValue);
        if ($gotResult) $this->expectException(RuleException::class);
        $data->validate($mock);
    }

    /**
     * @param string $expectedValue
     * @param string $checkValue
     * @param bool $gotResult
     * @throws RuleException
     * @dataProvider compareGtProvider
     */
    public function testGreaterThan(string $expectedValue, string $checkValue, bool $gotResult)
    {
        $data = new Rules\GreaterThan();
        $data->setAgainstValue($expectedValue);
        $this->assertInstanceOf('\kalanis\kw_rules\Rules\ARule', $data);
        $mock = MockEntry::init('foo', $checkValue);
        if (!$gotResult) $this->expectException(RuleException::class);
        $data->validate($mock);
    }

    /**
     * @param string $expectedValue
     * @param string $checkValue
     * @param bool $gotResult
     * @throws RuleException
     * @dataProvider compareLqProvider
     */
    public function testGreaterEquals(string $expectedValue, string $checkValue, bool $gotResult)
    {
        $data = new Rules\GreaterEquals();
        $data->setAgainstValue($expectedValue);
        $this->assertInstanceOf('\kalanis\kw_rules\Rules\ARule', $data);
        $mock = MockEntry::init('foo', $checkValue);
        if (!$gotResult) $this->expectException(RuleException::class);
        $data->validate($mock);
    }

    /**
     * @param string $expectedValue
     * @param string $checkValue
     * @param bool $gotResult
     * @throws RuleException
     * @dataProvider compareLqProvider
     */
    public function testLesserThan(string $expectedValue, string $checkValue, bool $gotResult)
    {
        $data = new Rules\LesserThan();
        $data->setAgainstValue($expectedValue);
        $this->assertInstanceOf('\kalanis\kw_rules\Rules\ARule', $data);
        $mock = MockEntry::init('foo', $checkValue);
        if ($gotResult) $this->expectException(RuleException::class);
        $data->validate($mock);
    }

    /**
     * @param string $expectedValue
     * @param string $checkValue
     * @param bool $gotResult
     * @throws RuleException
     * @dataProvider compareGtProvider
     */
    public function testLesserEquals(string $expectedValue, string $checkValue, bool $gotResult)
    {
        $data = new Rules\LesserEquals();
        $data->setAgainstValue($expectedValue);
        $this->assertInstanceOf('\kalanis\kw_rules\Rules\ARule', $data);
        $mock = MockEntry::init('foo', $checkValue);
        if ($gotResult) $this->expectException(RuleException::class);
        $data->validate($mock);
    }

    public function compareGtProvider()
    {
        return [
            ['5', '7', true],
            ['7', '5', false],
            ['6', '6', false],
        ];
    }

    public function compareLqProvider()
    {
        return [
            ['4', '8', true],
            ['8', '4', false],
            ['6', '6', true],
        ];
    }

    /**
     * @param mixed $checkValue
     * @param bool $gotResult
     * @throws RuleException
     * @dataProvider compareFillProvider
     */
    public function testEmpty($checkValue, bool $gotResult)
    {
        $data = new Rules\IsEmpty();
        $this->assertInstanceOf('\kalanis\kw_rules\Rules\ARule', $data);
        $mock = MockEntry::init('foo', $checkValue);
        if ($gotResult) $this->expectException(RuleException::class);
        $data->validate($mock);
    }

    /**
     * @param mixed $checkValue
     * @param bool $gotResult
     * @throws RuleException
     * @dataProvider compareFillProvider
     */
    public function testFilled($checkValue, bool $gotResult)
    {
        $data = new Rules\IsFilled();
        $this->assertInstanceOf('\kalanis\kw_rules\Rules\ARule', $data);
        $mock = MockEntry::init('foo', $checkValue);
        if (!$gotResult) $this->expectException(RuleException::class);
        $data->validate($mock);
    }

    public function compareFillProvider()
    {
        return [
            [false, false],
            ['', false],
            [123, true],
            ['asdf', true],
            [MockEntry::init('foo', 'bar'), true],
        ];
    }

    /**
     * @param mixed $checkValue
     * @param bool $gotResult
     * @throws RuleException
     * @dataProvider compareJsonProvider
     */
    public function testJson($checkValue, bool $gotResult)
    {
        $data = new Rules\IsJsonString();
        $this->assertInstanceOf('\kalanis\kw_rules\Rules\ARule', $data);
        $mock = MockEntry::init('foo', $checkValue);
        if (!$gotResult) $this->expectException(RuleException::class);
        $data->validate($mock);
    }

    public function compareJsonProvider()
    {
        return [
            [false, false],
            ['', false],
            [123, true],
            ['asdf', false],
            [MockEntry::init('foo', 'bar'), false],
            ['{}', true],
            ['{"key": "value is more"}', true],
        ];
    }
}
