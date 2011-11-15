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
 * Date: 16/07/11
 *
 * Since a voter is always the same for a method, the result of a provider can be cached.
 * This provider does exactly that. The voters are cached in the SESSION under the key
 * FPC_CachedSAVoterProvider::CACHED_SA_VOTER_SESSION_KEY
 *
 */

class FPC_CachedSAVoterProvider implements FPC_IServiceAccessVoterProvider
{

    const CACHED_SA_VOTER_SESSION_KEY = "CACHED_SA_VOTER_SESSION_KEY";

    /**
     * @var FPC_IServiceAccessVoterProvider;
     */
    private $_delegate;

    public function __construct(FPC_IServiceAccessVoterProvider $delegate)
    {
        if (is_null($delegate)) {
            throw new Amfphp_Core_Exception("delegate IAccessVoterProvider cannot be null for CachedSAVoter");
        }
        $this->_delegate = $delegate;
    }

    /**
     * @param $serviceObject
     * @param $serviceName
     * @param $methodName
     * @return FPC_IServiceAccessVoter
     */
    function getVoter($serviceObject, $serviceName, $methodName)
    {
        if (session_id() == "") {
            session_start();
        }

        $key = $this->getKey($serviceName, $methodName);

        $voter = $this->getCachedVoter($key);

        if (is_null($voter)) {
            $voter = $this->_delegate->getVoter($serviceObject, $serviceName, $methodName);
            if (is_null($voter)) {
                $voter = new FPC_AlloverSAVoter();
            }
            $this->putVoterInCache($key, $voter);
        }

        return $voter;
    }

    private function getKey($serviceName, $methodName)
    {
        return "$serviceName:$methodName";
    }

    /**
     * @param $key
     * @return FPC_IServiceAccessVoter
     */
    private function getCachedVoter($key)
    {
        if (!isset($_SESSION[self::CACHED_SA_VOTER_SESSION_KEY])) {
            $_SESSION[self::CACHED_SA_VOTER_SESSION_KEY] = array();
        }

        $cache = &$_SESSION[self::CACHED_SA_VOTER_SESSION_KEY];
        if (isset($cache[$key])) {
            return $cache[$key];
        }

        return null;
    }

    private function putVoterInCache($key, FPC_IServiceAccessVoter $voter)
    {
        $_SESSION[self::CACHED_SA_VOTER_SESSION_KEY][$key] = $voter;
    }
}
