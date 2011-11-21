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

/**
 *  The login service that handles the authentication process.
 *
 *  @package FPC_AMFPHP_Plugins_FPCAuthentication
 *  @author Bastien Aracil
 */
class FPCAuthentication_LoginService {

    /**
     * Configuration data for the service
     *
     * @var FPCAuthentication_LoginServiceConfig $config
     */
    private $_config;

    public function __construct($config = null) {
        $this->_config = $config;
    }

    /**
     * The direct method for authentication. Uses the login and a secret of a user
     * to identify and to authenticate it.
     *
     * @param $login the login of the user trying to authenticated
     * @param $secret the secret of the user trying to authenticated
     * @return mixed
     */
    public function authenticate($login, $secret) {
        $result = FPCAuthentication_Result::getLoginResult($login);

        //get the expected secret from the given login
        $expectedSecret = $this->_config->getSecretProvider()->getSecret($login);

        if ($expectedSecret == $secret) {
            //expected and provided secret match. The authentication is successful
            //retrieve the roles and update the authentication result with them
            $roles = $this->_config->getRolesProvider()->getRoles($login);
            $result->updateOnSuccess($roles);
        }
        else {
            //expected and provided secret do not match. The authentication failed
            //update the authentication result accordingly
            $result->updateOnFailure();
        }

        //save the authentication result into the SESSION
        $result->save();

        //let's finish the job.
        if (!$result->getAuthenticated()) {
            //the authentication failed, throw an exception to warn so
            $result->throwException();
        }

        //save the secret as common key in the session
        $_SESSION[FPCAuthentication::FPC_COMMON_SECRET_KEY] = $secret;

        return $this->_config->getBuilder()->build($result);
    }

    /**
     * The handshake authentication process. Check the documentation of the plugin (see {@link FPCAuthentication}
     * for more details.
     *
     * @param $type the type of the handshake message
     * @param $data the data of the handshake message
     * @param $challenge the $challenge the sender expect the receiver to response
     * @throw FPCAuthentication_Exception if the authentication failed
     * @return FPCAuthentication_HandshakeMessage the response of the server
     */
    public function handshake($type, $data, $challenge) {
        $decodedData = base64_decode($data);
        $decodedChallenge = base64_decode($challenge);

        //retrieve the handler for the given type
        $handler = FPCAuthentication_Handler::getHandler($type, $this->_config);

        //handle the message
        $msg = $handler->handle($decodedData, $decodedChallenge);
        //encode in BASE64 the challenge before sending it over the network
        $msg->challenge = base64_encode($msg->challenge);
        $msg->data = base64_encode($msg->data);

        return $msg;
    }

    /**
     * Clear all the session data regarding the authentication result and process.
     * 
     * @return void
     */
    public function logout() {
        FPCAuthentication_Result::clear();
        FPCAuthentication_HandshakeData::clear();
        unset($_SESSION[FPCAuthentication::FPC_COMMON_SECRET_KEY]);
    }

    /**
     * Set the configuration data of the LoginService
     *
     * @param FPCAuthentication_LoginServiceConfig $config
     */
    public function setConfig($config)
    {
        $this->_config = $config;
    }

    /**
     * Return the configuration data of the LoginService
     *
     * @return FPCAuthentication_LoginServiceConfig
     */
    public function getConfig()
    {
        return $this->_config;
    }

}
