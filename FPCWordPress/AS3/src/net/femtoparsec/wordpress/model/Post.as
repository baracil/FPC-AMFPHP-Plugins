/**
 * User: Bastien Aracil
 * Date: 18/12/11
 * Time: 19:03
 */
package net.femtoparsec.wordpress.model {
[RemoteClass(alias="FPCWordPress.model.Post")]
public class Post {//extends WordPressHolder {

    /**
     * Id of the post
     */
    public var id:int;

    /**
     * Id of the author
     */
    public var authorId:int;

    /**
     * the content of the post
     */
    public var content:String;

    /**
     * the title of the post
     */
    public var title:String;

    /**
     * the local creation date of the post
     */
    public var date:Date;

    /**
     * the GMT creation date of the post
     */
    public var dateGMT:Date;

}
}
