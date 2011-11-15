/**
 * User: Bastien Aracil
 * Date: 15/11/11
 * Time: 23:20
 */
package net.femtoparsec.fpcauthentication.handler {
import net.femtoparsec.fpcauthentication.FPCAuthentication;
import net.femtoparsec.fpcauthentication.FPCAuthenticationMode;
import net.femtoparsec.fpcauthentication.FPCAuthenticationToken;
import net.femtoparsec.fpcauthentication.IFPCAuthenticationHandler;

public class AuthenticationHandler implements IFPCAuthenticationHandler {

    private var _directHandler:IFPCAuthenticationHandler;

    private var _handshakeHandler:IFPCAuthenticationHandler;

    public function AuthenticationHandler(fpcAuthentication:FPCAuthentication) {
        this._directHandler = new DirectAuthenticationHandler(fpcAuthentication);
        this._handshakeHandler = new HandshakeAuthenticationHandler(fpcAuthentication);
    }


    public function handle(token:FPCAuthenticationToken):void {
        var handler:IFPCAuthenticationHandler;

        switch (token.mode) {
            case FPCAuthenticationMode.DIRECT : handler = this._directHandler;break;
            case FPCAuthenticationMode.HANDSHAKE : handler = this._handshakeHandler;break;
            default : throw new Error("Invalid mode : " + token.mode);
        }

        handler.handle(token);
    }
}
}
