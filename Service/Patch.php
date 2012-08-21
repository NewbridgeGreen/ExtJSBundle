<?php

namespace NewbridgeGreen\ExtJSBundle\Service;

class Patch
{
    public function patch($object, $patch, $format = null)
    {

        if ($format === 'json') {
            $patch = json_decode($patch);
        }

        if (is_object($patch)) {
            $patch = get_object_vars($patch);
        }

        foreach ($patch as $property => $value) {
            if (is_object($value)) {
                $value = get_object_vars($value);
            }
            if ($value !== null && !is_object($value)) {
                call_user_func(array($object, 'set' . ucfirst($property)), $value);
            }
        }
        return $object;
    }
}
