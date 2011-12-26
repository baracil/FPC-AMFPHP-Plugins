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
 *   @package FPC_AMFPHP_Plugins_ServiceAccess
 *   @subpackage voter
 */

/**
 *
 * A voter that grants access if a given property of a the parameters is equals to the current user login.
 *
 * When called, a voter gets the $parameters of the secured method. This voter check
 * if a given property of one of these parameters is equal to the login of the current
 * authenticated user. To define which property of which parameter should be used for
 * the check, two parameters are used :
 *
 * $index : the index of the parameter in the parameters array where the login information is.
 * $loginProperty : the property name of the selected parameter that holds the login.
 *
 * By default, $index = 0 and $loginProperty = "login"
 *
 * if $loginProperty is note define, then $parameters[$index] is directly used.
 *
 * @package FPC_AMFPHP_Plugins_ServiceAccess
 * @subpackage voter
 * @author Bastien Aracil
 */
class FPC_CurrentUserLoginSAVoter implements FPC_IServiceAccessVoter {

    private $_index;

    private $_loginProperty;

    /**
     * @param int $index the index of the parameters that is used for the check
     * @param string $loginProperty the property of the selected parameter that holds the login
     */
    public function __construct($index = 0, $loginProperty = "login") {
        $this->_index = $index;
        $this->_loginProperty = $loginProperty;
    }

    /**
     * This voter extracts from the parameters a login value and compare it to the login
     * of the authenticated user to grant or deny access to the method. If $loginProperty (see contructor)
     * is null then $parameters[$index] is used, else $parameters[$index]->${loginProperty} is used.
     *
     *
     * @param FPC_IServiceAccessUser $user the current user information
     * @param $serviceObject the instance of the service
     * @param array $parameters the parameters that will be passed to the secured method
     * @return bool true if the extracted login is equal to the login of the authenticated user
     */
    function accessGranted(FPC_IServiceAccessUser $user, $serviceObject, array $parameters)
    {
        $cnt = count($parameters);

        if ( $this->_index >= $cnt) {
            return false;
        }

        $parameter = $parameters[$this->_index];

        if (is_null($this->_loginProperty)) {
            $login = $parameter;
        }
        else if (!isset($parameter->{$this->_loginProperty})) {
            return false;
        }
        else {
            $login = $parameter->{$this->_loginProperty};
        }

        return $login === $user->getLogin();
    }


}
