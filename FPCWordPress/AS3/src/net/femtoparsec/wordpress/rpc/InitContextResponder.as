/**
 * User: Bastien Aracil
 * Date: 24/12/11
 * Time: 15:20
 */
package net.femtoparsec.wordpress.rpc {
import mx.rpc.IResponder;

import net.femtoparsec.wordpress.IWordPress;
import net.femtoparsec.wordpress.IWordPressHolder;
import net.femtoparsec.wordpress.rpc.RPCUtils;

public class InitContextResponder implements IResponder {

    private var _context:IWordPress;

    public function InitContextResponder(context:IWordPress) {
        _context = context;
    }

    public function result(value:Object):void {
        var result:* = RPCUtils.getResult(value);
        this.initContext(result);
    }

    public function fault(value:Object):void {
        var rootCause:* = RPCUtils.getRootCause(value);
        this.initContext(rootCause);
    }

    private function initContext(value:*):void {
        var oneValue:*;
        if (value is IWordPressHolder) {
            (value as IWordPressHolder).context = _context;
        }
        else if (value is Array) {
            for each (oneValue in value) {
                this.initContext(oneValue);
            }
        }
        else if (value is Object) {
            for each (oneValue in value) {
                this.initContext(oneValue);
            }
        }
    }

}
}
