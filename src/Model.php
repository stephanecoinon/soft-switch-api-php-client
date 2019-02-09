<?php

namespace StephaneCoinon\SoftSwitch;

class Model
{
    /**
     * Model attributes.
     *
     * @var array
     */
    protected $attributes = [];

    /**
     * Instantiate a new model.
     *
     * @param array $attributes
     */
    public function __construct(array $attributes = [])
    {
        // Only set attributes with non-numeric keys as the API returns all
        // attributes twice: once with a numeric key and once with a string
        // key/name
        $this->attributes = array_filter($attributes, function ($value, $key) {
            return ! is_numeric($key);
        }, ARRAY_FILTER_USE_BOTH);
    }

    /**
     * Has the model got a given attribute?
     *
     * @param  string $name
     * @return boolean
     */
    public function hasAttribute($name)
    {
        return array_key_exists($name, $this->attributes);
    }

    /**
     * Get an attribute value.
     *
     * @param  string $name
     * @param  mixed  $default  default value to return if attribute does not exist
     * @return mixed
     */
    public function getAttribute($name, $default = null)
    {
        return $this->attributes[$name] ?? $default;
    }

    /**
     * Set an attribute value.
     *
     * @param  string $name
     * @param  mixed $value
     * @return self
     */
    public function setAttribute(string $name, $value): self
    {
        $this->attributes[$name] = $value;

        return $this;
    }

    /**
     * Get all the attributes on the model.
     *
     * @return array
     */
    public function getAttributes()
    {
        return $this->attributes;
    }

    /**
     * Get an attribute value.
     *
     * @param  string $name
     * @return mixed
     */
    public function __get($name)
    {
        return $this->getAttribute($name);
    }

    /**
     * Set an attribute value.
     *
     * @param  string $name
     * @param  mixed $value
     * @return void
     */
    public function __set($name, $value)
    {
        $this->setAttribute($name, $value);
    }

    public function toArray(): array
    {
        return $this->getAttributes();
    }

    /**
     * Get parameters to pass to API from this model.
     *
     * Override this method in your child model when not all the model attributes
     * should be passed as parameters to the API.
     *
     * @return array
     */
    public function getApiParameters(): array
    {
        return $this->toArray();
    }
}
