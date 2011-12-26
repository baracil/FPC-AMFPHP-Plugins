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
 *   @package FPC_AMFPHP_Plugins_FPCWordPress
 */

define("FPCWordPress_ROOTPATH",dirname(__FILE__) . DIRECTORY_SEPARATOR);

//model
require_once FPCWordPress_ROOTPATH."model/Category.php";
require_once FPCWordPress_ROOTPATH."model/User.php";
require_once FPCWordPress_ROOTPATH."model/Post.php";

//exception
require_once FPCWordPress_ROOTPATH."exception/UnknownUserException.php";
require_once FPCWordPress_ROOTPATH."exception/UnknownPostException.php";
require_once FPCWordPress_ROOTPATH."exception/UnknownCategoryException.php";

//dao
require_once FPCWordPress_ROOTPATH."dao/IWordPressDAO.php";
require_once FPCWordPress_ROOTPATH."dao/WordPressDAO.php";

//builder
require_once FPCWordPress_ROOTPATH."builder/AbstractBuilder.php";
require_once FPCWordPress_ROOTPATH."builder/UserBuilder.php";
require_once FPCWordPress_ROOTPATH."builder/PostBuilder.php";
require_once FPCWordPress_ROOTPATH."builder/CategoryBuilder.php";

require_once FPCWordPress_ROOTPATH."WordPressUtils.php";
require_once FPCWordPress_ROOTPATH."WordPressService.php";
?>