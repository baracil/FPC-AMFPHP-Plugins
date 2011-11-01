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
 * Date: 10/07/11
 */

require_once "ServicePropertySetterConfig.php";

class ServicePropertySetter
{

    const CONFIG_KEY = "config";

    /**
     * @var FPC_ServicePropertySetterConfig
     */
    private $config;

    /**
     * constructor.
     * @param array $config optional key/value pairs in an associative array. Used to override default configuration values.
     */
    public function  __construct(array $config = null)
    {
        $filterManager = Amfphp_Core_FilterManager::getInstance();
        $filterManager->addFilter(Amfphp_Core_Common_ServiceRouter::FILTER_SERVICE_OBJECT, $this, "filterServiceObject");

        $this->config = null;
        if ($config) {
            if (isset($config[self::CONFIG_KEY])) {
                $pluginConfig = $config[self::CONFIG_KEY];
                if ($pluginConfig instanceof FPC_ServicePropertySetterConfig) {
                    $this->config = $pluginConfig;
                }
            }
        }

        if (is_null($this->config)) {
            //empty configuration. Not null to avoid null test later.
            $this->config = new FPC_ServicePropertySetterConfig();
        }

    }

    /**
     * called when the service object is created, just before the method call.
     * Set register properties in the configuration
     *
     * @param Object $serviceObject
     * @param String $serviceName
     * @param String $methodName
     * @return Object
     */
    public function filterServiceObject($serviceObject, $serviceName, $methodName, $parameters)
    {
        if (!$this->config->hasServiceSetting($serviceName)) {
            return;
        }

        $serviceSetting = $this->config->getServiceSetting($serviceName);
        $setter = $this->config->getPropertySetter();

        $properties = $serviceSetting->getProperties();
        foreach ($properties as $propertyName => $propertyValue) {
            $setter->setProperty($serviceObject, $propertyName, $propertyValue);
        }

        if (method_exists($serviceObject, "afterPropertiesSet")) {
            $serviceObject->afterPropertiesSet();
        }
    }

}
