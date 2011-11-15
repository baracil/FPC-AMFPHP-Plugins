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
