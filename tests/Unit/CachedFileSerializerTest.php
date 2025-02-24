<?php

namespace vallerydelexy\LaravelWizard\Test\Unit;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use vallerydelexy\LaravelWizard\Cache\CachedFile;
use vallerydelexy\LaravelWizard\Cache\CachedFileSerializer;
use vallerydelexy\LaravelWizard\Test\Concerns\CachedFileTesting;
use vallerydelexy\LaravelWizard\Test\TestCase;

class CachedFileSerializerTest extends TestCase
{
    use CachedFileTesting;

    /**
     * The cached file serializer instance.
     *
     * @var \vallerydelexy\LaravelWizard\Cache\CachedFileSerializer
     */
    protected $serializer;

    protected function setUp(): void
    {
        parent::setUp();

        Storage::fake('local');

        $this->serializer = new CachedFileSerializer();
    }

    protected function tearDown(): void
    {
        $this->serializer = null;

        parent::tearDown();
    }

    public function testSerializeFilePutUploadedFile()
    {
        // arrange
        CachedFile::setFakeFilename('test_temp_file');
        $file = UploadedFile::fake()->image('avatar.jpg');
        $expected = $this->getSerializedCachedFile();

        // act
        $actual = $this->serializer->serializeFile($file);

        // assert
        $this->assertEquals($expected, $actual);
        Storage::assertExists('laravel-wizard-tmp/test_temp_file.'.jpg());
    }

    public function testSerializeFilePutCachedFile()
    {
        // arrange
        CachedFile::setFakeFilename('test_temp_file');
        $file = UploadedFile::fake()->image('avatar.jpg');
        $cachedFile = new CachedFile($file);
        $expected = $this->getSerializedCachedFile();

        // act
        $actual = $this->serializer->serializeFile($cachedFile);

        // assert
        $this->assertEquals($expected, $actual);
        Storage::assertExists('laravel-wizard-tmp/test_temp_file.'.jpg());
    }

    public function testUnserializeFile()
    {
        // arrange
        $file = UploadedFile::fake()->image('avatar.jpg');
        $filePath = $file->storeAs('laravel-wizard-tmp', 'test_temp_file.'.jpg());
        $serialized = $this->getSerializedCachedFile();

        // act
        $cachedFile = $this->serializer->unserializeFile($serialized);
        $cachedFile->setTmpPath($filePath);

        // assert
        $this->assertInstanceOf(CachedFile::class, $cachedFile);
        $this->assertEquals('local', $cachedFile->disk());
        $this->assertEquals('test_temp_file.'.jpg(), $cachedFile->filename());
        $this->assertEquals('image/jpeg', $cachedFile->mimeType());
        $this->assertEquals('laravel-wizard-tmp/test_temp_file.'.jpg(), $cachedFile->tmpPath());
        $this->assertTrue(is_file($cachedFile->tmpFullPath()));
        $this->assertIsTemporaryFile($cachedFile->file());
    }

    public function testFirstTimeSerializePayloadFiles()
    {
        // arrange
        CachedFile::setFakeFilename('test_temp_file');
        $file = UploadedFile::fake()->image('avatar.jpg');
        $data = [
            'other_step' => ['field' => 'data'],
            'avatar_step' => ['avatar' => $file],
        ];
        $cachedData = [];
        $expected = [
            'other_step' => ['field' => 'data'],
            'avatar_step' => ['avatar' => $this->getSerializedCachedFile()],
            '_files' => [$this->tempFileFullPath()],
        ];

        // act
        $actual = $this->serializer->serializePayloadFiles($data, $cachedData);

        // assert
        $this->assertEquals($expected, $actual);
    }

    public function testSecondTimeSerializePayloadFiles()
    {
        // arrange
        CachedFile::setFakeFilename('test_temp_file');
        $file = UploadedFile::fake()->image('avatar.jpg');
        $data = [
            'other_step' => ['field' => 'data'],
            'avatar_step' => ['avatar' => $file],
            '_files' => [$this->tempFileFullPath()],
        ];
        $cachedData = ['avatar_step' => ['avatar' => $this->getSerializedCachedFile()]];
        $expected = [
            'other_step' => ['field' => 'data'],
            'avatar_step' => ['avatar' => $this->getSerializedCachedFile()],
            '_files' => [$this->tempFileFullPath()],
        ];

        // act
        $actual = $this->serializer->serializePayloadFiles($data, $cachedData);

        // assert
        $this->assertEquals($expected, $actual);
    }

    public function testUnserializePayloadFiles()
    {
        // arrange
        $file = UploadedFile::fake()->image('avatar.jpg');
        $file->storeAs('laravel-wizard-tmp', 'test_temp_file.'.jpg());
        $data = [
            'other_step' => ['field' => 'data'],
            'avatar_step' => ['avatar' => $this->getSerializedCachedFile()],
            '_files' => [$this->tempFileFullPath()],
        ];

        // act
        $actual = $this->serializer->unserializePayloadFiles($data);

        // assert
        $this->assertEquals(['field' => 'data'], $actual['other_step']);
        $this->assertIsTemporaryFile($actual['avatar_step']['avatar']);
        $this->assertEquals([$this->tempFileFullPath()], $actual['_files']);
    }

    public function testCanBeUnserializeFile()
    {
        // arrange
        $data = $this->getSerializedCachedFile();

        // act
        $actual = $this->serializer->canUnserializeFile($data);

        // assert
        $this->assertTrue($actual);
    }

    public function testCanNotBeUnserializeFile()
    {
        // arrange
        $data = $this->getSerializedCachedFile();

        // act
        $actual1 = $this->serializer->canUnserializeFile(str_replace(CachedFile::class, 'FakePrefix', $data));
        $actual2 = $this->serializer->canUnserializeFile(null);

        // assert
        $this->assertFalse($actual1, 'is string type but is no correct prefix');
        $this->assertFalse($actual2, 'is not string type');
    }

    public function testClearTmpFiles()
    {
        // arrange
        $file = UploadedFile::fake()->image('avatar1.jpg');
        $file->storeAs('laravel-wizard-tmp', 'test_temp_file1.'.jpg());
        $file = UploadedFile::fake()->image('avatar2.jpg');
        $file->storeAs('laravel-wizard-tmp', 'test_temp_file2.'.jpg());
        $files = [
            $this->tempFileFullPath('1'),
            $this->tempFileFullPath('2'),
        ];

        // act
        Storage::assertExists('laravel-wizard-tmp/test_temp_file1.'.jpg());
        Storage::assertExists('laravel-wizard-tmp/test_temp_file2.'.jpg());
        $this->serializer->clearTmpFiles($files);

        // assert
        Storage::assertMissing('laravel-wizard-tmp/test_temp_file1.'.jpg());
        Storage::assertMissing('laravel-wizard-tmp/test_temp_file2.'.jpg());
    }
}
