<?php
/**
 * User: Bastien Aracil
 * Date: 21/12/11
 */
 
interface FPCWordPress_IWordPressDAO {

    function findCategory($categoryId);

    function getCategory($categoryId);

    function getCategories();

    function findUser($userId);

    function getUser($userId);

    function findPost($postId);

    function getPost($postId);

    function findPosts($arguments);

}
