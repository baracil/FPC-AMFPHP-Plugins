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
 * A provider that returns a {@link FPC_RolesSAVoter}
 *
 * The provider uses the values of the annotation @rolesAllowed to initialize the voter.
 * For instance, with :
 *
 * <code>
 *   /**
 *    * @rolesAllowed ADMIN, MODERATOR
 *    {@*}
 *    public function securedMethod() {...}
 * </code>
 *
 * The method 'securedMethod' can only be called by user with at least the role ADMIN or MODERATOR.
 *
 * If the documentation contains several @rolesAllowed annotations, the values of each occurences are merged. So
 *
 * <code>
 *  /**
 *   * @rolesAllowed ADMIN, MODERATOR
 *   * @rolesAllowed USER
 *   {@*}
 *   public function securedMethod() {...}
 * </code>
 *
 * is exactly the same than
 * <code>
 *  /**
 *   * @rolesAllowed ADMIN, MODERATOR, USER
 *   {@*}
 *   public function securedMethod() {...}
 * </code>
 *
 * @package FPC_AMFPHP_Plugins_ServiceAccess
 * @subpackage provider
 * @author Bastien Aracil
 */
class FPC_ReflectionRolesSAVoterProvider extends FPC_AbstractReflectionSAVoterProvider implements FPC_IServiceAccessVoterProvider
{

    const DEFAULT_ROLES_TAG = "rolesAllowed";

    public function __construct($_rolesTag = self::DEFAULT_ROLES_TAG)
    {
        parent::__construct($_rolesTag, true);
    }

    /**
     * @param $serviceObject the object representing the service
     * @param $serviceName the name of the service
     * @param $methodName the name of the method
     * @param array $values list of values after the $tag in the method comment
     * @return FPC_IServiceAccessVoter a voter for this service/method
     */
    protected function handleValues($serviceObject, $serviceName, $methodName, array $roles)
    {
        if (count($roles) > 0) {
            return new FPC_RolesSAVoter($roles);
        }

        return new FPC_DenierSAVoter();
    }

}
