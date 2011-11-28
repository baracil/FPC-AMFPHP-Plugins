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
 *   Results of the FPCAuthentication process
 *
 *   @package FPC_AMFPHP_Plugins_FPCAuthentication
 *   @author Bastien Aracil
 */
class FPCAuthentication_Result {

    /**
     * The key used to save the result into the session
     */
    const FPC_LOGIN_RESULT_KEY = "FPCAuthenticationResult";

    /**
     * Retrieves the FPCAuthentication result from the session. Create an empty one
     * if not found.
     *
     * @static
     * @return FPCAuthentication_Result
     */
    public static function getResult() {
        FPCAuthentication_Result::startSession();
        $result = new FPCAuthentication_Result();
        return $result->restore();
    }

    /**
     * Retrieves the FPCAuthentication_Result from the session. Create an empty
     * one if not found or if the login of the retrieved result does not match
     * the one provided.
     *
     * @static
     * @param $login
     * @return FPCAuthentication_Result
     */
    public static function getLoginResult($login) {
        FPCAuthentication_Result::startSession();

        $result = FPCAuthentication_Result::getResult();

        if ($result->getLogin() != $login) {
            $result->initialize($login);
        }

        return $result;
    }

    /**
     * Remove the FPCAuthentication_Result from the session.
     *
     * @static
     * @return void
     */
    public static function clear() {
        FPCAuthentication_Result::startSession();
        unset($_SESSION[self::FPC_LOGIN_RESULT_KEY]);
    }

    private static function startSession() {
        if (session_id() == "") {
            session_start();
        }
    }

    private $_authenticated;

    private $_login;

    private $_roles;

    /**
     * Create a new FPCAuthentication_Result and initialize it with
     * the give $login if any provided.
     *
     * @param null $login
     */
    public function __construct($login = null) {
        $this->initialize($login);
    }

    /**
     * Initialize the current FPCAuthentication_Result with the given $login.
     *
     * @param string $login
     * @return FPCAuthentication_Result
     */
    public function initialize($login = null) {
        $this->_login = $login;
        $this->_authenticated = false;
        $this->_roles = array();
        return $this;
    }

    /**
     * Update the current FPCAuthentication_Result to reflect a failure
     * occurred during the authentication
     *
     * @return FPCAuthentication_Result $this
     */
    public function updateOnFailure() {
        $this->_authenticated = false;
        $this->_roles = array();
        return $this;
    }

    /**
     * Update the current FPCAuthentication_Result to reflect a successful
     * authentication.
     *
     * @param $roles the roles of the authenticated user
     * @return FPCAuthentication_Result $this
     */
    public function updateOnSuccess($roles) {
        $this->_authenticated = true;
        $this->_roles = $roles;
        return $this;
    }

    /**
     * @return bool true if result reflect an successful authentication.
     */
    public function getAuthenticated()
    {
        return $this->_authenticated;
    }

    /**
     * @return string the login of the user trying to or already authenticate.
     */
    public function getLogin()
    {
        return $this->_login;
    }

    /**
     * @return array The roles of the authenticated user, null if the user is not authenticated.
     */
    public function getRoles()
    {
        return $this->_roles;
    }

    /**
     * @return array the current result serialized in an associative array
     */
    public function toArray() {
        return array('login' => $this->_login, 'authenticated' => $this->_authenticated, 'roles' => $this->_roles);
    }

    /**
     * Initialize the current result with the values in the session
     *
     * @return FPCAuthentication_Result $this
     */
    public function restore() {
        if (!isset($_SESSION[self::FPC_LOGIN_RESULT_KEY])) {
            $this->initialize();
        }
        else {
            $this->fromArray($_SESSION[self::FPC_LOGIN_RESULT_KEY]);
        }
        return $this;
    }

    /**
     * Initialize the current result with the given associative array.
     *
     * @param $data the associative array
     * @return FPCAuthentication_Result $this
     */
    public function fromArray($data) {
        $this->_login  = isset($data['login'])?$data['login']:null;
        $this->_authenticated = isset($data['authenticated'])?$data['authenticated']:false;
        $this->_roles = isset($data['roles'])?$data['roles']:array();
        return $this;
    }

    /**
     * Save the current result into the session.
     *
     * @return FPCAuthentication_Result $this
     */
    public function save() {
        FPCAuthentication_Result::startSession();
        $_SESSION[self::FPC_LOGIN_RESULT_KEY] = $this->toArray();
        return $this;
    }

    /**
     * Throw an FPCAuthentication_Exception initialized with the current result information
     *
     * @throws FPCAuthentication_Exception
     * @return void
     */
    public function throwException() {
        throw new FPCAuthentication_Exception("Invalid login and/or password", $this->_login);
    }


}
