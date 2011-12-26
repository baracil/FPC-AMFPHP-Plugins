/**
 * User: Bastien Aracil
 * Date: 20/12/11
 * Time: 11:28
 */
package net.femtoparsec.wordpress.cst {
public class EOrder extends SimpleEnum {

    public static const ASC:EOrder = _('ASC');
    public static const DESC:EOrder = _('DESC');

    private static function _(value:String):EOrder {
        return new EOrder().init(value);
    }
}
}
