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
 * Date: 16/07/11
 *
 * This plugin performs
 */

require_once "ClassLoader.php";

class ServiceAccess
{

    const CONFIG_VOTER_PROVIDER_KEY = "serviceAccessVoterProvider";

    const CONFIG_USER_KEY = "serviceAccessUser";

    /**
     * @var FPC_IServiceAccessVoterProvider
     */
    private $_voterProvider;

    /**
     * @var FPC_IServiceAccessUser
     */
    private $_user;


    /**
     * constructor.
     * @param array $config optional key/value pairs in an associative array. Used to override default configuration values.
     */
    public function  __construct(array $config = null)
    {
        $filterManager = Amfphp_Core_FilterManager::getInstance();
        $filterManager->addFilter(Amfphp_Core_Common_ServiceRouter::FILTER_SERVICE_OBJECT, $this, "filterServiceObject");

        $this->_user = null;
        $this->_voterProvider = null;

        if ($config) {
            if (isset($config[self::CONFIG_VOTER_PROVIDER_KEY])) {
                $this->_voterProvider = $config[self::CONFIG_VOTER_PROVIDER_KEY];
            }

            if (isset($config[self::CONFIG_USER_KEY])) {
                $this->_user = $config[self::CONFIG_USER_KEY];
            }
        }

        /* set default provider */
        if(is_null($this->_voterProvider)) {
            $this->_voterProvider = new FPC_DefaultVoterProvider();
        }

        if (is_null($this->_user)) {
            throw new Exception("Invalid configuration for plugin ServiceAccess : serviceAccessUser must be set ");
        }

        if (!($this->_user instanceof FPC_IServiceAccessUser)) {
            throw new Exception("Invalid configuration for plugin ServiceAccess : serviceAccessUser must implement FPC_IServiceAccessUser ");
        }

        if (!($this->_voterProvider instanceof FPC_IServiceAccessVoterProvider)) {
            throw new Exception("Invalid configuration for plugin ServiceAccess : serviceAccessVoterProvider must implement FPC_IServiceAccessVoterProvider ");
        }

    }

    /**
     * called when the service object is created, just before the method call.
     * The voter for the method is obtained from the provider and vote on the
     * access status. if the access is denied a ServiceAccessException else this
     * method simply returns.
     *
     * @param Object $serviceObject
     * @param String $serviceName
     * @param String $methodName
     * @return void
     */
    public function filterServiceObject($serviceObject, $serviceName, $methodName, $parameters)
    {

        $voter = $this->_voterProvider->getVoter($serviceObject, $serviceName, $methodName);
        $user = $this->_user;

        if (is_null($voter)) {
            return;
        }

        $granted = $voter->accessGranted($user, $serviceObject, $parameters);

        if (!$granted) {
            throw new ServiceAccessException($serviceName, $methodName);
        }
    }

}
