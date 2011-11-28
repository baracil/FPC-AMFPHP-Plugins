<?php
/**
 *   @copyright Copyright (c) 2011, Bastien Aracil
 *   All rights reserved.
 *   New BSD license. See http://en.wikipedia.org/wiki/Bsd_license
 *
 *   Redistribution and use in source and binary forms, with or without
 *   modification, are permitted provided that the following conditions are met:
 *      * Redistributions of source code must retain the above copyright
 *        notice, this list of conditions and the following disclaimer.
 *      * Redistributions in binary form must reproduce the above copyright
 *        notice, this list of conditions and the following disclaimer in the
 *        documentation and/or other materials provided with the distribution.
 *      * The name of Bastien Aracil may not be used to endorse or promote products
 *        derived from this software without specific prior written permission.
 *
 *   THIS SOFTWARE IS PROVIDED BY THE COPYRIGHT HOLDERS AND CONTRIBUTORS "AS IS" AND
 *   ANY EXPRESS OR IMPLIED WARRANTIES, INCLUDING, BUT NOT LIMITED TO, THE IMPLIED
 *   WARRANTIES OF MERCHANTABILITY AND FITNESS FOR A PARTICULAR PURPOSE ARE
 *   DISCLAIMED. IN NO EVENT SHALL BASTIEN ARACIL BE LIABLE FOR ANY
 *   DIRECT, INDIRECT, INCIDENTAL, SPECIAL, EXEMPLARY, OR CONSEQUENTIAL DAMAGES
 *   (INCLUDING, BUT NOT LIMITED TO, PROCUREMENT OF SUBSTITUTE GOODS OR SERVICES;
 *   LOSS OF USE, DATA, OR PROFITS; OR BUSINESS INTERRUPTION) HOWEVER CAUSED AND
 *   ON ANY THEORY OF LIABILITY, WHETHER IN CONTRACT, STRICT LIABILITY, OR TORT
 *   (INCLUDING NEGLIGENCE OR OTHERWISE) ARISING IN ANY WAY OUT OF THE USE OF THIS
 *   SOFTWARE, EVEN IF ADVISED OF THE POSSIBILITY OF SUCH DAMAGE.
 *
 *   @package FPC_AMFPHP_Plugins_FPCAuthentication
 */

require_once "ClassLoader.php";

/**
 * The main class of the FPCAuthentication plugin
 *
 * <b>Overview</b>
 * 
 * FPCAuthentication is an AMFPHP plugin that manages authentication of users with a login
 * and a password. The plugin offers two ways to authenticate, a basic and an elaborate one.
 *
 * The basic method is a simple call to the server with the user login and its secret value
 * (generally a digest of a password) and the server grants or rejects the access according
 * to these two values. This method is simple but not 100% secured if not used over a secured
 * connection (a https connection) since message can be intercepted and even if a digest of
 * the password is sent, this digest is the password for the server.
 *
 * The elaborate method is an exchange of 4 messages between the server and the client.
 * The secret of the user is never sent explicitly nor a direct digest of it. This method
 * allows the authentication of the user on the server and the authentication of the
 * server on the client side. Also, it provides a random password only known by the client
 * and the server and only valid as long as the user does not logout. This temporary password
 * can be used to send sensible data to the server without compromising the user password.
 *
 * <b>Configuration</b>
 *
 * The plugins has several configuration parameters but only one is mandatory, all the others have
 * default values that should be sufficient for most of the cases. Below is the list of the
 * configuration parameters (the values in parentheses indicate if the parameter is mandatory or not
 * , and which interface the parameter must implement) :
 *
 * <ul>
 * <li>'<b>secretProvider</b>' (mandatory, {@link FPCAuthentication_ISecretProvider}) : This parameter is used to retrieve the user secret from his login. Below is a very simple implementation of such parameter :
 *
 * <code>
 * class SimpleSecretProvider implements FPCAuthentication_ISecretProvider {
 *     /**
 *      * @abstract
 *      * @param string $login
 *      * @return string the secret of the user with the given login. The secret
 *      * is not necessarily the explicit password. The only constraint is that
 *      * is should be the same thing provided by the client (an operation might
 *      * then be mandatory between the user input and the call to the server).
 *      {@*}
 *     function getSecret($login);
 *     {
 *         switch ($login) {
 *           case "admin" : return "adminPassword";
 *           case "user1" : return "user1Password";
 *         }
 *         return null;
 *     }
 * }
 * </code>
 *
 * Common implementations would access a database to get the secret of the user.
 * </li>
 *
 * <li>'<b>rolesProvider</b>' (optional, {@link FPCAuthentication_IRolesProvider}) : This parameter is used to retrieve the
 * roles of a user. It is used only if the user is successfully authenticated. Below is a
 * simple implementation of this parameter :
 *
 * <code>
 * class SimpleRolesProvider implements FPCAuthentication_IRolesProvider {
 *     /**
 *      * Called only if the authentication succeed.
 *      *
 *      * @param $login login of the authenticated user
 *      * @return array of string that defines the roles of the authenticated user
 *      {@*}
 *     function getRoles($login) {
 *         switch ($login) {
 *             case "admin" : return array("USER_INFO_EDITOR",
 *                                 "ORDER_EDITOR", "INVOICE_EDITOR");
 *             case "user1" : return array("USER_INFO_VIEWER", "ORDER_VIEWER");
 *         }
 *         return null;
 *     }
 * }
 * </code>
 *
 * Even if this parameter is optional, the developer might want to change its default behaviour
 * that is to send an empty array, i.e. the user has no roles.
 * </li>
 *
 * <li>'<b>builder</b>' (optional, {@link FPCAuthentication_IBuilder}) : The plugin saves some data during the authentication process and these data are used to create the
 * result returns by the authentication methods. The transformation of these data is done by this parameter.
 * The default builder (see below) should be enough but can be modified if more information is needed.
 *
 * <code>
 * class FPCAuthentication_DefaultBuilder implements FPCAuthentication_IBuilder {
 *
 *     function build(FPCAuthentication_Result $result)
 *     {
 *         if (is_null($result)) {
 *             return null
 *         }
 *
 *         return array(
 *             'login' => $result->getLogin(),
 *             'authenticated' => $this->getAuthenticated(),
 *             'roles' => $this->getRoles()
 *     }
 * }
 * </code>
 * </li>
 *
 * <li>'<b>challengeProvider</b>' (optional, {@link FPCAuthentication_IChallengeProvider}) : Used by the plugin to create random challenge sent to the client.
 * The default provider ({@link FPCAuthentication_DefaultChallengeProvider}) should be sufficient for most of the cases.</li>
 *
 * <li>'<b>challengeSolver</b>' (optional, {@link FPCAuthentication_IChallengeProvider}) : Used by the plugin to solve challenge
 * sent by the client. The default solver ({@link FPCAuthentication_DefaultChallengeSolver}) should be sufficient. The solver
 * can be changed but must be exactly the same on the client and on the server.</li>
 *
 * </ul>
 *
 * <b>Limitations</b>
 *
 * The plugin intercepts any calls to the 'fpcAuthentication' service and it redirects them to an instance of {@link FPCAuthentication_LoginService},
 * so if you use the FPCAuthentication plugin you cannot have a service named 'fpcAuthentication' (actually you can but all the calls to it will be intercepted
 * by the plugin and most probably this will result in an error).
 *
 * <b>Direct authentication</b>
 *
 * For a direct authentication, the client needs to call the 'fpcAuthentication::authenticate' method
 * (see {@link FPCAuthentication_LoginService::authenticate()})  with the login and the secret of
 * the user trying to authenticate.
 *
 * If the server validates the secret then the user is authenticated until the 'fpcAuthentication::logout'
 * method is called. Otherwise a FPCAuthentication_Exception is thrown.
 *
 * <b>Handshake authentication</b>
 *
 * For handshake authentication, four messages are exchange between the client and the server. Each message has at least
 * a type, a data property and a challenge property. The type (see {@link FPCAuthentication_HandshakeType})
 * is a string that defines the kind of the message and the meaning of the data and challenge property. Below
 * is the list of all the type :
 * <ul>
 * <li>{@link FPCAuthentication_HandshakeType::CHALLENGE_REQUEST} : type of the message sent by the client to initiate the handshake authentication.</li>
 * <li>{@link FPCAuthentication_HandshakeType::CHALLENGE} : type of the message sent by the server as a response to a {@link FPCAuthentication_HandshakeType::CHALLENGE_REQUEST} message</li>
 * <li>{@link FPCAuthentication_HandshakeType::CHALLENGE_ANSWER} : type of the message sent by the client as a response to a {@link FPCAuthentication_HandshakeType::CHALLENGE} message</li>
 * <li>{@link FPCAuthentication_HandshakeType::CHALLENGE_VALIDATION} : type of the message sent by the server as a response to a {@link FPCAuthentication_HandshakeType::CHALLENGE_ANSWER} message</li>
 * </ul>
 *
 * The data and challenge properties are BASE64 encoded in all messages. Below is the sequence of the messages.
 *
 * <pre>
 *     -*- Client -*-                              -*- Server -*-
 *  type      = challengeRequest
 *  data      = login of the user      ----->
 *  challenge = array of random bytes
 *
 *                                              type      = challenge
 *                                              data      = answer to the challenge of
 *                                     <-----               the previous message
 *                                              challenge = array of random bytes
 *
 *  type = challengeAnswer
 *  data = answer to the challenge of
 *         the previous message        ----->
 *  challenge = array of random bytes
 *
 *                                              type      = challengeValidation
 *                                              data      = answer to the challenge of
 *                                                          the previous message
 *                                     <-----   challenge = array of random bytes
 *                                              info      = information about the authenticated user
 *
 * </pre>
 * When a message is received the client or the server validates the answer to the challenge of the previous message.
 * The answer is computed with the {@link FPCAuthentication_IChallengeSolver} and should use the challenge and the secret
 * of the user trying to authenticate.
 *
 * @package FPC_AMFPHP_Plugins_FPCAuthentication
 * @author Bastien Aracil
 */
class FPCAuthentication {

    /**
     * Name of the emulated service
     */
    const EMULATED_SERVICE_NAME = "fpcAuthentication";

    /**
     * configuration key for the secret provider
     */
    const SECRET_PROVIDER_KEY = "secretProvider";

    /**
     * configuration key for the role provider
     */
    const ROLES_PROVIDER_KEY = "rolesProvider";

    /**
     * configuration key for the challenge solver
     */
    const CHALLENGE_SOLVER_KEY = "challengeSolver";

    /**
     * configuration key for the result builder
     */
    const BUILDER_KEY = "builder";

    /**
     * configuration key for the challenge provider
     */
    const CHALLENGE_PROVIDER_KEY = "challengeProvider";

    /**
     * session key for the loginService configuration
     */
    const FPC_LOGIN_CONFIG_KEY = "FPCAuthenticationConfig";

    /**
     * session key for the common secret key
     */
    const FPC_COMMON_SECRET_KEY = "FPCAuthenticationCommonSecret";

    /**
     * @var Amfphp_Core_Common_ClassFindInfo
     */
    private $loginServiceClassInfo;

    /**
     * @var FPCAuthentication_LoginServiceConfig
     */
    private $loginServiceConfig;

    public function __construct(array $config = null) {

        //hook the plugin
        $filterManager = Amfphp_Core_FilterManager::getInstance();
        $filterManager->addFilter(Amfphp_Core_Gateway::FILTER_SERVICE_NAMES_2_CLASS_FIND_INFO, $this, "filterServiceNames2ClassFindInfo");
        $filterManager->addFilter(Amfphp_Core_Common_ServiceRouter::FILTER_SERVICE_OBJECT, $this, "filterServiceObject");

        //prepare the plugin default configuration
        $this->loginServiceConfig = new FPCAuthentication_LoginServiceConfig();
        $this->loginServiceConfig->setDefaultBuilder(new FPCAuthentication_DefaultBuilder());
        $this->loginServiceConfig->setDefaultRolesProvider(new FPCAuthentication_DefaultRolesProvider());
        $this->loginServiceConfig->setDefaultChallengeSolver(new FPCAuthentication_DefaultChallengeSolver());
        $this->loginServiceConfig->setDefaultChallengeProvider(new FPCAuthentication_DefaultChallengeProvider());

        if ($config) {
            if (isset($config[self::SECRET_PROVIDER_KEY])) {
                $this->loginServiceConfig->setSecretProvider($config[self::SECRET_PROVIDER_KEY]);
            }
            if (isset($config[self::ROLES_PROVIDER_KEY])) {
                $this->loginServiceConfig->setRolesProvider($config[self::ROLES_PROVIDER_KEY]);
            }
            if (isset($config[self::BUILDER_KEY])) {
                $this->loginServiceConfig->setBuilder($config[self::BUILDER_KEY]);
            }
            if (isset($config[self::CHALLENGE_SOLVER_KEY])) {
                $this->loginServiceConfig->setBuilder($config[self::CHALLENGE_SOLVER_KEY]);
            }
            if (isset($config[self::CHALLENGE_PROVIDER_KEY])) {
                $this->loginServiceConfig->setBuilder($config[self::CHALLENGE_PROVIDER_KEY]);
            }
        }

        $this->loginServiceConfig->validate();
        $this->loginServiceClassInfo = new Amfphp_Core_Common_ClassFindInfo(dirname(__FILE__)."/LoginService.php","FPCAuthentication_LoginService");

    }

    /**
     * Hook point to save the classFindInfo of the {@link FPCAuthentication_LoginService} to override
     * the calls to the fpcAuthentication service
     *
     * @param $serviceNames2ClassFindInfo
     * @return array
     */
    public function filterServiceNames2ClassFindInfo($serviceNames2ClassFindInfo) {
        $serviceNames2ClassFindInfo[self::EMULATED_SERVICE_NAME] = $this->loginServiceClassInfo;
        return $serviceNames2ClassFindInfo;
    }

    /**
     * Hook point to set the configuration of the fpcAuthentication service or denied access to specific methods
     * if no user is authenticated.
     *
     * @throws FPCAuthentication_Exception
     * @param $serviceObject
     * @param $serviceName
     * @param $methodName
     * @param $parameters
     * @return
     */
    public function filterServiceObject($serviceObject, $serviceName, $methodName, $parameters) {

        if ($serviceName == self::EMULATED_SERVICE_NAME) {
            $this->setConfiguration($serviceObject);
            $allowedNotAuthenticated = true;
        }
        else {
            $allowedNotAuthenticated = $this->allowedNotAuthenticated($serviceName, $methodName);
        }

        if (!$allowedNotAuthenticated && !$this->isAuthenticated()) {
            throw new FPCAuthentication_Exception("Call to $serviceName.$methodName is allowed only for authenticated user");
        }

        return $serviceObject;
    }

    private function setConfiguration(FPCAuthentication_LoginService $service) {
        $service->setConfig($this->loginServiceConfig);
    }

    private function allowedNotAuthenticated($serviceName, $methodName)
    {
        //TODO allows the user to configure a white and black list of service/method that can be called
        //even if the user is not authenticated.
        // For now, allows any method.
        return true;
    }

    /**
     * @return bool
     */
    private function isAuthenticated() {
        $result = FPCAuthentication_Result::getResult();
        return $result->getAuthenticated();
    }

}
