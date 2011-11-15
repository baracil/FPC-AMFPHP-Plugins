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
 * Time: 04:23
 */
package net.femtoparsec.fpcauthentication {
import flash.events.Event;

public class AuthenticationEvent extends Event {

    public static const LOGOUT_EVENT:String = "logout";

    public static const AUTHENTICATION_EVENT:String = "authentication";

    public static const ERROR_EVENT:String = "error";

    public static function logout(login:String, bubbles:Boolean = false, cancelable:Boolean = false):AuthenticationEvent {
        var event:AuthenticationEvent = new AuthenticationEvent(LOGOUT_EVENT, bubbles, cancelable);
        event.login = login;
        return event;
    }

    public static function authentication(login:String, data:*, bubbles:Boolean = false, cancelable:Boolean = false):AuthenticationEvent {
        var event:AuthenticationEvent = new AuthenticationEvent(AUTHENTICATION_EVENT, bubbles, cancelable);
        event.login = login;
        event.data = data;
        return event;
    }

    public static function error(login:String, error:FPCAuthenticationException, bubbles:Boolean = false, cancelable:Boolean = false):AuthenticationEvent {
        var event:AuthenticationEvent = new AuthenticationEvent(ERROR_EVENT, bubbles, cancelable);
        event.login = login;
        event.error = error;
        return event;
    }

    /**
     * login of the user trying to authenticate
     */
    public var login:String;

    /**
     * data of the authenticated user. null if the authentication failed
     */
    public var data:*;

    /**
     * information about any error that occurred during the authentication. null if the authentication is successful.
     */
    public var error:FPCAuthenticationException;

    public function AuthenticationEvent(type:String, bubbles:Boolean = false, cancelable:Boolean = false) {
        super(type, bubbles, cancelable)
    }
}
}
