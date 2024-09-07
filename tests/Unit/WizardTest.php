<?php

namespace vallerydelexy\LaravelWizard\Test\Unit;

use vallerydelexy\LaravelWizard\StepRepository;
use vallerydelexy\LaravelWizard\Test\Stubs\PostStepStub;
use vallerydelexy\LaravelWizard\Test\TestCase;
use vallerydelexy\LaravelWizard\Wizard;

class WizardTest extends TestCase
{
    /**
     * The wizard instance.
     *
     * @var \vallerydelexy\LaravelWizard\Wizard|\Mockery\MockInterface
     */
    protected $wizard;

    protected function setUp(): void
    {
        parent::setUp();

        $this->wizard = new Wizard($this->app, 'test-wizard', 'Test');
    }

    protected function tearDown(): void
    {
        $this->wizard = null;

        parent::tearDown();
    }

    public function testCacheStepData()
    {
        // arrange
        $data = ['step' => ['field' => 'data']];
        /** @param \Mockery\MockInterface $mock */
        $cache = $this->mock('cache', function ($mock) {
            $mock->shouldReceive('set')->once();
            $mock->shouldReceive('get')->once()->andReturn(['Saved data.']);
        });
        $this->wizard->setCache($cache);

        // act
        $this->wizard->cacheStepData($data, 1);
        $actual = $this->wizard->cache()->get();

        // assert
        $this->assertEquals(['Saved data.'], $actual);
    }

    public function testGetNextStepIndex()
    {
        // arrange
        /** @param \Mockery\MockInterface $mock */
        $stepRepo = $this->mock(StepRepository::class, function ($mock) {
            $mock->shouldReceive('next')
                ->once()
                ->andReturn(new PostStepStub($this->wizard, 1));
        });
        $this->wizard->setStepRepo($stepRepo);

        // act
        $actual = $this->wizard->nextStepIndex();

        // assert
        $this->assertEquals(1, $actual);
    }

    public function testGetNextStepIndexReturnNull()
    {
        // arrange
        /** @param \Mockery\MockInterface $mock */
        $stepRepo = $this->mock(StepRepository::class, function ($mock) {
            $mock->shouldReceive('next')->once()->andReturn(null);
        });
        $this->wizard->setStepRepo($stepRepo);

        // act
        $actual = $this->wizard->nextStepIndex();

        // assert
        $this->assertNull($actual);
    }
}
