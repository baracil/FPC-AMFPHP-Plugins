/**
 * User: Bastien Aracil
 * Date: 13/11/11
 * Time: 17:12
 */
package net.femtoparsec.fpcauthentication {
import flash.utils.ByteArray;

/**
 * Challenge solver for the handshake authentication mode
 */
public interface IChallengeSolver {

    function solve(challenge:ByteArray, secret:String):ByteArray;

}
}
