<?php

namespace NewbridgeGreen\ExtJSBundle;

use JMS\SerializerBundle\Annotation\SerializedName;

class Store
{

    /** @SerializedName("root") */
    private $root;
    /** @SerializedName("success") */
    private $success;
    /** @SerializedName("message") */
    private $message;
    /** @SerializedName("total") */
    private $total;

    public function __construct($root = null, $success = true, $message = '')
    {
        $this->root = array_values($root->toArray());
        $this->total = $root->count();
        $this->success = $success;
        $this->message = $message;
    }

    public function getRoot()
    {
        return $this->root;
    }

    public function setRoot($root)
    {
        $this->root = $root;
    }

    public function getSuccess()
    {
        return $this->success;
    }

    public function setSuccess($success)
    {
        $this->success = $success;
    }

    public function getMessage()
    {
        return $this->message;
    }

    public function setMessage($message)
    {
        $this->message = $message;
    }

    public function getTotal()
    {
        return $this->total;
    }

    public function setTotal($total)
    {
        $this->total = $total;
    }
}