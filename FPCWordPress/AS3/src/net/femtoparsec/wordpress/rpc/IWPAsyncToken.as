/**
 * User: Bastien Aracil
 * Date: 24/12/11
 * Time: 18:36
 */
package net.femtoparsec.wordpress.rpc {
import mx.rpc.IResponder;

public interface IWPAsyncToken {

    function addResponder(responder:IResponder):IWPAsyncToken;

    function addOnResult(callback:Function, token:* = null):IWPAsyncToken;

    function addOnFault(callback:Function, token:* = null):IWPAsyncToken;

    function addCallbacks(onResult:Function, onFault:Function, token:* = null):IWPAsyncToken;

    function addBinding(object:*, resultProperty:String, faultProperty:String = null):IWPAsyncToken;

}
}
