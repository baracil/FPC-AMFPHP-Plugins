/**
 * User: Bastien Aracil
 * Date: 24/12/11
 * Time: 18:50
 */
package net.femtoparsec.wordpress.rpc {
import mx.rpc.IResponder;

[Bindable]
public class WPObjectResponder implements IResponder {

    public var faultValue:*;

    public var resultValue:*;

    public var value:*;

    public function WPObjectResponder() {
        this.clear();
    }

    public function result(data:Object):void {
        this.resultValue = data;
        this.faultValue = null;
        this.value = data;
    }

    public function fault(info:Object):void {
        this.resultValue = null;
        this.faultValue = info;
        this.value = info;
    }

    private function clear():WPObjectResponder {
        this.faultValue = null;
        this.resultValue = null;
        this.value = null;
        return this;
    }
}
}
