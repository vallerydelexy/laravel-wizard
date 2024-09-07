<?php

namespace vallerydelexy\LaravelWizard\Test\Stubs;

class WizardControllerSkipStub extends WizardControllerStub
{
    /**
     * The wizard steps instance.
     *
     * @var array
     */
    protected $steps = [
        UserSkipStepStub::class,
        PostStepStub::class,
    ];
}
