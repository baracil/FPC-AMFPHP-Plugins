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
 * Date: 06/07/11
 *
 * A ClassResolver that use the explicitType to find the file path of a class. The file path is relative to a root path given to
 * this resolver at construction.
 *
 * For instance, with a root path '/rootPath' (set with new FPC_PackageClassResolver('/rootPath')), if the explicitType is
 * "net.femtoparsec.myclasses.CustomClass" the resolver will search the directory '/rootPath/net/femtoparsec/myclasses/' for
 * the class 'CustomClass'.
 *
 */

require_once "IClassResolver.php";

class FPC_PackageClassResolver implements FPC_IClassResolver
{

    private $packageRootPaths;

    public function __construct($packageRootPaths = null)
    {
        if (is_null($packageRootPaths)) {
            $packageRootPaths = array();
        }
        $this->packageRootPaths = $packageRootPaths;
    }

    public function addPackageRootPath($packageRootPath) {
        $this->packageRootPaths[] = $packageRootPath;
    }

    /**
     * @param $explicitType
     * @return FPC_ClassInfo for the given explicitType or null if none found
     */
    public function resolve($explicitType)
    {
        if (empty($this->packageRootPaths)) {
            return null;
        }

        $customClassInfo = $this->extractPackageAndClassName($explicitType);

        $packageName = $customClassInfo[0];
        $packagePath = $customClassInfo[1];
        $customClassName = $customClassInfo[2];

        foreach ($this->packageRootPaths as $folderPath) {
            $customClassPath = $folderPath . "/" . $packagePath . $customClassName . ".php";
            if (file_exists($customClassPath)) {
                return new FPC_ClassInfo($explicitType, $packageName, $customClassName, $customClassPath);
            }
        }

        return null;
    }

    private function extractPackageAndClassName($explicitType)
    {
        $tokens = explode(".", $explicitType);
        $length = count($tokens);
        if ($length == 1) {
            return array("", "", $explicitType);
        }

        $customClassName = $tokens[$length - 1];
        $packageName = "";
        $packagePath = "";
        for ($idx = 0; $idx < ($length - 1); $idx++) {
            $packagePath .= $tokens[$idx] . DIRECTORY_SEPARATOR;
            $packageName .= $tokens[$idx] . ".";
        }

        $packageName = substr($packageName, 0, -1);

        return array($packageName, $packagePath, $customClassName);
    }

}
