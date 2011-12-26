/**
 * User: Bastien Aracil
 * Date: 20/12/11
 * Time: 06:28
 */
package net.femtoparsec.wordpress {
public class WordPressHolder implements IWordPressHolder {

    private var _context:IWordPress;

    [Bindable]
    public function get context():IWordPress {
        return this._context;
    }

    public function set context(context:IWordPress):void {
        this._context = context;
    }

}
}
