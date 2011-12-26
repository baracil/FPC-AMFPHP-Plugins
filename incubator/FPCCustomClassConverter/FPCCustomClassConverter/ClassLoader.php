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
 *  This file is part of amfPHP
 *
 * LICENSE
 *
 * This source file is subject to the license that is bundled
 * with this package in the file license.txt.
 * @package Amfphp_Core
 */

define("FPCCustomClassConverter_ROOTPATH",dirname(__FILE__).DIRECTORY_SEPARATOR);


//ClassResolvers
require_once FPCCustomClassConverter_ROOTPATH."ClassResolvers/IClassResolver.php";
require_once FPCCustomClassConverter_ROOTPATH."ClassResolvers/ClassInfo.php";
require_once FPCCustomClassConverter_ROOTPATH."ClassResolvers/AbstractDictionaryClassResolver.php";
require_once FPCCustomClassConverter_ROOTPATH."ClassResolvers/CachedClassResolver.php";
require_once FPCCustomClassConverter_ROOTPATH."ClassResolvers/ChainedClassResolver.php";
require_once FPCCustomClassConverter_ROOTPATH."ClassResolvers/DefaultClassResolver.php";
require_once FPCCustomClassConverter_ROOTPATH."ClassResolvers/DictionaryClassRevolver.php";
require_once FPCCustomClassConverter_ROOTPATH."ClassResolvers/PackagedClassResolver.php";
require_once FPCCustomClassConverter_ROOTPATH."ClassResolvers/SmartClassResolver.php";

//PropertiesManager
require_once FPCCustomClassConverter_ROOTPATH."PropertiesManager/IPropertyManager.php";
require_once FPCCustomClassConverter_ROOTPATH."PropertiesManager/IPropertyManagersExtractor.php";
require_once FPCCustomClassConverter_ROOTPATH."PropertiesManager/IPropertiesManager.php";
require_once FPCCustomClassConverter_ROOTPATH."PropertiesManager/ISpecificPropertiesManager.php";

require_once FPCCustomClassConverter_ROOTPATH."PropertiesManager/PropertyManager/PublicPropertyManager.php";
require_once FPCCustomClassConverter_ROOTPATH."PropertiesManager/PropertyManager/GSPropertyManager.php";
require_once FPCCustomClassConverter_ROOTPATH."PropertiesManager/PropertyManager/PropertyManagersExtractor.php";
require_once FPCCustomClassConverter_ROOTPATH."PropertiesManager/PropertyManager/CachedPropertyManagerExtractor.php";

require_once FPCCustomClassConverter_ROOTPATH."PropertiesManager/SpecificPropertiesManager/ExceptionManager.php";
require_once FPCCustomClassConverter_ROOTPATH."PropertiesManager/SpecificPropertiesManager/PDOExceptionManager.php";

require_once FPCCustomClassConverter_ROOTPATH."PropertiesManager/PublicPropertiesManager.php";
require_once FPCCustomClassConverter_ROOTPATH."PropertiesManager/ReflectionPropertiesManager.php";
require_once FPCCustomClassConverter_ROOTPATH."PropertiesManager/ChainedPropertiesManager.php";
require_once FPCCustomClassConverter_ROOTPATH."PropertiesManager/SpecificPropertiesManagers.php";
require_once FPCCustomClassConverter_ROOTPATH."PropertiesManager/SmartPropertiesManager.php";

require_once FPCCustomClassConverter_ROOTPATH."CustomSerializer.php";


?>
