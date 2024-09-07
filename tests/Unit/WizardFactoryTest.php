<?php

namespace vallerydelexy\LaravelWizard\Test\Unit;

use vallerydelexy\LaravelWizard\Contracts\CacheStore;
use vallerydelexy\LaravelWizard\StepRepository;
use vallerydelexy\LaravelWizard\Test\Stubs\UserStepStub;
use vallerydelexy\LaravelWizard\Test\TestCase;
use vallerydelexy\LaravelWizard\WizardFactory;

class WizardFactoryTest extends TestCase
{
    public function testMakeWizard()
    {
        $factory = new WizardFactory($this->app);

        $wizard = $factory->make('test-wizard', 'Test', [UserStepStub::class]);

        $this->assertEquals('test-wizard', $wizard->getName());
        $this->assertInstanceOf(CacheStore::class, $wizard->cache());
        $this->assertInstanceOf(StepRepository::class, $wizard->stepRepo());
        $this->assertCount(1, $wizard->stepRepo()->original());
        $this->assertInstanceOf(UserStepStub::class, $wizard->stepRepo()->original()->first());
    }
}
