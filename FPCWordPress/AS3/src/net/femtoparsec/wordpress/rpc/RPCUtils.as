/**
 * User: Bastien Aracil
 * Date: 24/12/11
 * Time: 15:27
 */
package net.femtoparsec.wordpress.rpc {
import mx.rpc.events.FaultEvent;
import mx.rpc.events.ResultEvent;

public class RPCUtils {

    public static function getResult(value:Object):* {
        if (value is ResultEvent) {
            return (value as ResultEvent).result;
        }
        return value;
    }

    public static function getRootCause(value:Object):* {
        if (value is FaultEvent) {
            return (value as FaultEvent).fault.rootCause;
        }
        return value;
    }

    public static function getValue(value:Object):* {
        if (value is ResultEvent) {
            return (value as ResultEvent).result;
        }
        else if (value is FaultEvent) {
            return (value as FaultEvent).fault.rootCause;
        }
        return value;
    }
}
}
