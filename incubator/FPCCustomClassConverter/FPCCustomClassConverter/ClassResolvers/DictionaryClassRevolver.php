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
 * Date: 06/07/11
 *
 * Resolver that get class information from a dictionary (set at construction).
 * The key of the dictionary are explicitType values and values the corresponding classInfo.
 *
 * This resolver can be used for specific classes that cannot be resolved with other resolver.
 *
 */

require_once "AbstractDictionaryClassResolver.php";
require_once "IClassResolver.php";

class FPC_DictionaryClassRevolver extends FPC_AbstractDictionaryClassResolver implements FPC_IClassResolver
{

    private $dictionary;

    public function __construct($dictionary = null)
    {
        if (is_null($dictionary)) {
            $dictionary = array();
        }
        $this->dictionary = $dictionary;
    }

    /**
     * @param $expliciteType
     * @return FPC_ClassInfo for the given explicitType or void if none found
     */
    public function resolve($explicitType)
    {
        return $this->findInfo($explicitType, $this->dictionary);
    }


    public function addInfo(FPC_ClassInfo $info) {
        $this->dictionary[$info->getExplicitType()] = $info;
    }
}
