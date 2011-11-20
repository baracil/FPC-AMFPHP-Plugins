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
 * A {@link FPC_IServiceAccessVoterProvider} that delegates the work to another {@link FPC_IServiceAccessVoterProvider}
 *
 *
 * @package FPC_AMFPHP_Plugins_ServiceAccess
 * @subpackage provider
 * @author Bastien Aracil
 */
class FPC_ProxySAVoterProvider implements FPC_IServiceAccessVoterProvider {

    /**
     * @var FPC_IServiceAccessVoterProvider
     */
    private $_delegate;

    /**
     * @param FPC_IServiceAccessVoterProvider $provider the provider that will do all the work
     * @param bool $cached if true, the given provider is first wrapped in a {@link FPC_CachedSAVoterProvider}, otherwise
     * $provider is directly used.
     */
    public function __construct(FPC_IServiceAccessVoterProvider $provider, $cached = false) {
        if ($cached) {
            $provider = new FPC_CachedSAVoterProvider($provider);
        }
        $this->_delegate = $provider;
    }

    /**
     * @param $serviceObject
     * @param $serviceName
     * @param $methodName
     * @return FPC_IServiceAccessVoter the voter used to determine the access right to the given method of a service.
     */
    function getVoter($serviceObject, $serviceName, $methodName)
    {
        return $this->_delegate->getVoter($serviceObject, $serviceName, $methodName);
    }


}
