/**
 * User: Bastien Aracil
 * Date: 13/11/11
 * Time: 04:33
 */
package net.femtoparsec.fpcauthentication {
/**
 * Handler of a authentication mode
 */
public interface IFPCAuthenticationHandler {

    function handle(token:FPCAuthenticationToken):void;

}
}
