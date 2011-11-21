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
 * The data used by the handshake authentication process.
 *
 * The data are saved into the Session since several exchange between the client
 * and the server are needed to complete the authentication.
 *
 * @package FPC_AMFPHP_Plugins_FPCAuthentication
 * @subpackage handler
 * @author Bastien Aracil
 */
class FPCAuthentication_HandshakeData {

    const FPC_HANDSHAKE_DATA_KEY = "FPCAuthenticationHandshakeData";

    /**
     * Retrieve the handshake data from the session. return null if none found.
     *
     * @static
     * @return FPCAuthentication_HandshakeData|null
     */
    public static function getData() {
        if (!isset($_SESSION[self::FPC_HANDSHAKE_DATA_KEY])) {
            return null;
        }

        $data = new FPCAuthentication_HandshakeData();
        return $data->restore();
    }

    /**
     * Clear any handshake data from the session
     * @static
     * @return void
     */
    public static function clear() {
        self::startSession();
        unset($_SESSION[self::FPC_HANDSHAKE_DATA_KEY]);
    }

    private static function startSession() {
        if (session_id() == "") {
            session_start();
        }
    }

    private $_login;

    private $_expectedType;

    private $_expectedAnswer;

    /**
     * @param $login the login of the current user trying to authenticate to set
     * @return void
     */
    public function setLogin($login)
    {
        $this->_login = $login;
    }

    /**
     * @return the login of the current user trying to authenticate
     */
    public function getLogin()
    {
        return $this->_login;
    }

    /**
     * @param $expectedAnswer the expected answer from the client to set
     * @return void
     */
    public function setExpectedAnswer($expectedAnswer)
    {
        $this->_expectedAnswer = $expectedAnswer;
    }

    /**
     * @return the expected answer from the client for the server challenge
     */
    public function getExpectedAnswer()
    {
        return $this->_expectedAnswer;
    }

    /**
     * @param $expectedType the expected handshake message type that will be sent by the client to set
     * @return void
     */
    public function setExpectedType($expectedType)
    {
        $this->_expectedType = $expectedType;
    }

    /**
     * @return the expected handshake message type that will be sent by the client
     */
    public function getExpectedType()
    {
        return $this->_expectedType;
    }

    /**
     * @return array an associative array with the properties of this handshake data
     */
    public function toArray() {
        return array('login' => $this->_login, 'expectedType' => $this->_expectedType, 'expectedAnswer' => $this->_expectedAnswer);
    }

    /**
     * Initialize the current handshake data with the give data
     *
     * @param $data an associative array of handshake data properties and values
     * @return FPCAuthentication_HandshakeData
     */
    public function fromArray($data) {
        $this->_login = isset($data['login'])?$data['login']:null;
        $this->_expectedType = isset($data['expectedType'])?$data['expectedType']:null;
        $this->_expectedAnswer = isset($data['expectedAnswer'])?$data['expectedAnswer']:null;
        return $this;
    }

    /**
     * Initialize the current handshake data from the data save into the session
     *
     * @return FPCAuthentication_HandshakeData $this
     */
    public function restore() {
       if (isset($_SESSION[self::FPC_HANDSHAKE_DATA_KEY])) {
           $this->fromArray($_SESSION[self::FPC_HANDSHAKE_DATA_KEY]);
       }
        return $this;
    }

    /**
     * Save the current handshake data into the session
     *
     * @return void
     */
    public function save() {
        $_SESSION[self::FPC_HANDSHAKE_DATA_KEY] = $this->toArray();
    }

}
