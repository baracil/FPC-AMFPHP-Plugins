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
 * Date: 16/10/11
 */
 
class FPC_SmartPropertiesManager implements FPC_IPropertiesManager {

    /**
     * @var FPC_IPropertiesManager
     */
    private $_manager;

    /**
     * @var FPC_SpecificPropertiesManagers
     */
    private $_specificManagers;

    public function __construct($cached = true) {
        $this->_specificManagers = new FPC_SpecificPropertiesManagers();
        
        $this->_manager = new FPC_ChainedPropertiesManager();
        $this->_manager->addManager($this->_specificManagers);
        $this->_manager->addManager(new FPC_ReflectionPropertiesManager(new FPC_PropertyManagersExtractor(), $cached));

    }

    public function addSpecificManager(FPC_ISpecificPropertiesManager $manager) {
        $this->_specificManagers->addSpecificManager($manager);
    }


    /**
     * @param $obj the object from which the properties must be get
     * @return array an associative array of $propertyName => $propertyValue pair or null if this manager cannot handle this object
     */
    function getProperties($obj)
    {
        return $this->_manager->getProperties($obj);
    }

    /**
     * @param mixed $obj the object the properties must be set
     * @param array $propertyValues an associative array of $propertyName => $propertyValue pair
     * @return bool true if the properties has been set by this manager, false otherwise
     */
    function setProperties($obj, $propertyValues)
    {
        return $this->_manager->setProperties($obj, $propertyValues);
    }

}
