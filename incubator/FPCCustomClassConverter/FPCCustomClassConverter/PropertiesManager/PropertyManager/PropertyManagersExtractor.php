<?php
/*
    Copyright (c) 2011, Bastien Aracil
    All rights reserved.
    New BSD license. See http://en.wikipedia.org/wiki/Bsd_license

    Redistribution and use in source and binary forms, with or without
    modification, are permitted provided that the following conditions are met:
       * Redistributions of source code must retain the above copyright
         notice, this list of conditions and the following disclaimer.
       * Redistributions in binary form must reproduce the above copyright
         notice, this list of conditions and the following disclaimer in the
         documentation and/or other materials provided with the distribution.
       * The name of Bastien Aracil may not be used to endorse or promote products
         derived from this software without specific prior written permission.

    THIS SOFTWARE IS PROVIDED BY THE COPYRIGHT HOLDERS AND CONTRIBUTORS "AS IS" AND
    ANY EXPRESS OR IMPLIED WARRANTIES, INCLUDING, BUT NOT LIMITED TO, THE IMPLIED
    WARRANTIES OF MERCHANTABILITY AND FITNESS FOR A PARTICULAR PURPOSE ARE
    DISCLAIMED. IN NO EVENT SHALL SILEX LABS BE LIABLE FOR ANY
    DIRECT, INDIRECT, INCIDENTAL, SPECIAL, EXEMPLARY, OR CONSEQUENTIAL DAMAGES
    (INCLUDING, BUT NOT LIMITED TO, PROCUREMENT OF SUBSTITUTE GOODS OR SERVICES;
    LOSS OF USE, DATA, OR PROFITS; OR BUSINESS INTERRUPTION) HOWEVER CAUSED AND
    ON ANY THEORY OF LIABILITY, WHETHER IN CONTRACT, STRICT LIABILITY, OR TORT
    (INCLUDING NEGLIGENCE OR OTHERWISE) ARISING IN ANY WAY OUT OF THE USE OF THIS
    SOFTWARE, EVEN IF ADVISED OF THE POSSIBILITY OF SUCH DAMAGE.
*/


/**
 * User: Bastien Aracil
 * Date: 19/07/11
 *
 * Return a list of managers that manage public property and private/protected properties that have getter and setter
 */

require_once "PublicPropertyManager.php";
require_once "GSPropertyManager.php";

class FPC_PropertyManagersExtractor implements FPC_IPropertyManagersExtractor {

    function extractPropertyManager($class)
    {
        $rClass = new ReflectionClass($class);

        //get public properties
        $properties = $this->extractPublicPropertyNames($rClass);

        //get public methods
        $methods = $this->extractPublicMethodNames($rClass);

        //remove getter/setter of public properties
        $this->filterMethodNames($methods, $properties);

        //extract property names that uses a getter and a setter obtains from the list of public methods
        $gsProperties = $this->extractGetterSetter($methods);


        //create the list of managers for the given class
        $result = array();

        foreach ($properties as $property => $dummy) {
            $result[$property] = new FPC_PublicPropertyManager($property);
        }

        foreach ($gsProperties as $gsProperty => $dummy) {
            $result[$gsProperty] = new FPC_GSPropertyManager($gsProperty);
        }

        return $result;
    }

    /**
     * @param ReflectionClass $rClass
     * @return array of string containing the names of the public properties
     */
    private function extractPublicPropertyNames(ReflectionClass $rClass) {
        $names = array();
        $properties = $rClass->getProperties(ReflectionProperty::IS_PUBLIC);

        foreach ($properties as $property) {
            $names[$property->getName()] = false;
        }

        return $names;
    }

    /**
     * @param ReflectionClass $rClass
     * @return array associative array with keys the names of all public methods and false as value.
     */
    private function extractPublicMethodNames(ReflectionClass $rClass) {
        $names = array();
        $methods = $rClass->getMethods(ReflectionMethod::IS_PUBLIC);

        foreach ($methods as $method) {
            $names[$method->getName()] = false;
        }

        return $names;
    }

    /**
     * @param $methodNames associative array with keys names of methods
     * @return array associative array with keys the name of the properties having a setter and a getter and false as value.
     */
    private function extractGetterSetter(&$methodNames) {
        $gsProperty = array();
        $names = $methodNames;
        foreach ($names as $methodName => $value) {
            $leftPart = substr($methodName,0,3);
            $rightPart = substr($methodName,3);
            if ($leftPart == "set") {
                if (isset($methodNames["get".$rightPart])) {
                    $gsProperty[$this->my_lcfirst($rightPart)] = true;
                }
            }
            else if ($leftPart == "get") {
                if (isset($methodNames["set".$rightPart])) {
                    $gsProperty[$this->my_lcfirst($rightPart)] = true;
                }
            }
        }

        return $gsProperty;
    }

    /**
     * Remove from the list $methodNames any setter and getter of a property
     * that is listed in the array $propertyNames
     *
     * @param array $methodNames an associative array with key the name of methods
     * @param array $propertyNames an array of property names
     * @return void
     */
    private function filterMethodNames(&$methodNames, $propertyNames) {
        foreach ($propertyNames as $propertyName => $value) {
            $ucProp = ucfirst($propertyName);

            $getterName = "get" . $ucProp;
            $setterName = "set" . $ucProp;

            unset($methodNames[$getterName]);
            unset($methodNames[$setterName]);
        }
    }

    private function my_lcfirst($str) {
        if (!function_exists('lcfirst')) {
            return substr_replace($str, strtolower(substr($str, 0, 1)), 0, 1);
        }
        return lcfirst($str);
    }

}
