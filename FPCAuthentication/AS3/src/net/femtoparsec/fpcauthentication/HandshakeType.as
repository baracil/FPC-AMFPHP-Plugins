/**
 * User: Bastien Aracil
 * Date: 13/11/11
 * Time: 04:15
 */
package net.femtoparsec.fpcauthentication {
/**
 * Type of the messages used for the handshake mode.
 */
public class HandshakeType {

    public static const CHALLENGE_REQUEST:String = "challengeRequest";

    public static const CHALLENGE:String = "challenge";

    public static const CHALLENGE_ANSWER:String = "challengeAnswer";

    public static const CHALLENGE_VALIDATION:String = "challengeValidation";


    public static function getNext(mode:String):String {
        switch (mode) {
            case CHALLENGE_REQUEST : return CHALLENGE;
            case CHALLENGE : return CHALLENGE_ANSWER;
            case CHALLENGE_ANSWER : return CHALLENGE_VALIDATION;
            default : return null;
        }
    }

}
}
