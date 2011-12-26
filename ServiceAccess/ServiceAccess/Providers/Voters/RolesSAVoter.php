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
 * A voter that checks the roles of the user
 *
 * If the calling user has at least one of the allowedRoles (set at construction) then
 * the access is granted. Otherwise the access is denied.
 *
 * see : <ul>
 * <li>{@link FPC_ReflectionRolesSAVoterProvider}</li>
 * <li>{@link FPC_MethodRolesSAVoterProvider}</li>
 * </ul>
 *
 * @package FPC_AMFPHP_Plugins_ServiceAccess
 * @subpackage voter
 * @author Bastien Aracil
 */
class FPC_RolesSAVoter implements FPC_IServiceAccessVoter
{

    private $_rolesAllowed;

    /**
     * @param array $allowedRoles the list of allowed roles
     */
    public function __construct(array $allowedRoles)
    {
        $this->_rolesAllowed = array();
        foreach ($allowedRoles as $roles) {
            $this->_rolesAllowed[$roles] = true;
        };
    }

    /**
     * Check if the $user has at least one role in the list of allowed roles of this voter.
     *
     * @param FPC_IServiceAccessUser $user the current user information
     * @param $serviceObject the instance of the service
     * @param array $parameters the parameters that will be passed to the secured method
     * @return bool true if access is granted, false otherwise
     */
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
