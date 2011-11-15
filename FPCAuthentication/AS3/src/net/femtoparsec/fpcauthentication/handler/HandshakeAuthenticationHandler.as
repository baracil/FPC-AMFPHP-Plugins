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
 * Time: 04:43
 */
package net.femtoparsec.fpcauthentication.handler {
import flash.utils.ByteArray;

import mx.logging.ILogger;
import mx.logging.Log;

import mx.rpc.AbstractOperation;
import mx.rpc.AsyncResponder;
import mx.rpc.AsyncToken;
import mx.rpc.events.FaultEvent;
import mx.rpc.events.ResultEvent;

import net.femtoparsec.fpcauthentication.*;

public class HandshakeAuthenticationHandler extends AbstractAuthenticationHandler {

    private var _challengeProvider:IChallengeProvider;

    private var _challengeSolver:IChallengeSolver;

    public function HandshakeAuthenticationHandler(fpcAuthentication:FPCAuthentication) {
        super(fpcAuthentication);
        _challengeProvider = fpcAuthentication.challengeProvider;
        _challengeSolver = fpcAuthentication.challengeSolver;
    }

    override public function handle(token:FPCAuthenticationToken):void {
        var innerToken:InnerToken = new InnerToken(token);

        this.handshake(
                HandshakeType.CHALLENGE_REQUEST,
                innerToken.login,
                onRequestResult,
                innerToken);
    }

    /**
     * Callback that handles response from the server of a CHALLENGE_REQUEST message
     * @param challenge the challenge sent by the server
     * @param token the authentication token
     * @param info optional information from the server. null for this response
     */
    private function onRequestResult(challenge:ByteArray, token:InnerToken, info:* = null):void {
        var data:ByteArray = this._challengeSolver.solve(challenge, token.secret);

        this.handshake(HandshakeType.CHALLENGE_ANSWER,
                data,
                onAnswerResult,
                token);
    }

    /**
     * Callback that handles response from the server of a CHALLENGE_ANSWER message
     * @param challenge the challenge sent by the server
     * @param token the authentication token
     * @param info the custom result of the authentication (by default an object with the properties 'login' and 'roles')
     */
    private function onAnswerResult(challenge:ByteArray, token:InnerToken, info:* = null):void {
        var data:ByteArray = this._challengeSolver.solve(challenge, token.secret);
        data = this._challengeSolver.solve(data, token.secret);

        token.onResultCallback(info, data, token.token)

    }

    /**
     * Perform the call to the server.
     * @param type
     * @param data
     * @param callback
     * @param token
     */
    private function handshake(type:String, data:*, callback:Function, token:InnerToken):void {

        token.sentChallenge = this._challengeProvider.getChallenge();
        token.expectedType = HandshakeType.getNext(type);
        token.innerResultCallback = callback;

        var encodedData:String = Base64Utils.encode(data);
        var encodedChallenge:String = Base64Utils.encodeByteArray(token.sentChallenge);

        var operation:AbstractOperation = this._fpcAuthentication.getOperation("handshake");
        var asyncToken:AsyncToken = operation.send(type, encodedData, encodedChallenge);
        asyncToken.addResponder(new AsyncResponder(onHandshakeResult, onHandshakeFault, token));
    }

    /**
     * pre handler of the server responses. Check for any error (null message, invalid type message and invalid
     * challenge answer). If everything is fine, the callback provided to the handshake method is called.
     * 
     * @param result
     * @param token
     */
    private function onHandshakeResult(result:ResultEvent, token:InnerToken):void {
        var message:HandshakeMessage = result.result as HandshakeMessage;

        var exceptionMessage:String = null;

        if (message == null) {
            exceptionMessage = "unexpected null HandshakeMessage";
        }
        else if (message.type != token.expectedType) {
            exceptionMessage = ["invalid HandshakeMessage type :",message.type, token.expectedType,"was expected"].join(" ");
        }
        else {
            var expectedAnswer:ByteArray = this._challengeSolver.solve(token.sentChallenge, token.secret);
            //compare in Base64
            var encodedExpectedAnswer:String = Base64Utils.encodeByteArray(expectedAnswer);
            if (message.data != encodedExpectedAnswer) {
                exceptionMessage = "Mismatch of challenge answers : secret on client side is invalid";
            }
        }

        if (exceptionMessage == null) {
            token.innerResultCallback(Base64Utils.decode(message.challenge), token, message.info);
        }
        else {
            token.onFaultCallback(new FPCAuthenticationException(token.login, exceptionMessage), token.token);
        }

    }

    private function onHandshakeFault(fault:FaultEvent, token:InnerToken):void {
        var fpcException:FPCAuthenticationException = fault.fault.rootCause as FPCAuthenticationException;
        if (fpcException == null) {
            throw fault.fault;
        }
        token.onFaultCallback(fpcException, token.token);
    }
}

}

import flash.utils.ByteArray;

import net.femtoparsec.fpcauthentication.FPCAuthenticationToken;

internal class InnerToken {

    public var sentChallenge:ByteArray;

    public var innerResultCallback:Function;

    public var expectedType:String;

    public var token:FPCAuthenticationToken;

    public function InnerToken(token:FPCAuthenticationToken) {
        this.token = token;
    }

    public function get login():String {
        return this.token.login;
    }

    public function get secret():String {
        return this.token.secret;
    }

    public function get onResultCallback():Function {
        return token.onResultCallback;
    }

    public function get onFaultCallback():Function {
        return token.onFaultCallback;
    }
}
