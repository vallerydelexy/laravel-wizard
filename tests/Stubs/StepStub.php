<?php

namespace vallerydelexy\LaravelWizard\Test\Stubs;

use Illuminate\Http\Request;
use vallerydelexy\LaravelWizard\Step;

class StepStub extends Step
{
    /**
     * The step slug.
     *
     * @var string
     */
    protected $slug = 'step-stub';

    /**
     * The step show label text.
     *
     * @var string
     */
    protected $label = 'Step stub';

    /**
     * Set the step model instance or the relationships instance.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Database\Eloquent\Model|\Illuminate\Database\Eloquent\Relations\Relation|null
     */
    public function model(Request $request)
    {
        //
    }

    /**
     * Save this step form data.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  array|null  $data
     * @param  \Illuminate\Database\Eloquent\Model|\Illuminate\Database\Eloquent\Relations\Relation|null  $model
     * @return void
     */
    public function saveData(Request $request, $data = null, $model = null)
    {
        //
    }

    /**
     * Validation rules.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function rules(Request $request)
    {
        return [
            //
        ];
    }
}
