<?php

namespace vallerydelexy\LaravelWizard\Test\Stubs;

use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use vallerydelexy\LaravelWizard\Step;

class PostStepStub extends Step
{
    /**
     * The step slug.
     *
     * @var string
     */
    protected $slug = 'post-step-stub';

    /**
     * The step show label text.
     *
     * @var string
     */
    protected $label = 'Post step stub';

    /**
     * The step form view path.
     *
     * @var string
     */
    protected $view = 'steps.post';

    /**
     * Set the step model instance or the relationships instance.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Database\Eloquent\Model|\Illuminate\Database\Eloquent\Relations\Relation|null
     */
    public function model(Request $request)
    {
        return $request->user()->posts();
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
        $data = Arr::only($data, ['title', 'content']);
        $model->create($data);
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
            'title' => 'required|max:50',
            'content' => 'required',
        ];
    }
}
