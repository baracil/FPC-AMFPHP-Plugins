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
 * Date: 04/11/11
 */


require_once "ClassLoader.php";

class FPCAuthentication {

    const AUTHENTICATOR_KEY = "authenticator";

    const BUILDER_KEY = "builder";

    const FPC_LOGIN_CONFIG_KEY = "FPCAuthenticationConfig";

    const FPC_LOGIN_RESULT_KEY = "FPCAuthenticationResult";

    /**
     * @var Amfphp_Core_Common_ClassFindInfo
     */
    private $loginServiceClassInfo;

    public function __construct(array $config = null) {

        $dirName = dirname(__FILE__);
        $this->loginServiceClassInfo = new Amfphp_Core_Common_ClassFindInfo($dirName . DIRECTORY_SEPARATOR . "LoginService.php", "FPCAuthentication_LoginService");

        $filterManager = Amfphp_Core_FilterManager::getInstance();
        $filterManager->addFilter(Amfphp_Core_Gateway::FILTER_SERVICE_NAMES_2_CLASS_FIND_INFO, $this, "filterServiceNames2ClassFindInfo");

        $authenticator = null;
        $builder = null;

        if ($config) {
            if (isset($config[self::AUTHENTICATOR_KEY])) {
                $authenticator = $config[self::AUTHENTICATOR_KEY];
            }
            if (isset($config[self::BUILDER_KEY])) {
                $builder = $config[self::BUILDER_KEY];
            }
        }

        if (is_null($authenticator) || !($authenticator instanceof FPCAuthentication_IAuthenticator)) {
            throw new Exception("Invalid configuration for plugin FPCAuthentication : authenticator must be set and implement FPCAuthentication_IAuthenticator ");
        }

        if (is_null($builder)) {
            $builder = new FPCAuthentication_DefaultBuilder();
        }

        if (!($builder instanceof FPCAuthentication_IBuilder)) {
            throw new Exception("Invalid configuration for plugin FPCAuthentication: builder must implement FPCAuthentication_IBuilder");
        }

        $loginServiceConfig = new FPCAuthentication_LoginServiceConfig();
        $loginServiceConfig->authenticator = $authenticator;
        $loginServiceConfig->builder = $builder;

        $GLOBALS[self::FPC_LOGIN_CONFIG_KEY] = $loginServiceConfig;
    }

    public function filterServiceNames2ClassFindInfo($serviceNames2ClassFindInfo) {
        $serviceNames2ClassFindInfo["fpclogin"] = $this->loginServiceClassInfo;
        return $serviceNames2ClassFindInfo;
    }

}
