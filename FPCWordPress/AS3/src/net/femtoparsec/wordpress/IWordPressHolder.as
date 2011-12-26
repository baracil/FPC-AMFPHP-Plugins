/**
 * User: Bastien Aracil
 * Date: 20/12/11
 * Time: 06:27
 */
package net.femtoparsec.wordpress {
public interface IWordPressHolder {

    function set context(context:IWordPress):void;

    function get context():IWordPress;

}
}
