<?php

use kalanis\kw_rules\Rules;
use kalanis\kw_rules\Exceptions\RuleException;


class CallbackRulesTest extends CommonTestClass
{
    /**
     * @param mixed $expectedCall
     * @param mixed $checkValue
     * @param bool $gotResult
     * @param bool $pass
     * @throws RuleException
     * @dataProvider callbackProvider
     */
    public function testCallback($expectedCall, $checkValue, bool $gotResult, bool $pass): void
    {
        $data = new Rules\ProcessCallback();
        $this->assertInstanceOf(Rules\ARule::class, $data);
        if (!$gotResult) $this->expectException(RuleException::class);
        $data->setAgainstValue($expectedCall);
        if ($gotResult) {
            $mock = MockEntry::init('foo', $checkValue);
            if (!$pass) $this->expectException(RuleException::class);
            $data->validate($mock);
        }
    }

    public function callbackProvider(): array
    {
        return [
            [false, false, false, false],
            ['', '', false, false],
            [123, '', false, false],
            ['asdf', '', false, false],
            ['CallbackRulesTest::callMeStatic', null, true, false],
            ['CallbackRulesTest::callMeStatic', 1, true, true ],
            [[$this, 'callMeDynamic'], null, true, false],
            [[$this, 'callMeDynamic'], 1, true, true],
        ];
    }

    public static function callMeStatic($param): bool
    {
        return !empty($param);
    }

    public function callMeDynamic($param): bool
    {
        return !empty($param);
    }
}
