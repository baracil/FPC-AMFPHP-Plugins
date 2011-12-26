/**
 * User: Bastien Aracil
 * Date: 21/12/11
 * Time: 18:27
 */
package net.femtoparsec.wordpress.rpc {
import mx.rpc.IResponder;

public class WPPropertyResponder implements IResponder {

    public var object:*;

    public var resultProperty:String;

    public var faultProperty:String;

    public function WPPropertyResponder(object:* = null, resultProperty:String = null, faultProperty:String = null) {
        this.object = object;
        this.resultProperty = resultProperty;
        this.faultProperty = faultProperty;
    }

    public function result(data:Object):void {
        this.setProperty(resultProperty, data);
    }

    public function fault(info:Object):void {
        this.setProperty(faultProperty, info);
    }

    private function setProperty(property:String, value:*):void {
        if (!object || !property) {
            return
        }
        object[property] = value;
    }
}
}
