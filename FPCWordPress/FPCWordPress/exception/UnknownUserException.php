<?php
/**
 * User: Bastien Aracil
 * Date: 20/12/11
 */
 
class FPCWordPress_UnknownUserException extends Exception {

    var $_explicitType="FPCWordPress.exception.UnknownUserException";

    var $id;

    public function __construct($id = 0) {
        $this->id = $id;
    }
}
