/**
 * User: Bastien Aracil
 * Date: 18/12/11
 * Time: 19:01
 */
package net.femtoparsec.wordpress.model {
import net.femtoparsec.wordpress.WordPressHolder;

[RemoteClass(alias="FPCWordPress.model.Category")]
public class Category extends WordPressHolder {

    public var id:int;

    public var count:int;

    public var description:String;

    public var name:String;

    public var niceName:String;

    public var parent:*;


}
}
