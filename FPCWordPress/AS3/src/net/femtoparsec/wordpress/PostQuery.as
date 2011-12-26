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
public class PostQuery extends WordPressHolder {

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


    public function PostQuery(context:IWordPress) {
        this.context = context;
    }

    public function withNumberPosts(value:int = 5):PostQuery {
        this._numberposts = value;
        return this;
    }

    public function withOffset(offset:int = 0):PostQuery {
        this._offset = offset;
        return this;
    }

    public function withCategoryId(id:int = -1):PostQuery {
        this._category = id;
        return this;
    }

    public function withCategory(category:Category = null):PostQuery {
        var id:int = -1;
        if (category != null) {
            id = category.id;
        }
        return this.withCategoryId(id);
    }

    public function withOrderBy(orderBy:EOrderBy = null):PostQuery {
        this._orderby = orderBy == null ? EOrderBy.DATE.value : orderBy.value;
        return this;
    }

    public function withOrder(order:EOrder = null):PostQuery {
        this._order = order == null ? EOrder.DESC.value : order.value;
        return this;
    }

    public function withInclude(postIds:Array):PostQuery {
        this._include = postIds;
        return this;
    }

    public function withExclude(postIds:Array):PostQuery {
        this._exclude = postIds;
        return this;
    }

    public function withMetaKey(key:String):PostQuery {
        this._meta_key = key;
        return this;
    }

    public function withMetaValue(value:String):PostQuery {
        this._meta_value = value;
        return this;
    }

    public function withPostType(...types):PostQuery {
        this._post_type = this.handleArray(types,  EPostType);
        return this;
    }
    public function withPostStatus(...status):PostQuery {
        this._post_status = this.handleArray(status, EPostStatus);
        return this;
    }

    public function withPostMimeType(type:String):PostQuery {
        this._post_mime_type = type;
        return this;
    }

    public function withPostParentId(id:int = -1):PostQuery {
        this._post_parent = id;
        return this;
    }

    public function withPostParent(post:Post = null):PostQuery {
        return this.withPostParentId(post == null ? -1:post.id);
    }

    private function handleArray(values:Array, type:Class):* {
        var result:Array = [];
        for (var value:* in values) {
            if (value is type) {
                result = result.concat(value.value);
            }
        }
        switch (result.length) {
            case 0 : return null;
            case 1 : return result[0];
        }
        return result;
    }

    public function findPosts():WPAsyncToken {
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
