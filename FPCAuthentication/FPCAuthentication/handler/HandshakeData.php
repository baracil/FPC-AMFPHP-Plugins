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
 * Date: 09/11/11
 */
 
class FPCAuthentication_HandshakeData {

    const FPC_HANDSHAKE_DATA_KEY = "FPCAuthenticationHandshakeData";


    public static function getData() {
        if (!isset($_SESSION[self::FPC_HANDSHAKE_DATA_KEY])) {
            return null;
        }

        $data = new FPCAuthentication_HandshakeData();
        return $data->restore();
    }

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

    public function setLogin($login)
    {
        $this->_login = $login;
    }

    public function getLogin()
    {
        return $this->_login;
    }

    public function setExpectedAnswer($expectedAnswer)
    {
        $this->_expectedAnswer = $expectedAnswer;
    }

    public function getExpectedAnswer()
    {
        return $this->_expectedAnswer;
    }

    public function setExpectedType($expectedType)
    {
        $this->_expectedType = $expectedType;
    }

    public function getExpectedType()
    {
        return $this->_expectedType;
    }

    public function toArray() {
        return array('login' => $this->_login, 'expectedType' => $this->_expectedType, 'expectedAnswer' => $this->_expectedAnswer);
    }

    public function fromArray($data) {
        $this->_login = isset($data['login'])?$data['login']:null;
        $this->_expectedType = isset($data['expectedType'])?$data['expectedType']:null;
        $this->_expectedAnswer = isset($data['expectedAnswer'])?$data['expectedAnswer']:null;
        return $this;
    }

    public function restore() {
       if (isset($_SESSION[self::FPC_HANDSHAKE_DATA_KEY])) {
           $this->fromArray($_SESSION[self::FPC_HANDSHAKE_DATA_KEY]);
       }
        return $this;
    }

    public function save() {
        $_SESSION[self::FPC_HANDSHAKE_DATA_KEY] = $this->toArray();
    }

}
