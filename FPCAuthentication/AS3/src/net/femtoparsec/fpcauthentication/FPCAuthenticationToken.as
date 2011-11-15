/**
 * User: Bastien Aracil
 * Date: 13/11/11
 * Time: 04:33
 */
package net.femtoparsec.fpcauthentication {

/**
 * token used for the RPC to keep user data.
 */
public class FPCAuthenticationToken {

    private var _mode:String;

    private var _login:String;

    private var _secret:String;

    private var _onResultCallback:Function;

    private var _onFaultCallback:Function;

    public function FPCAuthenticationToken(login:String, secret:String = null, mode:String = null) {
        this._mode = mode;
        this._login = login;
        this._secret = secret;
    }

    public function get mode():String {
        return _mode;
    }

    public function get login():String {
        return _login;
    }

    public function get secret():String {
        return _secret;
    }

    public function get onResultCallback():Function {
        return _onResultCallback;
    }

    public function set onResultCallback(value:Function):void {
        _onResultCallback = value;
    }

    public function get onFaultCallback():Function {
        return _onFaultCallback;
    }

    public function set onFaultCallback(value:Function):void {
        _onFaultCallback = value;
    }
}
}
