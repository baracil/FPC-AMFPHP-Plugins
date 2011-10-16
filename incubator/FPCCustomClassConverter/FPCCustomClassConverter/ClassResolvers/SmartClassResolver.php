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
 * Date: 16/10/11
 */
 
class FPC_SmartClassResolver implements FPC_IClassResolver {

    /**
     * @var \FPC_IClassResolver
     */
    private $_resolver;

    /**
     * @var FPC_ChainedClassResolver
     */
    private $_chainedResolver;

    /**
     * @var FPC_DictionaryClassRevolver
     */
    private $_specificResolver;

    /**
     * @var FPC_PackageClassResolver
     */
    private $_packageResolver;

    /**
     * @var FPC_DefaultClassResolver;
     */
    private $_defaultResolver;

    public function __construct($cached = true) {
        $this->_chainedResolver = new FPC_ChainedClassResolver();
        if ($cached) {
            $this->_resolver = new FPC_CachedClassResolver($this->_chainedResolver);
        }
        else {
            $this->_resolver = $this->_chainedResolver;
        }
    }

    /**
     * @param FPC_ClassInfo $info
     * @return FPC_SmartClassResolver
     */
    public function addSpecificInfo(FPC_ClassInfo $info) {
        if (is_null($this->_specificResolver)) {
            $this->_specificResolver = new FPC_DictionaryClassRevolver();
            $this->_chainedResolver->addClassResolver($this->_specificResolver);
        }

        $this->_specificResolver->addInfo($info);
        return $this;
    }

    /**
     * @param $packageRootPath
     * @return FPC_SmartClassResolver
     */
    public function addPackageRootPath($packageRootPath) {
        if (is_null($this->_packageResolver)) {
            $this->_packageResolver = new FPC_PackageClassResolver();
            $this->_chainedResolver->addClassResolver($this->_packageResolver);
        }

        $this->_packageResolver->addPackageRootPath($packageRootPath);
        return $this;
    }

    /**
     * @param $defaultPath
     * @return FPC_SmartClassResolver
     */
    public function addDefaultRootPath($defaultPath) {
        if (is_null($this->_defaultResolver)) {
            $this->_defaultResolver = new FPC_DefaultClassResolver();
            $this->_chainedResolver->addClassResolver($this->_defaultResolver);
        }
        
        $this->_defaultResolver->addCustomClassFolderPath($defaultPath);
        return $this;
    }

    /**
     * @param $explicitType
     * @return FPC_ClassInfo for the given explicitType or void if none found
     */
    public function resolve($explicitType)
    {
        return $this->_resolver->resolve($explicitType);
    }


}
