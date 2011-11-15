/**
 * User: Bastien Aracil
 * Date: 13/11/11
 * Time: 19:21
 */
package net.femtoparsec.fpcauthentication {

[RemoteClass(alias="plugins.amfphp.FPCAuthentication.Exception")]
public class FPCAuthenticationException {

    public var login:String;

    public var message:String;

    public function FPCAuthenticationException(login:String = null, message:String = null) {
        this.login = login;
        this.message = message;
    }
}
}
