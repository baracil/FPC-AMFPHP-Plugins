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
