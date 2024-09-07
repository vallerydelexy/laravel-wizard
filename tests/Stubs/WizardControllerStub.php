<?php

namespace vallerydelexy\LaravelWizard\Test\Stubs;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller;
use vallerydelexy\LaravelWizard\Wizardable;

class WizardControllerStub extends Controller
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests, Wizardable;

    /**
     * The wizard name.
     *
     * @var string
     */
    protected $wizardName = 'test';

    /**
     * The wizard title.
     *
     * @var string
     */
    protected $wizardTitle = 'Test';

    /**
     * The wizard steps instance.
     *
     * @var array
     */
    protected $steps = [
        UserStepStub::class,
        PostStepStub::class,
    ];
}
