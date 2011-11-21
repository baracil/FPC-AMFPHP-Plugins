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
 *   @package FPC_AMFPHP_Plugins_FPCAuthentication
 */

/**
 *  Configuration data for {@link FPCAuthentication_LoginService}
 *
 *  @package FPC_AMFPHP_Plugins_FPCAuthentication
 *  @author Bastien Aracil
 */
class FPCAuthentication_LoginServiceConfig {

    /**
     * @var FPCAuthentication_ISecretProvider
     */
    private $_secretProvider;


    /**
     * @var FPCAuthentication_IRolesProvider
     */
    private $_rolesProvider;

    /**
     * @var FPCAuthentication_IBuilder
     */
    private $_builder;

    /**
     * @var FPCAuthentication_IChallengeSolver
     */
    private $_challengeSolver;

    /**
     * @var FPCAuthentication_IChallengeProvider
     */
    private $_challengeProvider;

    /**
     * @var FPCAuthentication_IRolesProvider
     */
    private $_defaultRolesProvider;

    /**
     * @var FPCAuthentication_IBuilder
     */
    private $_defaultBuilder;

    /**
     * @var FPCAuthentication_IChallengeSolver
     */
    private $_defaultChallengeSolver;

    /**
     * @var FPCAuthentication_IChallengeProvider
     */
    private $_defaultChallengeProvider;

    public function validate() {
        $this->_secretProvider = $this->validateProperty($this->_secretProvider, null, FPCAuthentication::SECRET_PROVIDER_KEY, "FPCAuthentication_ISecretProvider");
        $this->_rolesProvider = $this->validateProperty($this->_rolesProvider, $this->_defaultRolesProvider, FPCAuthentication::ROLES_PROVIDER_KEY, "FPCAuthentication_IRolesProvider");
        $this->_builder = $this->validateProperty($this->_builder, $this->_defaultBuilder, FPCAuthentication::BUILDER_KEY, "FPCAuthentication_IBuilder");
        $this->_challengeSolver = $this->validateProperty($this->_challengeSolver, $this->_defaultChallengeSolver, FPCAuthentication::CHALLENGE_SOLVER_KEY, "FPCAuthentication_IChallengeSolver");
        $this->_challengeProvider = $this->validateProperty($this->_challengeProvider, $this->_defaultChallengeProvider, FPCAuthentication::CHALLENGE_PROVIDER_KEY, "FPCAuthentication_IChallengeProvider");
    }

    private function validateProperty($propertyValue, $defaultValue, $propertyName, $propertyClass) {
        if (is_null($propertyValue)) {
            $propertyValue = $defaultValue;
        }

        if (is_null($propertyValue) || !($propertyValue instanceof $propertyClass)) {
            throw new Exception("Invalid configuration for plugin FPCAuthentication : $propertyName must be set and implement $propertyClass ");
        }

        return $propertyValue;
    }

    /**
     * Set the builder for {@link FPCAuthentication_LoginService}
     *
     * @param FPCAuthentication_IBuilder $builder
     */
    public function setBuilder($builder)
    {
        $this->_builder = $builder;
    }

    /**
     * The result builder for {@link FPCAuthentication_LoginService}
     *
     * @return FPCAuthentication_IBuilder
     */
    public function getBuilder()
    {
        return $this->_builder;
    }

    /**
     * Set the default builder for {@link FPCAuthentication_LoginService}
     *
     * @param FPCAuthentication_IBuilder $defaultBuilder
     */
    public function setDefaultBuilder($defaultBuilder)
    {
        $this->_defaultBuilder = $defaultBuilder;
    }

    /**
     * Set the default roles provider for {@link FPCAuthentication_LoginService}
     *
     * @param FPCAuthentication_IRolesProvider $defaultRolesProvider
     */
    public function setDefaultRolesProvider($defaultRolesProvider)
    {
        $this->_defaultRolesProvider = $defaultRolesProvider;
    }

    /**
     * Set the roles provider for {@link FPCAuthentication_LoginService}
     *
     * @param FPCAuthentication_IRolesProvider $rolesProvider
     */
    public function setRolesProvider($rolesProvider)
    {
        $this->_rolesProvider = $rolesProvider;
    }

    /**
     * The role provider for {@link FPCAuthentication_LoginService}
     *
     * @return FPCAuthentication_IRolesProvider
     */
    public function getRolesProvider()
    {
        return $this->_rolesProvider;
    }

    /**
     * The secret provider for {@link FPCAuthentication_LoginService}
     * 
     * @param FPCAuthentication_ISecretProvider $secretProvider
     */
    public function setSecretProvider($secretProvider)
    {
        $this->_secretProvider = $secretProvider;
    }

    /**
     * The secret provider for {@link FPCAuthentication_LoginService}
     *
     * @return FPCAuthentication_ISecretProvider
     */
    public function getSecretProvider()
    {
        return $this->_secretProvider;
    }

    /**
     * Set the default challenge solver for {@link FPCAuthentication_LoginService}
     *
     * @param FPCAuthentication_IChallengeSolver $defaultChallengeSolver
     */
    public function setDefaultChallengeSolver($defaultChallengeSolver)
    {
        $this->_defaultChallengeSolver = $defaultChallengeSolver;
    }

    /**
     * The default challenge solver for {@link FPCAuthentication_LoginService}
     *
     * @return FPCAuthentication_IChallengeSolver
     */
    public function getDefaultChallengeSolver()
    {
        return $this->_defaultChallengeSolver;
    }

    /**
     * Set the challenge solver for {@link FPCAuthentication_LoginService}
     *
     * @param FPCAuthentication_IChallengeSolver $challengeSolver
     */
    public function setChallengeSolver($challengeSolver)
    {
        $this->_challengeSolver = $challengeSolver;
    }

    /**
     * The challenge solver for {@link FPCAuthentication_LoginService}
     *
     * @return FPCAuthentication_IChallengeSolver
     */
    public function getChallengeSolver()
    {
        return $this->_challengeSolver;
    }

    /**
     * Set the challenge provider for {@link FPCAuthentication_LoginService}
     *
     * @param FPCAuthentication_IChallengeProvider $challengeProvider
     */
    public function setChallengeProvider($challengeProvider)
    {
        $this->_challengeProvider = $challengeProvider;
    }

    /**
     * The challenge provider for {@link FPCAuthentication_LoginService}
     *
     * @return FPCAuthentication_IChallengeProvider
     */
    public function getChallengeProvider()
    {
        return $this->_challengeProvider;
    }

    /**
     * Set the default challenge provider for {@link FPCAuthentication_LoginService}
     *
     * @param FPCAuthentication_IChallengeProvider $defaultChallengeProvider
     */
    public function setDefaultChallengeProvider($defaultChallengeProvider)
    {
        $this->_defaultChallengeProvider = $defaultChallengeProvider;
    }

    /**
     * The default challenge provider for {@link FPCAuthentication_LoginService}
     *
     * @return FPCAuthentication_IChallengeProvider
     */
    public function getDefaultChallengeProvider()
    {
        return $this->_defaultChallengeProvider;
    }


}
