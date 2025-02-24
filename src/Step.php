<?php

namespace vallerydelexy\LaravelWizard;

use Illuminate\Http\Request;

abstract class Step
{
    /**
     * The wizard instance.
     *
     * @var \vallerydelexy\LaravelWizard\Wizard
     */
    protected $wizard;

    /**
     * The step model instance or the relationships instance.
     *
     * @var \Illuminate\Database\Eloquent\Model|\Illuminate\Database\Eloquent\Relations\Relation|null
     */
    protected $model;

    /**
     * The step index.
     *
     * @var int
     */
    protected $index;

    /**
     * The step slug.
     *
     * @var string
     */
    protected $slug;

    /**
     * The step show label text.
     *
     * @var string
     */
    protected $label;

    /**
     * The step form view path.
     *
     * @var string
     */
    protected $view;

    /**
     * Is it possible to skip this step.
     *
     * @var bool
     */
    protected $skip = false;

    /**
     * The request input except input data.
     *
     * @var array
     */
    protected $inputExcept = ['_token', '_method', '_trigger'];

    /**
     * Create a new step instance.
     *
     * @param  \vallerydelexy\LaravelWizard\Wizard  $wizard
     * @param  int  $index
     * @return void
     */
    public function __construct(Wizard $wizard, int $index)
    {
        $this->wizard = $wizard;
        $this->index = $index;
    }

    /**
     * Get the step index.
     *
     * @return int
     */
    public function index()
    {
        return $this->index;
    }

    /**
     * Get the step number.
     *
     * @return int
     */
    public function number()
    {
        return $this->index + 1;
    }

    /**
     * Get the step slug.
     *
     * @return string
     */
    public function slug()
    {
        return $this->slug;
    }

    /**
     * Get the step show label text.
     *
     * @return string
     */
    public function label()
    {
        return $this->label;
    }

    /**
     * Get the step form view path.
     *
     * @return string
     */
    public function view()
    {
        if ($this->view) {
            return $this->view;
        }

        return config('wizard.step_view_path').".{$this->wizard->getName()}.{$this->slug}";
    }

    /**
     * Get is it possible to skip this step.
     *
     * @return bool
     */
    public function skip()
    {
        return $this->skip;
    }

    /**
     * Get the step belongs wizard.
     *
     * @return \vallerydelexy\LaravelWizard\Wizard
     */
    public function getWizard()
    {
        return $this->wizard;
    }

    /**
     * Get the step repository.
     *
     * @return \vallerydelexy\LaravelWizard\StepRepository
     */
    public function getRepo()
    {
        return $this->wizard->stepRepo();
    }

    /**
     * Get the step model instance or the relationships instance.
     *
     * @return \Illuminate\Database\Eloquent\Model|\Illuminate\Database\Eloquent\Relations\Relation|null
     */
    public function getModel()
    {
        return $this->model;
    }

    /**
     * Set the step model instance or the relationships instance.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return void
     */
    public function setModel(Request $request)
    {
        $this->model = $this->model($request);
    }

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
    abstract public function saveData(Request $request, $data = null, $model = null);

    /**
     * Validation rules.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function rules(Request $request)
    {
        return [];
    }

    /**
     * Get custom messages for validator errors.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function validateMessages(Request $request)
    {
        return [];
    }

    /**
     * Get custom attributes for validator errors.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function validateAttributes(Request $request)
    {
        return [];
    }

    /**
     * Get request input data.
     *
     * @param  Request  $request
     * @return array
     */
    public function getRequestData(Request $request)
    {
        return $request->except($this->inputExcept);
    }

    /**
     * Get step cache data.
     *
     * @param  string  $key
     * @return array|string|null
     */
    public function data($key = '')
    {
        return $this->wizard->cache()->get($this->getDataKey($key));
    }

    /**
     * Get step data key.
     *
     * @param  string  $key
     * @return string
     */
    public function getDataKey($key = '')
    {
        return collect([$this->slug, $key])->filter()->implode('.');
    }

    /**
     * Get all user input data from steps.
     *
     * @return array
     */
    public function getStepsData()
    {
        return $this->getRepo()->original()->reduce(function ($carry, $step) {
            return array_merge($carry, $step->data());
        }, []);
    }

    /**
     * Find step by slug.
     *
     * @param  string  $slug
     * @return \vallerydelexy\LaravelWizard\Step|null
     */
    public function find(string $slug)
    {
        return $this->getRepo()->find($slug);
    }

    /**
     * Find step key by slug.
     *
     * @param  string  $slug
     * @return int|null
     */
    public function findKey(string $slug, int $default = null)
    {
        return $this->getRepo()->findKey($slug, $default);
    }

    /**
     * Get the first step.
     *
     * @return \vallerydelexy\LaravelWizard\Step|null
     */
    public function first()
    {
        return $this->getRepo()->first();
    }

    /**
     * Get the last step.
     *
     * @return \vallerydelexy\LaravelWizard\Step|null
     */
    public function last()
    {
        return $this->getRepo()->last();
    }

    /**
     * Get the previous step.
     *
     * @return \vallerydelexy\LaravelWizard\Step|null
     */
    public function prev()
    {
        return $this->getRepo()->prev();
    }

    /**
     * Get the next step.
     *
     * @return \vallerydelexy\LaravelWizard\Step|null
     */
    public function next()
    {
        return $this->getRepo()->next();
    }

    /**
     * Checks if an a prev step.
     *
     * @return bool
     */
    public function hasPrev()
    {
        return $this->getRepo()->hasPrev();
    }

    /**
     * Checks if an a next step.
     *
     * @return bool
     */
    public function hasNext()
    {
        return $this->getRepo()->hasNext();
    }

    /**
     * Get prev step slug.
     *
     * @return string|null
     */
    public function prevSlug()
    {
        return $this->getRepo()->prevSlug();
    }

    /**
     * Get next step slug.
     *
     * @return string|null
     */
    public function nextSlug()
    {
        return $this->getRepo()->nextSlug();
    }

    /**
     * Cache progress data.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  array  $additionalData
     * @return array
     */
    public function cacheProgress(Request $request, array $additionalData = [])
    {
        // Get cache data, and push this step data.
        $cacheData = $this->wizard->cache()->get();
        $cacheData[$this->slug] = $this->getRequestData($request);
        $cacheData = array_merge($cacheData, $additionalData);

        $nextStepIndex = $this->wizard->nextStepIndex();

        // Save data to cache.
        $this->wizard->cacheStepData($cacheData, $nextStepIndex);

        return $this->wizard->cache()->get();
    }
}
