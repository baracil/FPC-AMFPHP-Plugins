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
