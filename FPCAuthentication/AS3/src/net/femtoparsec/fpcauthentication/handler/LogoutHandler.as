/**
 * User: Bastien Aracil
 * Date: 15/11/11
 * Time: 23:11
 */
package net.femtoparsec.fpcauthentication.handler {
import mx.rpc.AbstractOperation;
import mx.rpc.AsyncResponder;
import mx.rpc.events.FaultEvent;
import mx.rpc.events.ResultEvent;

import net.femtoparsec.fpcauthentication.FPCAuthentication;
import net.femtoparsec.fpcauthentication.FPCAuthenticationException;
import net.femtoparsec.fpcauthentication.FPCAuthenticationToken;

public class LogoutHandler extends AbstractAuthenticationHandler {
    public function LogoutHandler(fpcAuthentication:FPCAuthentication) {
        super(fpcAuthentication);
    }

    override public function handle(token:FPCAuthenticationToken):void {
        var operation:AbstractOperation = this.getOperation("logout");
        operation.send().addResponder(new AsyncResponder(this.onResult, this.onFault, token));

    }

    private function onFault(fault:FaultEvent, token:FPCAuthenticationToken):void {
        var fpcException:FPCAuthenticationException = this.getFPCAuthenticationException(fault);
        token.onFaultCallback(fpcException, token)
    }

    private function onResult(result:ResultEvent, token:FPCAuthenticationToken):void {
        token.onResultCallback(result.result, token);
    }
}
}
