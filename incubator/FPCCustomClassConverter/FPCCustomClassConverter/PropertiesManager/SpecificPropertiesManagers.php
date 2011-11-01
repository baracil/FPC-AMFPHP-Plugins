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
    DISCLAIMED. IN NO EVENT SHALL BASTIEN ARACIL BE LIABLE FOR ANY
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
 */

class FPC_SpecificPropertiesManagers implements FPC_IPropertiesManager {

    private $managers;

    private $classDispatcher;

    public function __construct() {
        $this->managers = array();
        $this->classDispatcher = array();
    }

    public function addSpecificManager(FPC_ISpecificPropertiesManager $manager) {
        $this->addManager($manager->getClassName(), $manager);
    }

    public function addManager($className, FPC_IPropertiesManager $manager) {
        $this->managers[$className] = $manager;
    }

    /**
     * @param $obj
     * @return array an array of $propertyName => $propertyValue pair or null if this manager cannot handle this object
     */
    function getProperties($obj)
    {
        $className = get_class($obj);
        if (isset($this->managers[$className])) {
            return $this->managers[$className]->getProperties($obj);
        }

        return null;
    }

    /**
     * @param array $propertyValues an array of $propertyName => $propertyValue pair
     * @return bool true if the properties has been set by this manager, false otherwise
     */
    function setProperties($obj, $propertyValues)
    {
        $className = get_class($obj);
        if (isset($this->managers[$className])) {
            return $this->managers[$className]->setProperties($obj, $propertyValues);
        }

        return false;
    }


    function getDefinedClass($originClassName) {
        return $originClassName;
/**
        if (isset($this->classDispatcher[$originClassName])) {
            return $this->classDispatcher[$originClassName];
        }

        $reflection = new ReflectionClass($originClassName);
        $className = $originClassName;
        while (!isset($this->managers[$className])) {
            $reflection = $reflection->getParentClass();
            if ($reflection == false) {
                return null;
            }
            $className = $reflection->getName();
        }

        $this->classDispatcher[$originClassName] = $className;
        return $className;
 */
    }
}
