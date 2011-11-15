/**
 * User: Bastien Aracil
 * Date: 13/11/11
 * Time: 19:20
 */
package net.femtoparsec.fpcauthentication.handler {
[RemoteClass(alias="plugins.amfphp.FPCAuthentication.handler.HandshakeMessage")]
public class HandshakeMessage {

    /**
     * The type of the message. must be one of the const value of HandshakeType
     */
    public var type:String;

    /**
     * payload of the message. Depending of the message but for the client, it is always
     * the server answer of the client challenge (Base64 encoded)
     */
    public var data:String;

    /**
     * the challenge the receiver needed to answer in its response (Base64 encoded)
     */
    public var challenge:String;

    /**
     * optional information. null for all message type but the CHALLENGE_VALIDATION. The information
     * are the custom result of the authentication (by default an object with the properties 'login'
     * and 'roles').
     */
    public var info:*;

}
}
