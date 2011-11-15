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
 * Time: 03:58
 */
package net.femtoparsec.fpcauthentication {
import flash.events.Event;
import flash.events.EventDispatcher;
import flash.utils.ByteArray;

import mx.rpc.AbstractOperation;
import mx.rpc.AsyncResponder;
import mx.rpc.remoting.mxml.RemoteObject;

import net.femtoparsec.fpcauthentication.Base64Utils;

import net.femtoparsec.fpcauthentication.handler.AuthenticationHandler;

import net.femtoparsec.fpcauthentication.handler.DirectAuthenticationHandler;
import net.femtoparsec.fpcauthentication.handler.HandshakeAuthenticationHandler;
import net.femtoparsec.fpcauthentication.handler.LogoutHandler;

[Event(name="authentication", type="net.femtoparsec.fpcauthentication.AuthenticationEvent")]
[Event(name="error", type="net.femtoparsec.fpcauthentication.AuthenticationEvent")]
[Event(name="logout", type="net.femtoparsec.fpcauthentication.AuthenticationEvent")]
public class FPCAuthentication  extends EventDispatcher {

    private var _loginService:LoginService;

    [Bindable]
    [Inspectable(enumeration="DIRECT,HANDSHAKE")]
    public var mode:String;

    [Bindable]
    public var challengeProvider:IChallengeProvider;

    [Bindable]
    public var challengeSolver:IChallengeSolver;

    private var _inProgress:Boolean = false;

    private var _logoutHandler:IFPCAuthenticationHandler;

    private var _authenticationHandler:IFPCAuthenticationHandler;

    [Bindable(event="inProgressChanged")]
    public function get inProgress():Boolean {
        return this._inProgress;
    }

    private var _authenticated:Boolean = false;

    [Bindable(event="authenticatedChanged")]
    public function get authenticated():Boolean {
        return _authenticated;
    }

    private var _authenticatedLogin:String = null;
    [Bindable(event="authenticatedLoginChanged")]
    public function get authenticatedLogin():String {
        return _authenticatedLogin;
    }

    public function FPCAuthentication() {
        this._loginService = new LoginService();
        this.challengeSolver = new DefaultChallengeSolver();
        this.challengeProvider = new DefaultChallengeProvider();
        this._logoutHandler = new LogoutHandler(this);
        this._authenticationHandler = new AuthenticationHandler(this);
    }

    public function logout():Boolean {
        if (!this._authenticated) {
            return false;
        }

        var token:FPCAuthenticationToken = new FPCAuthenticationToken(this._authenticatedLogin);
        token.onResultCallback = this.onLogoutResult;
        token.onFaultCallback = this.onLogoutFault;

        return launch(this._logoutHandler, token);
    }

    public function authenticate(login:String, secret:String):Boolean {
        if (this._authenticated) {
            return false;
        }

        var token:FPCAuthenticationToken = new FPCAuthenticationToken(login, secret, this.mode);
        token.onResultCallback = this.onAuthenticationResult;
        token.onFaultCallback = this.onAuthenticationFault;

        return launch(this._authenticationHandler, token);
    }

    //Delegate method to the remoteObject
    public function get destination():String {
        return _loginService.destination;
    }

    public function set destination(name:String):void {
        _loginService.destination = name;
    }

    public function get concurrency():String {
        return _loginService.concurrency;
    }

    public function set concurrency(c:String):void {
        _loginService.concurrency = c;
    }

    public function get endpoint():String {
        return _loginService.endpoint;
    }

    public function set endpoint(url:String):void {
        _loginService.endpoint = url;
    }

    public function get makeObjectsBindable():Boolean {
        return _loginService.makeObjectsBindable;
    }

    public function set makeObjectsBindable(b:Boolean):void {
        _loginService.makeObjectsBindable = b;
    }

    public function get showBusyCursor():Boolean {
        return _loginService.showBusyCursor;
    }

    public function set showBusyCursor(sbc:Boolean):void {
        _loginService.showBusyCursor = sbc;
    }

    public function getOperation(name:String):AbstractOperation {
        return _loginService.getOperation(name);
    }

//private methods

    private function launch(handler:IFPCAuthenticationHandler, token:FPCAuthenticationToken = null):Boolean {
        if (_inProgress) {
            return false;
        }
        this.validate();
        this.setInProgress(true);

        handler.handle(token);

        return true;
    }

    private function doLogout(token:FPCAuthenticationToken):void {
        var operation:AbstractOperation = this._loginService.getOperation("logout");
        operation.send().addResponder(new AsyncResponder(this.onLogoutResult, this.onLogoutFault, token));
    }

    private function setInProgress(value:Boolean):void {
        if (value == this._inProgress) {
            return;
        }
        this._inProgress = value;
        this.dispatchEvent(new Event("inProgressChanged"));
    }

    private function setAuthenticated(value:Boolean):void {
        if (value == this._authenticated) {
            return;
        }
        this._authenticated = value;
        this.dispatchEvent(new Event("authenticatedChanged"));
    }

    private function setAuthenticatedLogin(login:String):void {
        if (this._authenticatedLogin == login) {
            return;
        }
        this._authenticatedLogin = login;
        this.setAuthenticated(login != null);
        this.dispatchEvent(new Event("authenticatedLoginChanged"));
    }

    private function onAuthenticationResult(result:*, commonSecret:ByteArray, token:FPCAuthenticationToken):void {
        trace(Base64Utils.encodeByteArray(commonSecret));
        this.setAuthenticatedLogin(token.login);
        this.endTreatment(AuthenticationEvent.authentication(token.login, result));
    }

    private function onAuthenticationFault(fault:FPCAuthenticationException, token:FPCAuthenticationToken):void {
        this.setAuthenticatedLogin(null);
        this.endTreatment(AuthenticationEvent.error(token.login, fault));
    }

    private function onLogoutResult(result:*, token:FPCAuthenticationToken):void {
        this.setAuthenticatedLogin(null);
        this.endTreatment(AuthenticationEvent.logout(token.login));
    }

    private function onLogoutFault(fault:FPCAuthenticationException, token:FPCAuthenticationToken):void {
        this.endTreatment(AuthenticationEvent.error(token.login, fault));
    }

    private function endTreatment(event:Event):void {
        this.dispatchEvent(event);
        this.setInProgress(false);
    }

    private function validate():void {
        if (this.challengeSolver == null) {
            throw new Error("challengeSolver cannot be null");
        }
        if (this.challengeProvider == null) {
            throw new Error("challengeProvider cannot be null");
        }
        switch (this.mode) {
            case FPCAuthenticationMode.DIRECT :
            case FPCAuthenticationMode.HANDSHAKE : return;
            default : throw new Error("Invalid mode : " + this.mode);
        }
    }


}
}
