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
 * Date: 29/07/11
 *
 * A voter that uses a method to get the access status of the service.
 * This voter needs the following parameters (set at construction):
 *
 * $serviceName : the name of the service the user is accessing to
 * $methodName : the name of the method the user is accessing to
 * $checkMethodName : the name of the method that will be used to check access to the service
 *
 * When the method accessGranted is called, this voter call the $checkMethodName of the serviceObject with the given parameters :
 *
 * $serviceName (from this voter)
 * $methodName (from this voter)
 * $user (from accessGranted method parameters)
 * $parameters (from accessGranted method parameters)
 *
 * the result of the method is used as access result.
 * If the $checkMethod does not exists, the access is denied
 *
 */
require_once "IServiceAccessVoter.php";

class FPC_MethodSAVoter implements FPC_IServiceAccessVoter {

    private $_serviceName;

    private $_methodName;

    private $_checkMethodName;

    private $_grantedIfNoMethod;

    /**
     * @param String $serviceName the name of the service
     * @param String $methodName the name of the secured method
     * @param String $checkMethodName the name of the method that will be called to determine the access right
     * @param bool $grantedIfNoMethod the value return if the checkMethod is not found (false by default)
     */
    public function __construct($serviceName, $methodName, $checkMethodName, $grantedIfNoMethod = false) {
        $this->_checkMethodName = $checkMethodName;
        $this->_methodName = $methodName;
        $this->_serviceName = $serviceName;
        $this->_grantedIfNoMethod = $grantedIfNoMethod;
    }

    function accessGranted(FPC_IServiceAccessUser $user, $serviceObject, array $parameters)
    {
        $result = $this->_grantedIfNoMethod;
        if (method_exists($serviceObject, $this->_checkMethodName)) {
            $result = call_user_func(array($serviceObject, $this->_checkMethodName), $this->_serviceName, $this->_methodName, $parameters, $user);
        }
        return (boolean)$result;
    }


}
