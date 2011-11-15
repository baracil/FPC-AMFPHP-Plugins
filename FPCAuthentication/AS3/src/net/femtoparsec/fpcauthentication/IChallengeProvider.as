/**
 * User: Bastien Aracil
 * Date: 13/11/11
 * Time: 17:13
 */
package net.femtoparsec.fpcauthentication {
import flash.utils.ByteArray;

/**
 * Provider of challenge for the handshake authentication mode
 */
public interface IChallengeProvider {

    function getChallenge():ByteArray;

}
}
