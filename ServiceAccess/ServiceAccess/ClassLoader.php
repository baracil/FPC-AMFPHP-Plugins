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
 * User: Bastien Aracil
 * Date: 16/07/11
 */

define("ServiceAccess_ROOTPATH",dirname(__FILE__) . DIRECTORY_SEPARATOR);

//providers/voters
require_once ServiceAccess_ROOTPATH."Providers/Voters/IServiceAccessVoter.php";
require_once ServiceAccess_ROOTPATH."Providers/Voters/AlloverSAVoter.php";
require_once ServiceAccess_ROOTPATH."Providers/Voters/ComitySAVoters.php";
require_once ServiceAccess_ROOTPATH."Providers/Voters/DenierSAVoter.php";
require_once ServiceAccess_ROOTPATH."Providers/Voters/MethodSAVoter.php";
require_once ServiceAccess_ROOTPATH."Providers/Voters/CurrentUserLoginSAVoter.php";
require_once ServiceAccess_ROOTPATH."Providers/Voters/RolesSAVoter.php";

//Providers
require_once ServiceAccess_ROOTPATH."Providers/IServiceAccessVoterProvider.php";
require_once ServiceAccess_ROOTPATH."Providers/AbstractReflectionSAVoterProvider.php";
require_once ServiceAccess_ROOTPATH."Providers/CachedSAVoterProvider.php";
require_once ServiceAccess_ROOTPATH."Providers/ComitySAVoterProvider.php";
require_once ServiceAccess_ROOTPATH."Providers/MethodRolesSAVoterProvider.php";
require_once ServiceAccess_ROOTPATH."Providers/ReflectionRolesSAVoterProvider.php";
require_once ServiceAccess_ROOTPATH."Providers/ReflectionMethodSAVoterProvider.php";
require_once ServiceAccess_ROOTPATH."Providers/CurrentUserLoginSAVoterProvider.php";
require_once ServiceAccess_ROOTPATH."Providers/ProxySAVoterProvider.php";


require_once ServiceAccess_ROOTPATH."IServiceAccessUser.php";
require_once ServiceAccess_ROOTPATH."ServiceAccessException.php";
require_once ServiceAccess_ROOTPATH."ServiceAccess.php";
require_once ServiceAccess_ROOTPATH."SessionServiceAccessUser.php";
require_once ServiceAccess_ROOTPATH."DefaultVoterProvider.php";
require_once ServiceAccess_ROOTPATH."FPCAuthenticationUser.php";

?>