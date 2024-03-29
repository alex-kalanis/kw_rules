<?php

use kalanis\kw_rules\Rules;
use kalanis\kw_rules\Interfaces\IRules;
use kalanis\kw_rules\Exceptions\RuleException;


class MatchRulesTest extends CommonTestClass
{
    /**
     * @throws RuleException
     */
    public function testNoMatchInitial(): void
    {
        $data = new Rules\MatchAll();
        $this->expectException(RuleException::class);
        $data->setAgainstValue('abc');
    }

    /**
     * @throws RuleException
     */
    public function testNoMatchStr(): void
    {
        $data = new Rules\MatchAll();
        $this->expectException(RuleException::class);
        $data->setAgainstValue(['abc', 'def', 'ghi', 'jkl']);
    }

    /**
     * @throws RuleException
     */
    public function testNoMatchObj(): void
    {
        $data = new Rules\MatchAll();
        $this->expectException(RuleException::class);
        $data->setAgainstValue([MockEntry::init('abc', 'def'), MockEntry::init('ghi', 'jkl'), ]);
    }

    /**
     * @throws RuleException
     */
    public function testProcessErrors(): void
    {
        $data = new Rules\MatchAll();
        $data->setAgainstValue([$this->initDeny(), $this->initDeny()]);
        try {
            $mock = MockEntry::init('foo', 'bar');
            $data->validate($mock);
        } catch (RuleException $ex) {
            // no catch - no errors - be risky and that's okay here
            $got = $ex->getPrev();
            $this->assertNotEmpty($got);
            $this->assertInstanceOf(RuleException::class, $got);
        }
    }

    /**
     * @param IRules[] $expectedValue
     * @param bool $matchAll
     * @param bool $matchAny
     * @throws RuleException
     * @dataProvider matchProvider
     */
    public function testMatchAll(array $expectedValue, bool $matchAll, bool $matchAny): void
    {
        $data = new Rules\MatchAll();
        $data->setAgainstValue($expectedValue);
        $this->assertInstanceOf(Rules\ARule::class, $data);

        $mock = MockEntry::init('foo', 'bar');
        if (!$matchAll) $this->expectException(RuleException::class);
        $data->validate($mock);
    }

    /**
     * @param IRules[] $expectedValue
     * @param bool $matchAll
     * @param bool $matchAny
     * @throws RuleException
     * @dataProvider matchProvider
     */
    public function testMatchAny(array $expectedValue, bool $matchAll, bool $matchAny): void
    {
        $data = new Rules\MatchAny();
        $data->setAgainstValue($expectedValue);
        $this->assertInstanceOf(Rules\ARule::class, $data);
        $mock = MockEntry::init('foo', 'bar');
        if (!$matchAny) $this->expectException(RuleException::class);
        $data->validate($mock);
    }

    public function matchProvider(): array
    {
        return [
            [[$this->initPass(), $this->initPass()], true, true], // pass all
            [[$this->initPass(), $this->initDeny()], false, true], // pass partial
            [[$this->initDeny(), $this->initDeny()], false, false], // pass nothing
        ];
    }

    public function testMatchEntryFail1(): void
    {
        $data = new Rules\MatchByEntry();
        $this->assertInstanceOf(Rules\ARule::class, $data);
        $this->expectException(RuleException::class);
        $data->setAgainstValue('just string');
    }

    public function testMatchEntryFail2(): void
    {
        $data = new Rules\MatchByEntry();
        $this->assertInstanceOf(Rules\ARule::class, $data);
        $this->expectException(RuleException::class);
        $data->setAgainstValue(new \stdClass());
    }

    public function testMatchEntryPass(): void
    {
        $data = new Rules\MatchByEntry();
        $this->assertInstanceOf(Rules\ARule::class, $data);

        $mock1 = MockEntry::init('foo', 'bar');
        $mock1->addRule(IRules::IS_NOT_EMPTY, 'problems: none');
        $data->setAgainstValue($mock1);
        $mock2 = MockEntry::init('baz', 'bar');
        $mock2->addRule(IRules::EQUALS, 'pass, because it is okay');
        $data->validate($mock2);
    }

    public function testMatchEntrySub(): void
    {
        $data = new Rules\MatchByEntry();
        $this->assertInstanceOf(Rules\ARule::class, $data);

        $mock1 = MockEntry::init('foo', 'bar');
        $mock1->addRule(IRules::IS_NOT_EMPTY, 'problems: none');
        $data->setAgainstValue($mock1);
        $mock2 = MockEntry::init('baz', 'bar');
        $mock2->addRule(IRules::EQUALS, 'pass, because it is okay');
        $mock1->addRule(IRules::IS_NUMERIC, 'add something which make it fail');
        $this->expectException(RuleException::class);
        $data->validate($mock2);
    }

    protected function initPass(): Rules\ProcessCallback
    {
        $callback = new Rules\ProcessCallback();
        $callback->setAgainstValue('MatchRulesTest::callMePass');
        return $callback;
    }

    public static function callMePass(...$args): bool
    {
        return true;
    }

    protected function initDeny(): Rules\ProcessCallback
    {
        $callback = new Rules\ProcessCallback();
        $callback->setAgainstValue('MatchRulesTest::callMeDeny');
        return $callback;
    }

    public static function callMeDeny(...$args): bool
    {
        return false;
    }
}
