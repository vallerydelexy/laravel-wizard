<?php

namespace vallerydelexy\LaravelWizard\Test\Stubs;

class UserSkipStepStub extends UserStepStub
{
    /**
     * Is it possible to skip this step.
     *
     * @var bool
     */
    protected $skip = true;
}
