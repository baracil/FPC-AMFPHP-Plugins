/**
 * User: Bastien Aracil
 * Date: 20/12/11
 * Time: 06:32
 */
package net.femtoparsec.wordpress {
import net.femtoparsec.wordpress.cst.EOrder;
import net.femtoparsec.wordpress.cst.EOrderBy;
import net.femtoparsec.wordpress.cst.EPostStatus;
import net.femtoparsec.wordpress.cst.EPostType;
import net.femtoparsec.wordpress.model.Category;
import net.femtoparsec.wordpress.model.Post;
import net.femtoparsec.wordpress.rpc.WPAsyncToken;

/**
 * Technical class to easy the query of Posts
 *
 */
public class PostsQuery extends WordPressHolder {

    private var _numberposts:int = 5;

    private var _offset:int = 0

    private var _category:int = -1;

    private var _orderby:String = EOrderBy.DATE.value;

    private var _order:String = EOrder.DESC.value;

    private var _include:Array = [];

    private var _exclude:Array = [];

    private var _meta_key:String = "";

    private var _meta_value:String = "";

    private var _post_type:* = EPostType.POST.value;

    private var _post_mime_type:String;

    private var _post_parent:int = -1;

    private var _post_status:* = EPostStatus.PUBLISH.value;


    public function PostsQuery(context:IWordPress) {
        this.context = context;
    }

    public function withNumberPosts(value:int = 5):PostsQuery {
        this._numberposts = value;
        return this;
    }

    public function withOffset(offset:int = 0):PostsQuery {
        this._offset = offset;
        return this;
    }

    public function withCategoryId(id:int = -1):PostsQuery {
        this._category = id;
        return this;
    }

    public function withCategory(category:Category = null):PostsQuery {
        var id:int = -1;
        if (category != null) {
            id = category.id;
        }
        return this.withCategoryId(id);
    }

    public function withOrderBy(orderBy:EOrderBy = null):PostsQuery {
        this._orderby = orderBy == null ? EOrderBy.DATE.value : orderBy.value;
        return this;
    }

    public function withOrder(order:EOrder = null):PostsQuery {
        this._order = order == null ? EOrder.DESC.value : order.value;
        return this;
    }

    public function withInclude(postIds:Array):PostsQuery {
        this._include = postIds;
        return this;
    }

    public function withExclude(postIds:Array):PostsQuery {
        this._exclude = postIds;
        return this;
    }

    public function withMetaKey(key:String):PostsQuery {
        this._meta_key = key;
        return this;
    }

    public function withMetaValue(value:String):PostsQuery {
        this._meta_value = value;
        return this;
    }

    public function withPostType(...types):PostsQuery {
        this._post_type = this.handleArray(types,  EPostType);
        return this;
    }
    public function withPostStatus(...status):PostsQuery {
        this._post_status = this.handleArray(status, EPostStatus);
        return this;
    }

    public function withPostMimeType(type:String):PostsQuery {
        this._post_mime_type = type;
        return this;
    }

    public function withPostParentId(id:int = -1):PostsQuery {
        this._post_parent = id;
        return this;
    }

    public function withPostParent(post:Post = null):PostsQuery {
        return this.withPostParentId(post == null ? -1:post.id);
    }

    private function handleArray(values:Array, type:Class):Array {
        var result:Array = [];
        for (var value:* in values) {
            if (values is type) {
                result = result.concat(value);
            }
        }
        switch (result.length) {
            case 0 : return null;
            case 1 : return result[0];
        }
        return result;
    }

    public function getPosts():WPAsyncToken {
        return this.context.findPosts(createArguments());
    }

    private function createArguments():* {
        var result:* = addArguments({}, 'numberposts');
        result = addArguments(result, 'offset');
        result = addArguments(result, 'category', -1);
        result = addArguments(result, 'orderby');
        result = addArguments(result, 'order');
        result = addArguments(result, 'include');
        result = addArguments(result, 'exclude');
        result = addArguments(result, 'meta_key');
        result = addArguments(result, 'meta_value');
        result = addArguments(result, 'post_type');
        result = addArguments(result, 'post_mime_type');
        result = addArguments(result, 'post_parent', -1);
        result = addArguments(result, 'post_status');
        return result;
    }

    private function addArguments(result:*, property:String, nullValue:* = null):* {
        var value:* = this["_"+property];
        if (value != undefined && value != null && value != nullValue) {
            result[property] = value;
        }
        return result;
    }

}
}
