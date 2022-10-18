<?php

use kalanis\kw_rules\Rules\File;
use kalanis\kw_rules\Exceptions\RuleException;


class FileRulesTest extends CommonTestClass
{
    /**
     * @throws RuleException
     */
    public function testFileExists(): void
    {
        $data = new File\FileExists();
        $this->assertInstanceOf(File\AFileRule::class, $data);
        $data->validate($this->getMockFile());
    }

    /**
     * @throws RuleException
     */
    public function testFileNotExists(): void
    {
        $data = new File\FileExists();
        $this->expectException(RuleException::class);
        $data->validate($this->getMockNoFile());
    }

    /**
     * @throws RuleException
     */
    public function testFileSent(): void
    {
        $data = new File\FileSent();
        $this->assertInstanceOf(File\AFileRule::class, $data);
        $data->validate($this->getMockFile());
    }

    /**
     * @throws RuleException
     */
    public function testFileNotSent(): void
    {
        $data = new File\FileSent();
        $this->expectException(RuleException::class);
        $data->validate($this->getMockNoFile());
    }

    /**
     * @throws RuleException
     */
    public function testFileReceived(): void
    {
        $data = new File\FileReceived();
        $this->assertInstanceOf(File\AFileRule::class, $data);
        $data->validate($this->getMockFile());
    }

    /**
     * @throws RuleException
     */
    public function testFileNotReceived(): void
    {
        $data = new File\FileReceived();
        $this->expectException(RuleException::class);
        $data->validate($this->getMockNoFile());
    }

    /**
     * @param string $maxSize
     * @param int $fileSize
     * @param bool $match
     * @throws RuleException
     * @dataProvider sizeMatchProvider
     */
    public function testFileMaxSize(string $maxSize, int $fileSize, bool $match): void
    {
        $data = new File\FileMaxSize();
        $this->assertInstanceOf(File\AFileRule::class, $data);
        $data->setAgainstValue($maxSize);
        $mock = MockFile::init('foo', 'text0.txt', 'text/plain',
            '', $fileSize, UPLOAD_ERR_OK );
        if (!$match) $this->expectException(RuleException::class);
        $data->validate($mock);
    }

    public function sizeMatchProvider(): array
    {
        return [
            ['32',  128,   false],
            ['10g', 46843, true],
            ['15m', 84641, true],
            ['30k', 3534,  true],
            ['30k', 35534, false],
        ];
    }

    /**
     * @throws RuleException
     */
    public function testFileMimeEquals(): void
    {
        $data = new File\FileMimeEquals();
        $this->assertInstanceOf(File\AFileRule::class, $data);
        $data->setAgainstValue('text/plain');
        $data->validate($this->getMockFile());
    }

    /**
     * @throws RuleException
     */
    public function testFileMimeNotEquals(): void
    {
        $data = new File\FileMimeEquals();
        $data->setAgainstValue('octet/stream');
        $this->expectException(RuleException::class);
        $data->validate($this->getMockFile());
    }

    /**
     * @throws RuleException
     */
    public function testFileMimeListFailString(): void
    {
        $data = new File\FileMimeList();
        $this->expectException(RuleException::class);
        $data->setAgainstValue('text/plain');
    }

    /**
     * @throws RuleException
     */
    public function testFileMimeListFailNumber(): void
    {
        $data = new File\FileMimeList();
        $this->expectException(RuleException::class);
        $data->setAgainstValue(123456);
    }

    /**
     * @throws RuleException
     */
    public function testFileMimeListFailClass(): void
    {
        $data = new File\FileMimeList();
        $this->expectException(RuleException::class);
        $data->setAgainstValue(new \stdClass());
    }

    /**
     * @throws RuleException
     */
    public function testFileMimeListFailArrayNumber(): void
    {
        $data = new File\FileMimeList();
        $this->expectException(RuleException::class);
        $data->setAgainstValue([123456]);
    }

    /**
     * @throws RuleException
     */
    public function testFileMimeListFailArrayClass(): void
    {
        $data = new File\FileMimeList();
        $this->expectException(RuleException::class);
        $data->setAgainstValue([new \stdClass()]);
    }

    /**
     * @throws RuleException
     */
    public function testFileMimeList(): void
    {
        $data = new File\FileMimeList();
        $data->setAgainstValue(['text/plain']);
        $this->assertInstanceOf(File\AFileRule::class, $data);
        $data->validate($this->getMockFile());
    }

    /**
     * @throws RuleException
     */
    public function testFileMimeNotList(): void
    {
        $data = new File\FileMimeList();
        $data->setAgainstValue(['octet/stream']);
        $this->expectException(RuleException::class);
        $data->validate($this->getMockFile());
    }
}
