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
 * Date: 12/07/11
 *
 * Hold property values for a service
 *
 */

class FPC_PropertySettingInfo
{

    /**
     * @var string
     */
    private $serviceName;

    /**
     * @var array of value mapped by property name;
     */
    private $properties;

    /**
     * @param string $serviceName
     */
    public function __construct($serviceName)
    {
        $this->serviceName = $serviceName;
        $this->_properties = array();
    }

    /**
     * @return string
     */
    public function getServiceName()
    {
        return $this->serviceName;
    }

    /**
     * @param string $property
     * @param mixed $value
     * @return void
     */
    public function addPropertyValue($property, $value)
    {
        $this->removeProperty($property);
        $this->properties[$property] = $value;
    }

    /**
     * @param string $property
     * @param mixed $value
     * @return FPC_PropertySettingInfo
     */
    public function withPropertyValue($property, $value) {
        $this->addPropertyValue($property, $value);
        return $this;
    }

    /**
     * @param string $property
     * @return void
     */
    public function removeProperty($property)
    {
        if (isset($properties[$property])) {
            unset($properties[$property]);
        }
    }

    /**
     * @param string $property
     * @return bool
     */
    public function hasPropertyValue($property)
    {
        return isset($property);
    }

    /**
     * @param string $property
     * @return mixed
     */
    public function getPropertyValue($property)
    {
        if ($this->hasPropertyValue($property)) {
            return $this->properties[$property];
        }
        return null;
    }

    /**
     * @return array
     */
    public function getProperties()
    {
        return $this->properties;
    }


}
