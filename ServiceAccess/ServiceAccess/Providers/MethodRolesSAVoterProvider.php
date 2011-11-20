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
 *   @subpackage provider
 */

/**
 * A provider that returns a {@link FPC_RolesSAVoter}
 *
 * The returned voter is initialized with the roles returned by a
 * given method (by default 'getMethodRoles'). The method name can be set at construction.
 *
 * The signature of this method should be
 *
 * <code>
 *  public function getMethodRoles($methodName) {
 *   //do some work
 *   return AN_ARRAY_OF_STRING_LISTING_THE_ROLES_ALLOWED_FOR_THE_METHOD_METHODNAME;
 * }
 * </code>
 *
 * @package FPC_AMFPHP_Plugins_ServiceAccess
 * @subpackage provider
 * @author Bastien Aracil
 */
class FPC_MethodRolesSAVoterProvider implements FPC_IServiceAccessVoterProvider
{

    /**
     * the default name of the method used to get the allowed roles
     */
    const METHOD_GET_METHOD_ROLES = "getMethodRoles";

    private $_methodRoleProvider;

    /**
     * @param string $methodRoleProvider The method that will provides the allowed roles of a method.
     */
    public function __construct($methodRoleProvider = self::METHOD_GET_METHOD_ROLES) {
        $this->_methodRoleProvider = $methodRoleProvider;
    }

    /**
     * @param $serviceObject
     * @param $serviceName
     * @param $methodName
     * @return FPC_IServiceAccessVoter
     */
    function getVoter($serviceObject, $serviceName, $methodName)
    {
        if (!method_exists($serviceObject, $this->_methodRoleProvider)) {
            return new FPC_AlloverSAVoter();
        }

        //the service object has a "getMethodRoles" method. role checking is necessary if the returned value is not null
        $allowedRoles = call_user_func(array($serviceObject, self::METHOD_GET_METHOD_ROLES), $methodName);
        return new FPC_RolesSAVoter($allowedRoles);
    }


}
