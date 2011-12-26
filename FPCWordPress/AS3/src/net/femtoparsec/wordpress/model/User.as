/**
 * User: Bastien Aracil
 * Date: 20/12/11
 * Time: 17:55
 */
package net.femtoparsec.wordpress.model {
import net.femtoparsec.wordpress.WordPressHolder;

[RemoteClass(alias="FPCWordPress.model.User")]
public class User extends WordPressHolder {

    public var id:int;

    public var login:String;

    public var niceName:String;

    public var email:String;

    public var url:String;

    public var registered:Date;

    public var displayName:String;

    public var firstName:String;

    public var lastName:String;

    public var nickName:String;

    public var description:String;

    public var capabilities:Object;

    public var adminColor:String;

    public var primaryBlog:String;

    public var richEditing:Boolean;

    public var sourceDomain:String;

}
}
