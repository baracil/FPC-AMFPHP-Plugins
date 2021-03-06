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
 *   @subpackage handler
 */

/**
 *   Abstract handler for the handshake messages
 *
 *   @package FPC_AMFPHP_Plugins_FPCAuthentication
 *   @subpackage handler
 *   @author Bastien Aracil
 */
abstract class FPCAuthentication_Handler {

    /**
     * Retrieve the appropriate handler for a given handshake message type
     *
     * @static
     * @param $type the handshake message type
     * @param $config FPCAuthentication_LoginServiceConfig the configuration of the LoginService used to initialize the handler
     * @return FPCAuthentication_Handler
     */
    public static function getHandler($type,FPCAuthentication_LoginServiceConfig $config) {
        $handler = null;
        switch ($type) {
            case FPCAuthentication_HandshakeType::CHALLENGE_REQUEST :
                $handler = new FPCAuthentication_ChallengeRequestHandler();
                break;
            case FPCAuthentication_HandshakeType::CHALLENGE_ANSWER :
                $handler = new FPCAuthentication_ChallengeAnswerHandler();
                break;
            default :
                $handler = new FPCAuthentication_InvalidTypeHandler($type);
        }

        if (!is_null($handler)) {
            $handler->_config = $config;
        }

        return $handler;
    }

    protected static function start_session() {
        if (session_id() == "") {
            session_start();
        }
    }

    /**
     * @var FPCAuthentication_LoginServiceConfig
     */
    private $_config;

    /**
     * Entry point of the handler.
     *
     * Performs the type and challenge answer tests and call the extending handler
     * for specific treatment.
     *
     * @param $data the data of the incoming message
     * @param $challenge the challenge of the incoming message
     * @return FPCAuthentication_HandshakeMessage
     */
    public function handle($data, $challenge) {
        $this->preHandle();

        self::start_session();

        //retrieve the data saved from the previous call (can be a new object for the beginning of the handshake)
        $sessionData = $this->getSessionData($data);

        //the session should never be null. If this happened, then the handshake protocol has
        //not been respected
        if (is_null($sessionData)) {
            throw new FPCAuthentication_Exception("Invalid handshake message. No session data defined.");
        }

        //retrieve user data (login and secret) and the login data for the user
        $login = $sessionData->getLogin();
        $secret = $this->getConfig()->getSecretProvider()->getSecret($login);

        $result = FPCAuthentication_Result::getLoginResult($sessionData->getLogin());


        //let's check that the type of the message is the one expected
        $handledType = $this->getHandledType();

        if ($handledType != $sessionData->getExpectedType()) {
            $result->updateOnFailure();
            $result->throwException();
        }

        //validation challenge answer
        if (!$this->validateChallengeAnswer($sessionData, $data)) {
            $result->updateOnFailure();
            $result->throwException();
        }

        $result = $this->prepareResult($result);


        //prepare our response
        $newType = FPCAuthentication_HandshakeType::getNext($handledType);
        $newData = $this->getConfig()->getChallengeSolver()->solve($challenge, $secret);
        $newChallenge = $this->getConfig()->getChallengeProvider()->getChallenge();
        $newInfo = null;

        //update session data
        $sessionData->setExpectedType(FPCAuthentication_HandshakeType::getNext($newType));
        $sessionData->setExpectedAnswer($this->getConfig()->getChallengeSolver()->solve($newChallenge, $secret));
        $sessionData->save();

        if ($result->getAuthenticated()) {
            //authentication done, send user information, clear session data and save common key
            $newInfo = $this->getConfig()->getBuilder()->build($result);
            $this->clearSessionData();
            $_SESSION[FPCAuthentication::FPC_COMMON_SECRET_KEY] = $this->getConfig()->getChallengeSolver()->solve($sessionData->getExpectedAnswer(), $secret);
            error_log(base64_encode($_SESSION[FPCAuthentication::FPC_COMMON_SECRET_KEY]));
        }


        return $this->formMessage($newType, $newData, $newChallenge, $newInfo);
    }

    /**
     * Call before any treatment occurred.
     *
     * @abstract
     * @return void
     */
    protected abstract function preHandle();

    /**
     * Retrieve the handshake data saved into the session.
     *
     * The method is abstract since some handshake message might
     * completely reinitialize the data.
     *
     * @abstract
     * @param $data
     * @return FPCAuthentication_HandshakeData
     */
    protected abstract function getSessionData($data);

    /**
     * @abstract
     * @return string the handshake message type this handler handles.
     */
    protected abstract function getHandledType();

    /**
     * Validate the challenge answer.
     *
     * The method is abstract since some handshake message might not need
     * a validation or the given $data is not a challenge answer (like for the first message).
     *
     * @abstract
     * @param FPCAuthentication_HandshakeData $sessionData
     * @param $data
     * @return bool
     */
    protected abstract function validateChallengeAnswer(FPCAuthentication_HandshakeData $sessionData, $data);

    /**
     * Prepare the {@link FPCAuthentication_Result} after the handling has been completed.
     *
     * @abstract
     * @param FPCAuthentication_Result $result
     * @return FPCAuthentication_Result
     */
    protected abstract function prepareResult($result);

    /**
     * @return FPCAuthentication_LoginServiceConfig the loginService configuration
     */
    protected function getConfig()
    {
        return $this->_config;
    }

    /**
     * Remove any information saved into the session during the handshake process
     *
     * @return void
     */
    protected function clearSessionData() {
        self::start_session();
        FPCAuthentication_HandshakeData::clear();
        unset($_SESSION[FPCAuthentication::FPC_COMMON_SECRET_KEY]);
    }

    /**
     * Create the {@link FPCAuthentication_HandshakeMessage} the handler returns
     *
     * @param $type
     * @param $data
     * @param $challenge
     * @param $info
     * @return FPCAuthentication_HandshakeMessage
     */
    private function formMessage($type, $data, $challenge, $info) {
        $msg = new FPCAuthentication_HandshakeMessage();
        $msg->type = $type;
        $msg->data = $data;
        $msg->challenge = $challenge;
        $msg->info = $info;
        return $msg;
    }


}
