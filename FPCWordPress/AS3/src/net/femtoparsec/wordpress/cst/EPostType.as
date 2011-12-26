/**
 * User: Bastien Aracil
 * Date: 20/12/11
 * Time: 11:35
 */
package net.femtoparsec.wordpress.cst {
public class EPostType extends SimpleEnum {

    public static const POST:EPostType = _('post');

    public static const PAGE:EPostType = _('page');

    public static const REVISION:EPostType = _('revision');

    public static const ATTACHMENT:EPostType = _('attachment');

    public static const ANY:EPostType = _('any');

    private static function _(value:String):EPostType {
        return new EPostType().init(value);
    }

}
}
