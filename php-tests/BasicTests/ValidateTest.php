<?php

use kalanis\kw_rules\Rules;
use kalanis\kw_rules\Exceptions\RuleException;
use kalanis\kw_rules\Interfaces;
use kalanis\kw_rules\TValidate;


class ValidateTest extends CommonTestClass
{
    /**
     * @throws RuleException
     */
    public function testSimple()
    {
        $validate = new MockValidate();
        $this->assertEmpty($validate->getRules());
        $validate->addRule(Interfaces\IRules::IS_FILLED, 'Not filled');
        $validate->addRule(Interfaces\IRules::IS_STRING, 'Not string');
        $this->assertTrue($validate->validate(MockEntry::init('foo', 'bar')));
        $this->assertEmpty($validate->getErrors());
    }

    /**
     * @throws RuleException
     */
    public function testFailed()
    {
        $validate = new MockValidate();
        $this->assertEmpty($validate->getRules());
        $validate->addRule(Interfaces\IRules::IS_FILLED, 'Not filled');
        $validate->addRule(Interfaces\IRules::IS_STRING, 'Not string');
        $this->assertFalse($validate->validate(MockEntry::init('baz', 0)));
        $this->assertNotEmpty($validate->getErrors());
    }

    /**
     * @throws RuleException
     */
    public function testOr()
    {
        $validate = new MockValidate();
        $this->assertEmpty($validate->getRules());
        $validate->addRule(Interfaces\IRules::IS_STRING, 'Not string');
        $validate->addRule(Interfaces\IRules::IS_NUMERIC, 'Not number');
        $presetRules = $validate->getRules();
        $validate->removeRules();
        $validate->addRule(Interfaces\IRules::IS_FILLED, 'Not filled');
        $validate->addRule(Interfaces\IRules::MATCH_ANY, 'Must be following', $presetRules);
        $this->assertTrue($validate->validate(MockEntry::init('vfr', 75)));
        $this->assertEmpty($validate->getErrors());
    }

    /**
     * @throws RuleException
     */
    public function testOrFail()
    {
        $validate = new MockValidate();
        $this->assertEmpty($validate->getRules());
        $validate->addRule(Interfaces\IRules::IS_STRING, 'Not string');
        $validate->addRule(Interfaces\IRules::IS_BOOL, 'Not boolean');
        $presetRules = $validate->getRules();
        $validate->removeRules();
        $validate->addRule(Interfaces\IRules::IS_FILLED, 'Not filled');
        $validate->addRule(Interfaces\IRules::MATCH_ANY, 'Must be following', $presetRules);
        $presetRules = $validate->getRules();
        $validate->removeRules();
        $this->assertEmpty($validate->getRules());
        $validate->addRules($presetRules);
        $this->assertFalse($validate->validate(MockEntry::init('vfr', 75)));
        $this->assertNotEmpty($validate->getErrors());
    }

    /**
     * @throws RuleException
     */
    public function testAddFile()
    {
        $validate = new MockValidateFile();
        $this->assertEmpty($validate->getRules());
        $validate->addRule(Interfaces\IRules::FILE_RECEIVED, 'Must be received');
        $validate->addRule(Interfaces\IRules::FILE_SENT, 'Must be sent');
        $presetRules = $validate->getRules();
        $validate->removeRules();
        $this->assertEmpty($validate->getRules());
        $validate->addRules($presetRules);
        $this->assertFalse($validate->validate($this->getMockNoFile()));
        $this->assertNotEmpty($validate->getErrors());
    }
}


class MockValidate
{
    use TValidate;

    protected function whichFactory(): Interfaces\IRuleFactory
    {
        return new Rules\Factory();
    }
}


class MockValidateFile
{
    use TValidate;

    protected function whichFactory(): Interfaces\IRuleFactory
    {
        return new Rules\File\Factory();
    }
}
