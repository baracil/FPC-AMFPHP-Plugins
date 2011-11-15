<?php
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
 * Date: 09/11/11
 */

class FPCAuthentication_HandshakeType {

    //sent by the client to start the handshake
    const CHALLENGE_REQUEST = "challengeRequest";

    //sent by the server in response of a CHALLENGE_REQUEST
    const CHALLENGE = "challenge";

    //sent by the client in response of a CHALLENGE message from the server
    const CHALLENGE_ANSWER = "challengeAnswer";

    //sent by the server if the answer from the client is correct. When the client receive
    //this message, it can assume it has been authenticated on the server.
    const CHALLENGE_VALIDATION = "challengeValidation";

    /**
     * @static
     * @throws FPCAuthentication_Exception
     * @param $type the type of the current message
     * @return string the type of the next message in the protocol
     */
    public static function getNext($type) {
        switch ($type) {
            case FPCAuthentication_HandshakeType::CHALLENGE_REQUEST : return FPCAuthentication_HandshakeType::CHALLENGE;
            case FPCAuthentication_HandshakeType::CHALLENGE : return FPCAuthentication_HandshakeType::CHALLENGE_ANSWER;
            case FPCAuthentication_HandshakeType::CHALLENGE_ANSWER : return FPCAuthentication_HandshakeType::CHALLENGE_VALIDATION;
            case FPCAuthentication_HandshakeType::CHALLENGE_VALIDATION : return null;
            default : throw new FPCAuthentication_Exception("Invalid type : $type");
        }
    }
}
