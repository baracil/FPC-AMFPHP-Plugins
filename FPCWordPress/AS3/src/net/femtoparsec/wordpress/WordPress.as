/**
 * User: Bastien Aracil
 * Date: 20/12/11
 * Time: 06:30
 */
package net.femtoparsec.wordpress {
import net.femtoparsec.wordpress.exception.ExceptionBootStrap;
import net.femtoparsec.wordpress.model.ModelBootStrap;
import net.femtoparsec.wordpress.rpc.WPAsyncToken;

public class WordPress extends AbstractWordPress implements IWordPress {

    ModelBootStrap;
    ExceptionBootStrap;

    /**
     * Create a PostsQuery to ease the query of posts
     * @return
     */
    public function createPostsQuery():PostsQuery {
        return new PostsQuery(this);
    }

    /**
     * @param args the query arguments (see http://codex.wordpress.org/Template_Tags/get_posts)
     * @return the posts matching the query arguments
     */
    public function findPosts(args:*):WPAsyncToken {
        return this.call("findPosts", args);
    }

    /**
     * @return all the categories
     */
    public function getCategories():WPAsyncToken {
        return this.call("getCategories");
    }

    /**
     * @param userId
     * @return the user with the given id
     * @throws UnknownUserException if no user exists with the given id
     */
    public function getUser(userId:int):WPAsyncToken {
        return this.callOneArgument("getUser", userId);
    }
}
}

