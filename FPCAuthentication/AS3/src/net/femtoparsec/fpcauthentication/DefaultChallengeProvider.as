/**
 * User: Bastien Aracil
 * Date: 15/11/11
 * Time: 17:59
 */
package net.femtoparsec.fpcauthentication {
import flash.utils.ByteArray;

public class DefaultChallengeProvider implements IChallengeProvider {

    private var _length:int;


    public function DefaultChallengeProvider(length:int = 64) {
        _length = length;
    }

    public function getChallenge():ByteArray {
        var ba:ByteArray = new ByteArray();
        for (var idx:uint = 0; idx < _length; idx++) {
            ba.writeByte(int(Math.random() * 256));
        }
        return ba;
    }
}
}
