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
 * Date: 22/12/11
 * Time: 19:47
 */
package net.femtoparsec.wordpress.rpc {
import flash.events.EventDispatcher;

import mx.rpc.AsyncResponder;
import mx.rpc.AsyncToken;
import mx.rpc.IResponder;
import mx.rpc.Responder;

public class WPAsyncToken extends EventDispatcher implements IWPAsyncToken {

    private var _asyncToken:AsyncToken;

    public function WPAsyncToken(asyncToken:AsyncToken) {
        this._asyncToken = asyncToken;
    }

    public function get asyncToken():AsyncToken {
        return _asyncToken;
    }

    public function addResponder(responder:IResponder):IWPAsyncToken {
        _asyncToken.addResponder(new WrapperResponder(responder));
        return this;
    }

    public function addOnResult(callback:Function, token:* = null):IWPAsyncToken {
        return this.addCallbacks(callback, null, token);
    }

    public function addOnFault(callback:Function, token:* = null):IWPAsyncToken {
        return this.addCallbacks(null, callback, token);
    }

    public function addCallbacks(onResult:Function, onFault:Function, token:* = null):IWPAsyncToken {
        if (token == null) {
            return this.addResponder(new Responder(onResult, onFault));
        }
        else {
            this.addResponder(new AsyncResponder(onResult, onFault, token));
        }
        return this;
    }

    public function addBinding(object:*, resultPropertyName:String, faultPropertyName:String = null):IWPAsyncToken {
        return this.addResponder(new WPPropertyResponder(object, resultPropertyName, faultPropertyName))
    }
}
}
