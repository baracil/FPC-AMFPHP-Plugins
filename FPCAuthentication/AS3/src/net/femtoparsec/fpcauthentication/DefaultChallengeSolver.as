/**
 * User: Bastien Aracil
 * Date: 13/11/11
 * Time: 23:29
 */
package net.femtoparsec.fpcauthentication {
import flash.utils.ByteArray;

import mx.utils.SHA256;

/**
 * The default challenge solver. Do exactly the same than the one on the server side
 */
public class DefaultChallengeSolver implements IChallengeSolver {

    public function solve(challenge:ByteArray, secret:String):ByteArray {
        var buffer:ByteArray = new ByteArray();
        buffer.writeUTFBytes(secret);
        buffer.writeBytes(challenge);


        return byteArrayDigest(buffer);
    }

    private function byteArrayDigest(data:ByteArray):ByteArray {
        var digest:String = SHA256.computeDigest(data);

        var result:ByteArray = new ByteArray();
        var idx:int = 0;
        while (idx < digest.length) {
            result.writeByte(int("0x"+digest.substr(idx, 2)));
            idx+=2;
        }
        return result;

    }
}
}
