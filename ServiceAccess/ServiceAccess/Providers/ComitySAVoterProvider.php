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
 * Date: 29/07/11
 *
 * A provider that create a FPC_ComitySAVoters. By default the mode of the created FPC_ComitySAVoters
 * is FPC_ComitySAVoter::VETO_MODE but this can be set with the @comityMode annotation in the method documentation like this
 *
 * @comityMode ALL
 *
 */

class FPC_ComitySAVoterProvider extends FPC_AbstractReflectionSAVoterProvider implements FPC_IServiceAccessVoterProvider {


    private $_defaultMode;

    private $_providers;

    private $_grantIfNoVoter;

    public function __construct($providers= array(), $defaultMode = FPC_ComitySAVoters::VETO_MODE, $grantIfNoVoter = true) {
        parent::__construct("comityMode");
        $this->_defaultMode = $defaultMode;
        $this->_providers = $providers;
        $this->_grantIfNoVoter = $grantIfNoVoter;
    }

    /**
     * @param $serviceObject the object representing the service
     * @param $serviceName the name of the service
     * @param $methodName the name of the method
     * @param array $values list of values after the $tag in the method comment
     * @return FPC_IServiceAccessVoter a voter for this service/method
     */
    protected function handleValues($serviceObject, $serviceName, $methodName, array $values)
    {
        $nbVoters = 0;
        $mode = $this->getMode($values);
        $result = new FPC_ComitySAVoters($mode);
        foreach ($this->_providers as $provider) {
            $voter = $provider->getVoter($serviceObject, $serviceName, $methodName);
            if (!is_null($voter)) {
                $result->addVoter($voter);
                $nbVoters++;
            }
        }

        if ($nbVoters > 0) {
            return $result;
        }

        if ($this->_grantIfNoVoter) {
            return new FPC_AlloverSAVoter();
        }

        return new FPC_DenierSAVoter();
    }


    private function getMode(array $values)
    {
        if (empty($values)) {
            return $this->_defaultMode;
        }

        $mode = $values[0];
        switch ($mode) {
            case FPC_ComitySAVoters::MAJORITY_MODE :
            case FPC_ComitySAVoters::UNANIMITY_MODE :
            case FPC_ComitySAVoters::VETO_MODE :
                return $mode;
        }

        return $this->_defaultMode;
    }


}
