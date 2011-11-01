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
 * Date: 16/07/11
 *
 * A voter that uses several voters and a voting mode to determine if the access to the service is granted or denied.
 *
 * There are three mode :
 *
 * ComitySAVoters::MAJORITY_MODE : access is granted if 50% or more pf the registered voters grant the access
 * ComitySAVoters::UNANIMITY_MODE : access is granted if all registered voters grant the access
 * ComitySAVoters::VETO_MODE : access is granted if at least one registered voter grants the access
 *
 * The mode is set at construction and cannot be change after that.
 * A voter can be added to the comity with the addVoter(IServiceAccessVoter) method. A voter cannot be removed.
 *
 */
require_once "IServiceAccessVoter.php";

class FPC_ComitySAVoters implements FPC_IServiceAccessVoter
{

    /**
     * 50% or more voters must grant access for the comity to grant it
     */
    const MAJORITY_MODE = "MAJORITY";

    /**
     * all voters must grant access for the comity to grant it
     */
    const UNANIMITY_MODE = "UNANIMITY";

    /**
     * at least one voter must grant access for the comity to grant it
     */
    const VETO_MODE = "VETO";

    /**
     * @var array of IServiceAccessVoter
     */
    private $_voters;

    /**
     * @var string comity voting mode
     */
    private $_mode;

    public function __construct($mode = FPC_ComitySAVoters::VETO_MODE)
    {
        $this->_voters = array();
        $this->_mode = $mode;
    }

    public function addVoter(FPC_IServiceAccessVoter $voter)
    {
        $this->_voters[] = $voter;
    }

    function accessGranted(FPC_IServiceAccessUser $user, $serviceObject, array $parameters)
    {
        if ($this->_mode == FPC_ComitySAVoters::VETO_MODE) {
            return $this->getVetoAccessGranted($user, $serviceObject, $parameters);
        }
        else if ($this->_mode == FPC_ComitySAVoters::UNANIMITY_MODE) {
            return $this->getUnanimityAccessGranted($user, $serviceObject, $parameters);
        }
        else if ($this->_mode == FPC_ComitySAVoters::MAJORITY_MODE) {
            return $this->getMajorityAccessGranted($user, $serviceObject, $parameters);
        }
        return false;
    }

    private function getVetoAccessGranted(FPC_IServiceAccessUser $user, $serviceObject, array $parameters)
    {
        foreach ($this->_voters as $voter) {
            if ($voter->accessGranted($user, $serviceObject, $parameters)) {
                return true;
            }
        }
        return false;
    }

    private function getUnanimityAccessGranted(FPC_IServiceAccessUser $user, $serviceObject, array $parameters)
    {
        foreach ($this->_voters as $voter) {
            if (!$voter->accessGranted($user, $serviceObject, $parameters)) {
                return false;
            }
        }
        return true;
    }

    private function getMajorityAccessGranted(FPC_IServiceAccessUser $user, $serviceObject, array $parameters)
    {
        $nbVoters = 0;
        $nbGranted = 0;

        foreach ($this->_voters as $voter) {
            $nbVoters++;
            if ($voter->accessGranted($user, $serviceObject, $parameters)) {
                $nbGranted++;
            }
        }

        return ($nbGranted * 2) >= $nbVoters;
    }


}
