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
