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

    const SECRET_PROVIDER_KEY = "secretProvider";

    const ROLES_PROVIDER_KEY = "rolesProvider";

    const CHALLENGE_SOLVER_KEY = "challengeSolver";

    const BUILDER_KEY = "builder";

    const CHALLENGE_PROVIDER_KEY = "challengeProvider";

    const FPC_LOGIN_CONFIG_KEY = "FPCAuthenticationConfig";

    const FPC_COMMON_SECRET_KEY = "FPCAuthenticationCommonSecret";

    /**
     * @var Amfphp_Core_Common_ClassFindInfo
     */
    private $loginServiceClassInfo;

    public function __construct(array $config = null) {

        //create the classFindInfo for the login service
        $dirName = dirname(__FILE__);
        $this->loginServiceClassInfo = new Amfphp_Core_Common_ClassFindInfo($dirName . DIRECTORY_SEPARATOR . "LoginService.php", "FPCAuthentication_LoginService");

        //hook the plugin
        $filterManager = Amfphp_Core_FilterManager::getInstance();
        $filterManager->addFilter(Amfphp_Core_Gateway::FILTER_SERVICE_NAMES_2_CLASS_FIND_INFO, $this, "filterServiceNames2ClassFindInfo");

        //prepare the plugin default configuration
        $loginServiceConfig = new FPCAuthentication_LoginServiceConfig();
        $loginServiceConfig->setDefaultBuilder(new FPCAuthentication_DefaultBuilder());
        $loginServiceConfig->setDefaultRolesProvider(new FPCAuthentication_DefaultRolesProvider());
        $loginServiceConfig->setDefaultChallengeSolver(new FPCAuthentication_DefaultChallengeSolver());
        $loginServiceConfig->setDefaultChallengeProvider(new FPCAuthentication_DefaultChallengeProvider());

        if ($config) {
            if (isset($config[self::SECRET_PROVIDER_KEY])) {
                $loginServiceConfig->setSecretProvider($config[self::SECRET_PROVIDER_KEY]);
            }
            if (isset($config[self::ROLES_PROVIDER_KEY])) {
                $loginServiceConfig->setRolesProvider($config[self::ROLES_PROVIDER_KEY]);
            }
            if (isset($config[self::BUILDER_KEY])) {
                $loginServiceConfig->setBuilder($config[self::BUILDER_KEY]);
            }
            if (isset($config[self::CHALLENGE_SOLVER_KEY])) {
                $loginServiceConfig->setBuilder($config[self::CHALLENGE_SOLVER_KEY]);
            }
            if (isset($config[self::CHALLENGE_PROVIDER_KEY])) {
                $loginServiceConfig->setBuilder($config[self::CHALLENGE_PROVIDER_KEY]);
            }
        }

        $loginServiceConfig->validate();

        //store in global space since there is no way to set a property to instances
        //created by the AMFPHP serviceRouter
        $GLOBALS[self::FPC_LOGIN_CONFIG_KEY] = $loginServiceConfig;
    }

    public function filterServiceNames2ClassFindInfo($serviceNames2ClassFindInfo) {
        $serviceNames2ClassFindInfo["fpcauthentication"] = $this->loginServiceClassInfo;
        return $serviceNames2ClassFindInfo;
    }

}
