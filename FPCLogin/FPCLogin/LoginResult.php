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
 * Date: 06/11/11
 */

class FPCLogin_Result {

    private static function startSession() {
        if (session_id() == "") {
            session_start();
        }
    }

    private $_authenticated;

    private $_login;

    private $_roles;

    private $_nbFailedAttempt;

    public function __construct($login = null) {
        $this->initialize($login);
    }

    /**
     * @param string $login
     * @return FPCLogin_Result
     */
    public function initialize($login = null) {
        $this->_login = $login;
        $this->_authenticated = false;
        $this->_roles = array();
        $this->_nbFailedAttempt = 0;
        return $this;
    }

    /**
     * @return FPCLogin_Result
     */
    public function updateOnFailure() {
        $this->_authenticated = false;
        $this->_roles = array();
        $this->_nbFailedAttempt++;
        return $this;
    }

    /**
     * @param $roles
     * @return FPCLogin_Result
     */
    public function updateOnSuccess($roles) {
        $this->_authenticated = true;
        $this->_roles = $roles;
        return $this;
    }

    public function getAuthenticated()
    {
        return $this->_authenticated;
    }

    public function getLogin()
    {
        return $this->_login;
    }

    public function getNbFailedAttempt()
    {
        return $this->_nbFailedAttempt;
    }

    public function getRoles()
    {
        return $this->_roles;
    }

    /**
     * @return array
     */
    public function toArray() {
        return array('login' => $this->_login, 'authenticated' => $this->_authenticated, 'roles' => $this->_roles, 'nbFailedAttempt' => $this->_nbFailedAttempt );
    }

    /**
     * @param $data
     * @return FPCLogin_Result
     */
    public function fromArray($data) {
        $this->_login  = isset($data['login'])?$data['login']:null;
        $this->_authenticated = isset($data['authenticated'])?$data['authenticated']:false;
        $this->_nbFailedAttempt = isset($data['nbFailedAttempt'])?$data['nbFailedAttempt']:0;
        $this->_roles = isset($data['roles'])?$data['roles']:array();
        return $this;
    }

    public function restore($sessionKey) {
        FPCLogin_Result::startSession();

        if (isset($_SESSION[$sessionKey])) {
            $this->fromArray($_SESSION[$sessionKey]);
        }
        else {
            $this->initialize(null);
        }

        return $this;
    }

    public function save($sessionKey) {
        FPCLogin_Result::startSession();
        $_SESSION[$sessionKey] = $this->toArray();
        return $this;
    }

    public static function clear($sessionKey) {
        FPCLogin_Result::startSession();
        unset($_SESSION[$sessionKey]);
    }


}
