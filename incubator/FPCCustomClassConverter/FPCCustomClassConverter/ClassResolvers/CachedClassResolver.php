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
 * Date: 18/07/11
 *
 * This resolver caches the result of an inner resolver (set at construction). Then, on a next call with
 * the same explicitType the classInfo is obtained from the Session instead of the inner resolver
 * which can be time consuming.
 *
 * The result is cached in the session under a key set at construction (default 'CLASS_RESOLVER_CACHE_KEY').
 *
 */

require_once "IClassResolver.php";

class FPC_CachedClassResolver extends FPC_AbstractDictionaryClassResolver implements FPC_IClassResolver
{

    const DEFAULT_SESSION_CACHE_KEY = "CLASS_RESOLVER_CACHE_KEY";

    private $sessionCacheKey;

    private $classResolver;

    /**
     * @throws Amfphp_Core_Exception
     * @param FPC_IClassResolver $classResolver the inner resolver used if the class info cannot be found in the cache
     * @param string $key the session key used to save the inner resolver result
     */
    public function __construct(FPC_IClassResolver $classResolver, $key = self::DEFAULT_SESSION_CACHE_KEY)
    {
        if (is_null($classResolver)) {
            throw new Amfphp_Core_Exception("Null classResolver provided for SessionCachedClassResolver");
        }
        $this->classResolver = $classResolver;
        $this->sessionCacheKey = $key;
    }

    /**
     * @param $explicitType
     * @return FPC_ClassInfo for the given explicitType or void if none found
     */
    public function resolve($explicitType)
    {
        if (session_id() == "") {
            session_start();
        }

        $key = $this->sessionCacheKey;

        if (!isset($_SESSION[$key])) {
            $_SESSION[$key] = array();
        }

        return $this->getOrSetInfo($explicitType, $_SESSION[$key], $this->classResolver);

    }

    private function getOrSetInfo($explicitType, &$cache, FPC_IClassResolver $delegate) {
        $classInfo = $this->findInfo($explicitType, $cache);

        if (is_null($classInfo)) {
            $classInfo = $delegate->resolve($explicitType);
            if (!is_null($classInfo)) {
                $cache[$explicitType] = $classInfo;
            }
        }

        return $classInfo;
    }


}
