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
 * Time: 19:37
 */
package net.femtoparsec.fpcauthentication {
import flash.utils.ByteArray;

import mx.utils.Base64Decoder;
import mx.utils.Base64Encoder;

/**
 * Utility class to simplify the use of the Base64Encoder and Base64Decoder
 */
public class Base64Utils {

    private static const encoder:Base64Encoder = new Base64Encoder();

    private static const decoder:Base64Decoder = new Base64Decoder();

    public static function encodeString(value:String):String {
        encoder.reset();
        encoder.encode(value);
        return encoder.toString();
    }

    public static function encodeByteArray(data:ByteArray):String {
        encoder.reset();
        encoder.encodeBytes(data);
        return encoder.toString();
    }

    public static function decode(data:String):ByteArray {
        decoder.reset();
        decoder.decode(data);
        return decoder.toByteArray();
    }

    public static function encode(data:*):String {
        if (data is ByteArray) {
            return encodeByteArray(data);
        }
        else if (data is String) {
            return encodeString(data);
        }
        throw new Error("Invalid data type for Base64 encoding : String and ByteArray only")
    }
}
}
