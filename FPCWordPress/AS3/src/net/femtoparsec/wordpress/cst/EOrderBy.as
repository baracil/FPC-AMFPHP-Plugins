/**
 * User: Bastien Aracil
 * Date: 20/12/11
 * Time: 11:16
 */
package net.femtoparsec.wordpress.cst {
public class EOrderBy extends SimpleEnum {

    public static const NONE:EOrderBy = _('none');

    public static const ID:EOrderBy = _('ID');

    public static const AUTHOR:EOrderBy = _('author');

    public static const TITLE:EOrderBy = _('title');

    public static const DATE:EOrderBy = _('post_date');

    public static const MODIFIED:EOrderBy = _('modified');

    public static const PARENT:EOrderBy = _('parent');

    public static const RAND:EOrderBy = _('rand');

    public static const COMMENT_COUNT:EOrderBy = _('comment_count');

    public static const MENU_ORDER:EOrderBy = _('menu_order');

    public static const META_VALUE:EOrderBy = _('meta_value');

    public static const META_VALUE_NUM:EOrderBy = _('meta_value_num');

    private static function _(value:String):EOrderBy {
        return new EOrderBy().init(value);
    }
}
}
