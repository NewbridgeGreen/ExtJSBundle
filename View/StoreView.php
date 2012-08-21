<?php

namespace NewbridgeGreen\ExtJSBundle\View;

use FOS\RestBundle\View\View;

class StoreView extends View
{

    private $root = array();
    private $success = true;
    private $message = '';
    private $count = 0;
    private $metaData = array();

    /**
     *
     */
    public function __construct($data = null, $statusCode = null,
        array $headers = array())
    {
        parent::__construct($data, $statusCode, $headers);
        if ($data) {
            $this->processData($data);
        }
    }

    public function getData()
    {
        return array(
            'root' => $this->root,
            'success' => $this->success,
            'message' => $this->message,
            'count' => $this->count,
        );
    }

    /**
     *
     */
    public function setData($data)
    {
        parent::setData($data);
        if ($data) {
            $this->processData($data);
        }
        return $this;
    }

    public function setStatusCode($code)
    {
        parent::setStatusCode($code);
        $this->success = ($code >= 200 && $code < 300) ? true : false;
        return $this;
    }

    private function processData($data)
    {
        if (method_exists($data, 'toArray')) {
            $this->root = array_values($data->toArray());
        } else {
            $this->root = array_values((array) $data);
        }
        $this->count = count($this->root);
    }

    public function getRoot()
    {
        return $this->root;
    }

    public function setRoot($root)
    {
        $this->root = $root;
        $this->count = count($root);
        return $this;
    }

    public function getSuccess()
    {
        return $this->success;
    }

    public function setSuccess($success)
    {
        $this->success = $success;
        return $this;
    }

    public function getMessage()
    {
        return $this->message;
    }

    public function setMessage($message)
    {
        $this->message = $message;
        return $this;
    }

    public function getCount()
    {
        return $this->count;
    }

    public function setCount($count)
    {
        $this->count = $count;
        return $this;
    }

    public function getMetaData()
    {
        return $this->metaData;
    }

    public function setMetaData($metaData)
    {
        $this->metaData = $metaData;
        return $this;
    }

}