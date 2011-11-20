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

/**
 * The ServiceAccess plugin exception.
 *
 * This exception is thrown when the user tries to access a service he is not allowed to access
 *
 * @package FPC_AMFPHP_Plugins_ServiceAccess
 * @author Bastien Aracil
 */
class ServiceAccessException extends Exception
{

    var $_explicitType = "plugins.amfphp.ServiceAccess.ServiceAccessException";

    /**
     * @var string the name of the service the user tried to access
     */
    public $serviceName;

    /**
     * @var string the name of the method the user tried to access
     */
    public $methodName;

    /**
     * @param String $serviceName the name of the service the user tried to access
     * @param String $methodName the name of the method the user tried to access
     */
    public function __construct($serviceName = null, $methodName = null)
    {
        parent::__construct("Access denied");
        $this->methodName = $methodName;
        $this->serviceName = $serviceName;
    }

    /**
     * @param string $methodName the name of the method the user tried to access
     */
    public function setMethodName($methodName)
    {
        $this->methodName = $methodName;
    }

    /**
     * @return string the name of the method the user tried to access
     */
    public function getMethodName()
    {
        return $this->methodName;
    }

    /**
     * @param string $serviceName the name of the service the user tried to access
     */
    public function setServiceName($serviceName)
    {
        $this->serviceName = $serviceName;
    }

    /**
     * @return string the name of the service the user tried to access
     */
    public function getServiceName()
    {
        return $this->serviceName;
    }

}
