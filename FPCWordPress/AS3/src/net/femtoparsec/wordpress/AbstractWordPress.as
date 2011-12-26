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
 * Date: 20/12/11
 * Time: 17:41
 */
package net.femtoparsec.wordpress {
import mx.messaging.ChannelSet;
import mx.rpc.AbstractOperation;
import mx.rpc.AsyncToken;
import mx.rpc.IResponder;
import mx.rpc.remoting.mxml.RemoteObject;

import net.femtoparsec.wordpress.rpc.InitContextResponder;
import net.femtoparsec.wordpress.rpc.WPAsyncToken;

public class AbstractWordPress {

    private var _initContextResponder:IResponder;

    private var _service:RemoteObject;

    public function AbstractWordPress() {
        this._service = new RemoteObject("amfphp");
        this._service.source = "fpcWordPress";
        this.showBusyCursor = true;
        this._initContextResponder = new InitContextResponder(this as IWordPress);
    }

    /**
     * Protected methods
     */


    /**
     * @param operationName name of the remote operation
     * @param argument the argument of the remote operation
     * @return a WPAsyncResult to which IWPResponder can be added
     */
    protected function callOneArgument(operationName:String, argument:*):WPAsyncToken {
        return this.innerCall(operationName, argument);
    }

    protected function call(operationName:String, ...arguments):WPAsyncToken {
        return this.innerCall(operationName, arguments);
    }


    private function innerCall(operationName:String, arguments:*):WPAsyncToken {
        var operation:AbstractOperation = this._service.getOperation(operationName);
        var asyncToken:AsyncToken = operation.send(arguments);

        this.setupInnerResponder(asyncToken);

        return new WPAsyncToken(asyncToken);
    }

    private function setupInnerResponder(asyncToken:AsyncToken):AsyncToken {
        asyncToken.addResponder(this._initContextResponder);
        return asyncToken;
    }

    /**
     * RemoteObject delegate
     */
    [Bindable]
    public function get channelSet():ChannelSet {
        return _service.channelSet;
    }

    public function set channelSet(value:ChannelSet):void {
        _service.channelSet = value;
    }

    [Bindable]
    public function get concurrency():String {
        return _service.concurrency;
    }

    public function set concurrency(c:String):void {
        _service.concurrency = c;
    }

    [Bindable]
    public function get endpoint():String {
        return _service.endpoint;
    }

    public function set endpoint(url:String):void {
        _service.endpoint = url;
    }

    [Bindable]
    public function get showBusyCursor():Boolean {
        return _service.showBusyCursor;
    }

    public function set showBusyCursor(sbc:Boolean):void {
        _service.showBusyCursor = sbc;
    }

    [Bindable]
    public function get source():String {
        return _service.source;
    }

    public function set source(s:String):void {
        _service.source = s;
    }
}
}
