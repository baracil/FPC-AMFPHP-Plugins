/*
    Copyright (c) 2011, Bastien Aracil
    All rights reserved.
    New BSD license. See http://en.wikipedia.org/wiki/Bsd_license

    Redistribution and use in source and binary forms, with or without
    modification, are permitted provided that the following conditions are met:
       * Redistributions of source code must retain the above copyright
         notice, this list of conditions and the following disclaimer.
       * Redistributions in binary form must reproduce the above copyright
         notice, this list of conditions and the following disclaimer in the
         documentation and/or other materials provided with the distribution.
       * The name of Bastien Aracil may not be used to endorse or promote products
         derived from this software without specific prior written permission.

    THIS SOFTWARE IS PROVIDED BY THE COPYRIGHT HOLDERS AND CONTRIBUTORS "AS IS" AND
    ANY EXPRESS OR IMPLIED WARRANTIES, INCLUDING, BUT NOT LIMITED TO, THE IMPLIED
    WARRANTIES OF MERCHANTABILITY AND FITNESS FOR A PARTICULAR PURPOSE ARE
    DISCLAIMED. IN NO EVENT SHALL BASTIEN ARACIL BE LIABLE FOR ANY
    DIRECT, INDIRECT, INCIDENTAL, SPECIAL, EXEMPLARY, OR CONSEQUENTIAL DAMAGES
    (INCLUDING, BUT NOT LIMITED TO, PROCUREMENT OF SUBSTITUTE GOODS OR SERVICES;
    LOSS OF USE, DATA, OR PROFITS; OR BUSINESS INTERRUPTION) HOWEVER CAUSED AND
    ON ANY THEORY OF LIABILITY, WHETHER IN CONTRACT, STRICT LIABILITY, OR TORT
    (INCLUDING NEGLIGENCE OR OTHERWISE) ARISING IN ANY WAY OUT OF THE USE OF THIS
    SOFTWARE, EVEN IF ADVISED OF THE POSSIBILITY OF SUCH DAMAGE.
*/

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
