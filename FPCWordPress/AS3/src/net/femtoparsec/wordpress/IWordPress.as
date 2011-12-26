/**
 * User: Bastien Aracil
 * Date: 20/12/11
 * Time: 06:27
 */
package net.femtoparsec.wordpress {
import net.femtoparsec.wordpress.rpc.WPAsyncToken;

public interface IWordPress {

    function createPostsQuery():PostsQuery;

    function findPosts(args:*):WPAsyncToken;

    function getCategories():WPAsyncToken;

    function getUser(userId:int):WPAsyncToken;
}
}
