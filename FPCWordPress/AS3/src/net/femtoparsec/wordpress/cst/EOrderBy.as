/*
    Copyright (c) 2011, Bastien Aracil
    All rights reserved.
    New BSD license. See http://en.wikipedia.org/wiki/Bsd_license

    Redistribution and use in source and binary forms, with or without
    modification, are permitted provided that the following conditions are met:
       * Redistributions of source code must retain the above copyright
         notice, this list of conditions and the following disclaimer.
       * Redistributions in binary form must reproduce the above copyright
         notice, this list of conditions and the following disclaimer in the
         documentation and/or other materials provided with the distribution.
       * The name of Bastien Aracil may not be used to endorse or promote products
         derived from this software without specific prior written permission.

    THIS SOFTWARE IS PROVIDED BY THE COPYRIGHT HOLDERS AND CONTRIBUTORS "AS IS" AND
    ANY EXPRESS OR IMPLIED WARRANTIES, INCLUDING, BUT NOT LIMITED TO, THE IMPLIED
    WARRANTIES OF MERCHANTABILITY AND FITNESS FOR A PARTICULAR PURPOSE ARE
    DISCLAIMED. IN NO EVENT SHALL BASTIEN ARACIL BE LIABLE FOR ANY
    DIRECT, INDIRECT, INCIDENTAL, SPECIAL, EXEMPLARY, OR CONSEQUENTIAL DAMAGES
    (INCLUDING, BUT NOT LIMITED TO, PROCUREMENT OF SUBSTITUTE GOODS OR SERVICES;
    LOSS OF USE, DATA, OR PROFITS; OR BUSINESS INTERRUPTION) HOWEVER CAUSED AND
    ON ANY THEORY OF LIABILITY, WHETHER IN CONTRACT, STRICT LIABILITY, OR TORT
    (INCLUDING NEGLIGENCE OR OTHERWISE) ARISING IN ANY WAY OUT OF THE USE OF THIS
    SOFTWARE, EVEN IF ADVISED OF THE POSSIBILITY OF SUCH DAMAGE.
*/

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
