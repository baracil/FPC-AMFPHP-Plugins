/**
 * User: Bastien Aracil
 * Date: 24/12/11
 * Time: 18:59
 */
package net.femtoparsec.wordpress.rpc {
import mx.rpc.IResponder;

import net.femtoparsec.wordpress.rpc.RPCUtils;

public class WrapperResponder implements IResponder {

    private var _delegate:IResponder;

    public function WrapperResponder(delegate:IResponder) {
        _delegate = delegate;
    }

    public function result(data:Object):void {
        if (null != _delegate) {
           _delegate.result(RPCUtils.getResult(data));
        }
    }

    public function fault(info:Object):void {
        if (null != _delegate) {
           _delegate.fault(RPCUtils.getRootCause(info));
        }
    }
}
}
