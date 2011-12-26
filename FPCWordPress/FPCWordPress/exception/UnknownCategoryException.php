<?php
/**
 * User: Bastien Aracil
 * Date: 21/12/11
 */
 
class FPCWordPress_UnknownCategoryException extends Exception {

    var $_explicitType = "FPCWordPress.exception.UnknownCategoryException";

    var $id;

    public function __construct($id) {
        $this->id = $id;
    }
}
