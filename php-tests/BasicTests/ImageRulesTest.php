<?php

use kalanis\kw_rules\Rules\File;
use kalanis\kw_rules\Exceptions\RuleException;


class ImageRulesTest extends CommonTestClass
{
    /**
     * @throws RuleException
     */
    public function testImageExists(): void
    {
        $data = new File\ImageIs();
        $this->assertInstanceOf(File\AFileRule::class, $data);
        $data->validate($this->getMockImage());
    }

    /**
     * @throws RuleException
     */
    public function testImageNotExists(): void
    {
        $data = new File\ImageIs();
        $this->expectException(RuleException::class);
        $data->validate($this->getMockFile());
    }

    /**
     * @param string $maxSizeX
     * @param string $maxSizeY
     * @param bool $matchEquals
     * @throws RuleException
     * @dataProvider sizeMatchProvider
     */
    public function testImageMatchSize(string $maxSizeX, string $maxSizeY, bool $matchEquals): void
    {
        $data = new File\ImageSizeEquals();
        $this->assertInstanceOf(File\AFileRule::class, $data);
        $data->setAgainstValue([$maxSizeX, $maxSizeY]);
        if (!$matchEquals) $this->expectException(RuleException::class);
        $data->validate($this->getMockImage());
    }

    public function testImageMatchSizeFail(): void
    {
        $data = new File\ImageSizeEquals();
        $this->expectException(RuleException::class);
        $data->setAgainstValue('not array');
    }

    /**
     * @param string $maxSizeX
     * @param string $maxSizeY
     * @param bool $matchEquals
     * @param bool $matchMin
     * @param bool $matchMax
     * @throws RuleException
     * @dataProvider sizeMatchProvider
     */
    public function testImageMatchListSize(string $maxSizeX, string $maxSizeY, bool $matchEquals, bool $matchMin, bool $matchMax): void
    {
        $data = new File\ImageSizeList();
        $this->assertInstanceOf(File\AFileRule::class, $data);
        $data->setAgainstValue([[$maxSizeX, $maxSizeY]]);
        if (!$matchEquals) $this->expectException(RuleException::class);
        $data->validate($this->getMockImage());
    }

    public function testImageMatchListSizeFailString(): void
    {
        $data = new File\ImageSizeList();
        $this->expectException(RuleException::class);
        $data->setAgainstValue('abcdef');
    }

    public function testImageMatchListSizeFailSimpleArray(): void
    {
        $data = new File\ImageSizeList();
        $this->expectException(RuleException::class);
        $data->setAgainstValue(['12', '34']);
    }

    /**
     * @param string $maxSizeX
     * @param string $maxSizeY
     * @param bool $matchEquals
     * @param bool $matchMin
     * @param bool $matchMax
     * @throws RuleException
     * @dataProvider sizeMatchProvider
     */
    public function testImageMatchMinSize(string $maxSizeX, string $maxSizeY, bool $matchEquals, bool $matchMin, bool $matchMax): void
    {
        $data = new File\ImageSizeMin();
        $this->assertInstanceOf(File\AFileRule::class, $data);
        $data->setAgainstValue([$maxSizeX, $maxSizeY]);
        if (!$matchMin) $this->expectException(RuleException::class);
        $data->validate($this->getMockImage());
    }

    public function testImageMatchMinSizeFail(): void
    {
        $data = new File\ImageSizeMin();
        $this->assertInstanceOf(File\AFileRule::class, $data);
        $this->expectException(RuleException::class);
        $data->setAgainstValue('123456');
    }

    /**
     * @param string $maxSizeX
     * @param string $maxSizeY
     * @param bool $matchEquals
     * @param bool $matchMin
     * @param bool $matchMax
     * @throws RuleException
     * @dataProvider sizeMatchProvider
     */
    public function testImageMatchMaxSize(string $maxSizeX, string $maxSizeY, bool $matchEquals, bool $matchMin, bool $matchMax): void
    {
        $data = new File\ImageSizeMax();
        $this->assertInstanceOf(File\AFileRule::class, $data);
        $data->setAgainstValue([$maxSizeX, $maxSizeY]);
        if (!$matchMax) $this->expectException(RuleException::class);
        $data->validate($this->getMockImage());
    }

    public function testImageMatchMaxSizeFail(): void
    {
        $data = new File\ImageSizeMax();
        $this->assertInstanceOf(File\AFileRule::class, $data);
        $this->expectException(RuleException::class);
        $data->setAgainstValue('123456');
    }

    public function sizeMatchProvider(): array
    {
        return [
            ['6', '5', true,  true,  true ],
            ['5', '6', false, false, false],
            ['5', '5', false, true,  false],
            ['6', '6', false, false, true ],
            ['4', '0', false, true,  false],
            ['0', '7', false, false, true ],
        ];
    }
}
