/**
 * User: Bastien Aracil
 * Date: 20/12/11
 * Time: 11:17
 */
package net.femtoparsec.wordpress.cst {
public class SimpleEnum {

    private var _value:String;

    public function SimpleEnum() {
        _value = undefined;
    }

    public function get value():String {
        return _value;
    }

    protected function init(value:String):* {
        this._value = value;
        return this;
    }
}
}
