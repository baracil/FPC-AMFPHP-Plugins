<?php
/**
 * User: Bastien Aracil
 * Date: 21/12/11
 */
 
class FPCWordPress_WordPressDAO implements FPCWordPress_IWordPressDAO {

    /**
     * @var FPCWordPress_PostBuilder
     */
    private $_postBuilder;

    /**
     * @var FPCWordPress_UserBuilder
     */
    private $_userBuilder;

    /**
     * @var FPCWordPress_CategoryBuilder
     */
    private $_categoryBuilder;

    public function __construct() {
        $this->_postBuilder = new FPCWordPress_PostBuilder();
        $this->_userBuilder = new FPCWordPress_UserBuilder();
        $this->_categoryBuilder = new FPCWordPress_CategoryBuilder();
    }

    function findCategory($categoryId)
    {
        $category = get_category($categoryId);
        return $this->_categoryBuilder->transform($category);
    }

    function getCategory($id)
    {
        $category = $this->findCategory($id);
        if (is_null($category)) {
            throw new FPCWordPress_UnknownCategoryException($id);
        }
        return $category;
    }

    function getCategories()
    {
        $result = array();
        $categoryIds = get_all_category_ids();
        foreach($categoryIds as $categoryId) {
            $result[] = $this->findCategory($categoryId);
        }
        return $result;
    }


    /**
     * @param $id
     * @return null|FPCWordPress_User
     */
    function findUser($id)
    {
        $data = get_userdata($id);

        if ($data === false) {
            $data = null;
        }

        return $this->_userBuilder->transform($data);
    }

    /**
     * @param $id
     * @return null|FPCWordPress_User
     */
    function getUser($id)
    {
        $user = $this->findUser($id);
        if (is_null($user)) {
            throw new FPCWordPress_UnknownUserException($id);
        }
        return $user;
    }

    function findPost($id)
    {
        // TODO: Implement findPost() method.
    }

    function getPost($id)
    {
        $post = $this->findPost($id);
        if (is_null($post)) {
            throw new FPCWordPress_UnknownPostException($id);
        }
        return $post;
    }

    function findPosts($arguments)
    {
        $posts = get_posts($arguments);
        return $this->_postBuilder->transformAll($posts);
    }


}
