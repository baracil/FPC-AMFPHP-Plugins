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
 * Date: 13/11/11
 * Time: 04:35
 */
package net.femtoparsec.fpcauthentication.handler {
import flash.utils.ByteArray;

import mx.rpc.AbstractOperation;
import mx.rpc.AsyncResponder;
import mx.rpc.AsyncToken;
import mx.rpc.events.FaultEvent;
import mx.rpc.events.ResultEvent;

import net.femtoparsec.fpcauthentication.*;

public class DirectAuthenticationHandler extends AbstractAuthenticationHandler {

    public function DirectAuthenticationHandler(fpcAuthentication:FPCAuthentication) {
        super(fpcAuthentication);
    }

    override public function handle(token:FPCAuthenticationToken):void {
        var operation:AbstractOperation = this.getOperation("authenticate");
        var asyncToken:AsyncToken = operation.send(token.login, token.secret);
        asyncToken.addResponder(new AsyncResponder(onResult, onFault, token));
    }

    private function onResult(result:ResultEvent, token:FPCAuthenticationToken):void {
        var commonSecret:ByteArray = new ByteArray();
        commonSecret.writeUTFBytes(token.secret);
        token.onResultCallback(result.result, commonSecret, token);
    }

    private function onFault(fault:FaultEvent, token:FPCAuthenticationToken):void {
        var fpcException:FPCAuthenticationException = this.getFPCAuthenticationException(fault);
        token.onFaultCallback(fpcException, token);
    }
}
}
