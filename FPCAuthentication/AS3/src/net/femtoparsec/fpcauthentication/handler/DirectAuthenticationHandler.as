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
