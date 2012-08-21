<?php

namespace NewbridgeGreen\ExtJSBundle\Data;

use JMS\SerializerBundle\Annotation as JMS;

class Model
{
    protected $_defaultProperty = null;
    protected $_model = null;
    /**
     * Constructor
     *
     * @param  string|null $identifier
     * @param  array|Traversable|null $models
     * @param  string|null $label
     * @return void
     */
    public function __construct($model = null, $defaultProperty = null)
    {
        if (null !== $defaultProperty) {
            $this->_defaultProperty = $defaultProperty;
        }

        if (null !== $model) {
            $this->setModel($model);
        }
    }

    /**
     * Set an individual model, optionally by identifier (overwrites)
     *
     * @param  array|object $model
     * @param  string|null $identifier
     * @return Zend_Dojo_Data
     */
    public function setModel($model)
    {
        $this->_model = $this->_normalizeModel($model);
        return $this;
    }

    /**
     * Does an model with the given identifier exist?
     *
     * @param  string|int $model
     * @return bool
     */

    public function isModel($model)
    {
        return $model instanceof $this->_model;
    }

    /**
     * Retrieve an model by identifier
     *
     * Model retrieved will be flattened to an array.
     *
     * @param  string $id
     * @return array
     */
    public function getModel()
    {
        return $this->_model;
    }

    /**
     * Remove all models at once
     *
     * @return Zend_ExtJS_Store
     */
    public function clearModel()
    {
        $this->_model = null;
        return $this;
    }

    /**
     * Seralize entire data structure, including identifier and label, to array
     *
     * @return array
     */
    public function toArray()
    {
        return $this->getModel();
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
     * Normalize an model to attach to the collection
     *
     * @param  array|object $model
     * @return array
     */
    protected function _normalizeModel($model)
    {
        if (!is_object($model) && !is_array($model)) {
            //require_once 'Zend/Exception.php';
            //throw new Zend_Exception('Only arrays and objects may be attached');
            $model = (Object) array($this->_defaultProperty => $model);
        }

        if (is_object($model)) {
            if (method_exists($model, 'toArray')) {
                $model = $model->toArray();
            } else {
                $model = get_object_vars($model);
            }
        }

        return $model;
    }
}