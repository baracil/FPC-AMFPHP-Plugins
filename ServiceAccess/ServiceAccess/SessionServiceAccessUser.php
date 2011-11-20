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
 */

require_once "IServiceAccessUser.php";

/**
 * User: Bastien Aracil
 * Date: 29/07/11
 *
 * An implementation of {@link FPC_IServiceAccessUser} that uses values saved in the Session.
 *
 * This user provider retreives the login and the roles of the currently authenticated user
 * from the session by using to key set at construction. The login and the roles must have been set by another
 * process (this plugin just read them).
 *
 * @package FPC_AMFPHP_Plugins_ServiceAccess
 * @author Bastien Aracil
 */
class FPC_SessionServiceAccessUser implements FPC_IServiceAccessUser {

    /**
     * @var string
     */
    private $_sessionLoginKey;

    /**
     * @var string
     */
    private $_sessionRolesKey;

    /**
     * @param string $sessionLoginKey the session key to get the login of the currently authenticated user
     * @param string $sessionRolesKey the session key to get the roles of the currently authenticated user
     */
    public function __construct($sessionLoginKey, $sessionRolesKey) {
        $this->_sessionLoginKey = $sessionLoginKey;
        $this->_sessionRolesKey = $sessionRolesKey;
    }

    /**
     * @return string the login of the user
     */
    function getLogin()
    {
        if (session_id() == "") {
            session_start();
        }
        return $_SESSION[$this->_sessionLoginKey];
    }

    /**
     * @return array of strings of the roles given to the user
     */
    function getRoles()
    {
        if (session_id() == "") {
            session_start();
        }
        return $_SESSION[$this->_sessionRolesKey];
    }


}
