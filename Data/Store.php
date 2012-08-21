<?php

namespace NewbridgeGreen\ExtJSBundle\Data;

class Store implements ArrayAccess,Iterator,Countable
{

    protected $_models = array();
    protected $_success = null;
    protected $_total = null;
    protected $_message = null;
    protected $_defaultProperty = null;
    /**
     * Constructor
     *
     * @param  string|null $identifier
     * @param  array|Traversable|null $models
     * @param  string|null $label
     * @return void
     */
    public function __construct($models = null, $defaultProperty = null)
    {
        if (null !== $defaultProperty) {
            $this->_defaultProperty = $defaultProperty;
        }

        if (null !== $models) {
            $this->setModels($models);
        }
    }

    /**
     * Set the models to collect
     *
     * @param array|Traversable $models
     * @return Zend_Dojo_Data
     */
    public function setModels($models)
    {
        $this->clearModels();
        return $this->addModels($models);
    }
    /**
     * Set an individual model, optionally by identifier (overwrites)
     *
     * @param  array|object $model
     * @param  string|null $identifier
     * @return Zend_Dojo_Data
     */
    public function setModel($model, $id = null)
    {
        $model = new Model($model, $this->_defaultProperty);
        $this->_models[$model['id']] = $model['model'];
        return $this;
    }

    /**
     * Add an individual model, optionally by identifier
     *
     * @param  array|object $model
     * @return Zend_ExtJS_Store
     */
    public function addModel($model)
    {

        $model = new Model($model, $this->_defaultProperty);
        $this->_models[] = $model->getModel();
        return $this;
    }

    /**
     * Add multiple models at once
     *
     * @param  array|Traversable $models
     * @return Zend_ExtJS_Store
     */
    public function addModels($models)
    {
        if (
            !is_array($models) &&
            (!is_object($models) || !($models instanceof Traversable))
        ) {
            throw new Exception(
                'Only arrays and Traversable objects may be added to ' .
                __CLASS__
            );
        }

        foreach ($models as $model) {
            $this->addModel($model);
        }

        return $this;
    }

    /**
     * Get all models as an array
     *
     * Serializes models to arrays.
     *
     * @return array
     */
    public function getModels()
    {
        return $this->_models;
    }

    /**
     * Does an model with the given identifier exist?
     *
     * @param  string|int $id
     * @return bool
     */

    public function hasModel($id)
    {
        return array_key_exists($id, $this->_models);
    }

    /**
     * Retrieve an model by identifier
     *
     * Model retrieved will be flattened to an array.
     *
     * @param  string $id
     * @return array
     */
    public function getModel($id)
    {
        if (!$this->hasModel($id)) {
            return null;
        }

        return $this->_models[$id];
    }

    /**
     * Remove model by identifier
     *
     * @param  string $id
     * @return Zend_Dojo_Data
     */
    public function removeModel($id)
    {
        if ($this->hasModel($id)) {
            unset($this->_models[$id]);
        }

        return $this;
    }

    /**
     * Remove all models at once
     *
     * @return Zend_ExtJS_Store
     */
    public function clearModels()
    {
        $this->_models = array();
        return $this;
    }

    public function getTotal()
    {
        return $this->_total;
    }

    public function setTotal($total)
    {
        $this->_total = $total;
    }


    /**
     * Seralize entire data structure, including identifier and label, to array
     *
     * @return array
     */
    public function toArray()
    {
        $array = array_values($this->getModels());
        return $array;
    }

    /**
     * Serialize to JSON (dojo.data format)
     *
     * @return string
     */
    public function toJson()
    {
        return json_encode($this->toArray());
    }

    /**
     * Serialize to string (proxy to {@link toJson()})
     *
     * @return string
     */
    public function __toString()
    {
        return $this->toJson();
    }

    /**
     * ArrayAccess: does offset exist?
     *
     * @param  string|int $offset
     * @return bool
     */
    public function offsetExists($offset)
    {
        return (null !== $this->getModel($offset));
    }

    /**
     * ArrayAccess: retrieve by offset
     *
     * @param  string|int $offset
     * @return array
     */
    public function offsetGet($offset)
    {
        return $this->getModel($offset);
    }

    /**
     * ArrayAccess: set value by offset
     *
     * @param  string $offset
     * @param  array|object|null $value
     * @return void
     */
    public function offsetSet($offset, $value)
    {
        $this->setModel($value, $offset);
    }

    /**
     * ArrayAccess: unset value by offset
     *
     * @param  string $offset
     * @return void
     */
    public function offsetUnset($offset)
    {
        $this->removeModel($offset);
    }

    /**
     * Iterator: get current value
     *
     * @return array
     */
    public function current()
    {
        return current($this->_models);
    }

    /**
     * Iterator: get current key
     *
     * @return string|int
     */
    public function key()
    {
        return key($this->_models);
    }

    /**
     * Iterator: get next model
     *
     * @return void
     */
    public function next()
    {
        return next($this->_models);
    }

    /**
     * Iterator: rewind to first value in collection
     *
     * @return void
     */
    public function rewind()
    {
        return reset($this->_models);
    }

    /**
     * Iterator: is model valid?
     *
     * @return bool
     */
    public function valid()
    {
        return (bool) $this->current();
    }

    /**
     * Countable: how many models are present
     *
     * @return int
     */
    public function count()
    {
        return count($this->_models);
    }
}
