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
 * Date: 15/11/11
 * Time: 23:11
 */
package net.femtoparsec.fpcauthentication.handler {
import mx.rpc.AbstractOperation;
import mx.rpc.events.FaultEvent;

import net.femtoparsec.fpcauthentication.FPCAuthentication;
import net.femtoparsec.fpcauthentication.FPCAuthenticationException;
import net.femtoparsec.fpcauthentication.FPCAuthenticationToken;
import net.femtoparsec.fpcauthentication.IChallengeProvider;
import net.femtoparsec.fpcauthentication.IChallengeSolver;
import net.femtoparsec.fpcauthentication.IFPCAuthenticationHandler;

public class AbstractAuthenticationHandler implements IFPCAuthenticationHandler {

    protected var _fpcAuthentication:FPCAuthentication;

    public function AbstractAuthenticationHandler(fpcAuthentication:FPCAuthentication) {
        this._fpcAuthentication = fpcAuthentication;
    }

    protected function getOperation(operationName:String):AbstractOperation {
        return _fpcAuthentication.getOperation(operationName);
    }

    protected function get challengeProvider():IChallengeProvider {
        return _fpcAuthentication.challengeProvider;
    }

    protected function get challengeSolver():IChallengeSolver {
        return _fpcAuthentication.challengeSolver;
    }

    protected function getFPCAuthenticationException(fault:FaultEvent):FPCAuthenticationException {
        var result:FPCAuthenticationException = fault.fault.rootCause as FPCAuthenticationException;
        if (result == null) {
            this.handleFault(fault);
        }
        return result;
    }

    protected function handleFault(fault:FaultEvent):void {
        //simply throw the error
        throw fault.fault;
    }


    public function handle(token:FPCAuthenticationToken):void {
        //must be overriden
    }
}
}
