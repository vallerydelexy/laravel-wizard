<?php

namespace vallerydelexy\LaravelWizard\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller;
use vallerydelexy\LaravelWizard\Wizardable;

class WizardController extends Controller
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests, Wizardable;
}
