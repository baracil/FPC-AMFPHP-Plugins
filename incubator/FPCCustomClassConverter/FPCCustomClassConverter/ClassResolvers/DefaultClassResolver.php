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
 * Date: 06/07/
 *
 * Default class resolver that searches the classes in a directory.
 * The explicitType is used as the name of the class. This resolver simulates the default behaviour of
 * AMFPHP (that's why the Default prefix)
 */

class FPC_DefaultClassResolver implements FPC_IClassResolver
{

    private $customClassFolderPaths;

    public function __construct($customClassFolderPaths = null)
    {
        if (is_null($customClassFolderPaths)) {
            $customClassFolderPaths = array();
        }
        $this->customClassFolderPaths = $customClassFolderPaths;
    }

    public function addCustomClassFolderPath($path) {
        $this->customClassFolderPaths[] = $path;
    }

    /**
     * @param $expliciteType
     * @return FPC_ClassInfo for the given explicitType or void if none found
     */
    public function resolve($explicitType)
    {
        $customClassName = $explicitType;
        foreach ($this->customClassFolderPaths as $folderPath) {
            $customClassPath = $folderPath . "/" . $customClassName . ".php";
            if (file_exists($customClassPath)) {
                return new FPC_ClassInfo($explicitType, "", $customClassName, $customClassPath);
            }
        }
        return null;
    }

}

?>