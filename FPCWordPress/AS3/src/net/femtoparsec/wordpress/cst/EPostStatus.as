/**
 * User: Bastien Aracil
 * Date: 20/12/11
 * Time: 11:39
 */
package net.femtoparsec.wordpress.cst {
public class EPostStatus extends SimpleEnum {

    public static const PUBLISH:EPostStatus = _('publish');

    public static const PENDING:EPostStatus = _('pending');

    public static const DRAFT:EPostStatus = _('draft');

    public static const AUTO_DRAFT:EPostStatus = _('auto-draft');

    public static const FUTURE:EPostStatus = _('future');

    public static const PRIVATE:EPostStatus = _('private');

    public static const INHERIT:EPostStatus = _('inherit');

    public static const TRASH:EPostStatus = _('trash');

    public static const ANY:EPostStatus = _('any');

    private static function _(value:String):EPostStatus {
        return new EPostStatus().init(value);
    }
}
}
