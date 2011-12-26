<?php
/**
 * User: Bastien Aracil
 * Date: 18/12/11
 */

class FPCWordPress_WordPressService {

    /**
     * @var FPCWordPress_IWordPressDAO
     */
    private $_wordPressDAO;

    /**
     * @var string
     */
    private $_rootPath;

    public function init($rootPath, $wordPressDAO) {
        $this->_rootPath = $rootPath;
        $this->_wordPressDAO = $wordPressDAO;

        $wpLoad = $this->_rootPath . "/wp-load.php";

        require_once $wpLoad;
    }

    public function getCategories() {
        return $this->_wordPressDAO->getCategories();
    }

    public function findPosts($arguments) {
        return $this->_wordPressDAO->findPosts($arguments);
    }

    public function getUser($userId) {
        return $this->_wordPressDAO->getUser($userId);
    }

    private function extractId($id) {
        if (empty($id)) {
            return -1;
        }
        if (is_array($id)) {
            $id = $id[0];
        }
        return (int)$id;
    }
}
