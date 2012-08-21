<?php

/*
 * Copyright 2011 Johannes M. Schmitt <schmittjoh@gmail.com>
 *
 * Licensed under the Apache License, Version 2.0 (the "License");
 * you may not use this file except in compliance with the License.
 * You may obtain a copy of the License at
 *
 * http://www.apache.org/licenses/LICENSE-2.0
 *
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS,
 * WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 * See the License for the specific language governing permissions and
 * limitations under the License.
 */

namespace NewbridgeGreen\ExtJSBundle\Twig;


/**
 * Serializer helper twig extension
 *
 * Basically provides access to JMSSerializer from Twig
 */
class PrettyExtension extends \Twig_Extension
{

    public function getName()
    {
        return 'nbg_pretty';
    }

    public function getFilters()
    {
        return array(
            'pretty' => new \Twig_Filter_Method($this, 'pretty'),
        );
    }

    public function pretty($object)
    {
        return $this->indent($object);
    }

    /**
     * Indents a flat JSON string to make it more human-readable.
     * Stolen from http://recursive-design.com/blog/2008/03/11/format-json-with-php/
     * and adapted to put spaces around : characters.
     *
     * @param string $json The original JSON string to process.
     *
     * @return string Indented version of the original JSON string.
     */
    public function indent($json)
    {
        $result = '';
        $pos = 0;
        $strLen = strlen($json);
        $indentStr = '  ';
        $newLine = "\n";
        $prevChar = '';
        $outOfQuotes = true;

        for ($i = 0; $i <= $strLen; $i++) {

            // Grab the next character in the string.
            $char = substr($json, $i, 1);

            // Put spaces in front of :
            if ($outOfQuotes && $char == ':' && $prevChar != ' ') {
                $result .= ' ';
            }

            if ($outOfQuotes && $char != ' ' && $prevChar == ':') {
                $result .= ' ';
            }

            // Are we inside a quoted string?
            if ($char == '"' && $prevChar != '\\') {
                $outOfQuotes = !$outOfQuotes;

                // If this character is the end of an element,
                // output a new line and indent the next line.
            } else if (($char == '}' || $char == ']') && $outOfQuotes) {
                $result .= $newLine;
                $pos--;
                for ($j = 0; $j < $pos; $j++) {
                    $result .= $indentStr;
                }
            }

            // Add the character to the result string.
            $result .= $char;

            // If the last character was the beginning of an element,
            // output a new line and indent the next line.
            if (($char == ',' || $char == '{' || $char == '[') && $outOfQuotes) {
                $result .= $newLine;
                if ($char == '{' || $char == '[') {
                    $pos++;
                }

                for ($j = 0; $j < $pos; $j++) {
                    $result .= $indentStr;
                }
            }

            $prevChar = $char;
        }
//var_dump($result);exit;

        return $result;
    }

}
