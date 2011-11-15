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
