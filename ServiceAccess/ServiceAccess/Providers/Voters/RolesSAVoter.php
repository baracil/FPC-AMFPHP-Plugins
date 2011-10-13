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
    DISCLAIMED. IN NO EVENT SHALL SILEX LABS BE LIABLE FOR ANY
    DIRECT, INDIRECT, INCIDENTAL, SPECIAL, EXEMPLARY, OR CONSEQUENTIAL DAMAGES
    (INCLUDING, BUT NOT LIMITED TO, PROCUREMENT OF SUBSTITUTE GOODS OR SERVICES;
    LOSS OF USE, DATA, OR PROFITS; OR BUSINESS INTERRUPTION) HOWEVER CAUSED AND
    ON ANY THEORY OF LIABILITY, WHETHER IN CONTRACT, STRICT LIABILITY, OR TORT
    (INCLUDING NEGLIGENCE OR OTHERWISE) ARISING IN ANY WAY OUT OF THE USE OF THIS
    SOFTWARE, EVEN IF ADVISED OF THE POSSIBILITY OF SUCH DAMAGE.
*/

/**
 * User: Bastien Aracil
 * Date: 16/07/11
 *
 * A voter that checks if the calling user has at least one of the allowedRoles (set at construction)
 *
 */
require_once "IServiceAccessVoter.php";

class FPC_RolesSAVoter implements FPC_IServiceAccessVoter
{

    private $_rolesAllowed;

    public function __construct(array $allowedRoles)
    {
        $this->_rolesAllowed = array();
        foreach ($allowedRoles as $roles) {
            $this->_rolesAllowed[$roles] = true;
        };
    }

    function accessGranted(FPC_IServiceAccessUser $user, $serviceObject, array $parameters)
    {
        if (empty($this->_rolesAllowed)) {
            return false;
        }

        $grantedRoles = $user->getRoles();
        foreach ($grantedRoles as $granted) {
            if (isset($this->_rolesAllowed[$granted])) {
                return true;
            }
        }
        return false;
    }


}
