<?php

namespace vallerydelexy\LaravelWizard\Cache;

use Illuminate\Contracts\Session\Session;
use Illuminate\Support\Arr;
use vallerydelexy\LaravelWizard\Contracts\CacheStore;

class SessionStore implements CacheStore
{
    /**
     * The session instance.
     *
     * @var \Illuminate\Contracts\Session\Session
     */
    protected $session;

    /**
     * The cached file serializer instance.
     *
     * @var \vallerydelexy\LaravelWizard\Cache\CachedFileSerializer
     */
    protected $serializer;

    /**
     * The wizard key.
     *
     * @var string
     */
    protected $wizardKey;

    /**
     * Create a new wizard cache session store instance.
     *
     * @param  \Illuminate\Contracts\Session\Session  $session
     * @param  \vallerydelexy\LaravelWizard\Cache\CachedFileSerializer  $serializer
     * @param  string  $wizardKey
     * @return void
     */
    public function __construct(Session $session, CachedFileSerializer $serializer, string $wizardKey)
    {
        $this->session = $session;
        $this->serializer = $serializer;
        $this->wizardKey = $wizardKey;
    }

    /**
     * Get the store step data.
     *
     * @param  string  $key
     * @return mixed
     */
    public function get(string $key = '')
    {
        $data = $this->session->get($this->wizardKey, []);
        $data = $this->serializer->unserializePayloadFiles($data);

        return $key ? Arr::get($data, $key) : $data;
    }

    /**
     * Get the last processed step index.
     *
     * @return int|null
     */
    public function getLastProcessedIndex()
    {
        return $this->get('_last_index');
    }

    /**
     * Set data to the store.
     *
     * @param  array  $data
     * @param  int|null  $lastIndex
     * @return void
     */
    public function set(array $data, $lastIndex = null)
    {
        $cachedData = $this->session->get($this->wizardKey, []);

        $data = $this->serializer->serializePayloadFiles($data, $cachedData);

        if (isset($lastIndex) && is_numeric($lastIndex)) {
            $data['_last_index'] = (int) $lastIndex;
        }

        $this->session->put($this->wizardKey, $data);
    }

    /**
     * Put data to the store.
     *
     * @param  string  $key
     * @param  array  $value
     * @param  int|null  $lastIndex
     * @return void
     */
    public function put(string $key, array $value, $lastIndex = null)
    {
        $data = $this->get($key);
        Arr::set($data, $key, $value);
        $this->set($data, $lastIndex);
    }

    /**
     * Checks if an a step data.
     *
     * @param  string  $key
     * @return bool
     */
    public function has(string $key)
    {
        $data = $this->get($key);

        return isset($data);
    }

    /**
     * Clear the store data.
     *
     * @return void
     */
    public function clear()
    {
        $this->serializer->clearTmpFiles($this->get('_files'));

        $this->session->forget($this->wizardKey);
    }
}
