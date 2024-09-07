<?php

namespace vallerydelexy\LaravelWizard\Test\Stubs;

class WizardControllerUploadFileStub extends WizardControllerStub
{
    /**
     * The wizard steps instance.
     *
     * @var array
     */
    protected $steps = [
        AvatarStepStub::class,
        SaveAvatarStepStub::class,
    ];
}
