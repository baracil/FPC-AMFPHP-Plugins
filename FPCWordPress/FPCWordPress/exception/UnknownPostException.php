<?php
/**
 * User: Bastien Aracil
 * Date: 21/12/11
 */
 
class FPCWordPress_UnknownPostException extends Exception {

    var $_explicitType = "FPCWordPress.exception.UnknownPostException";

    var $id;

    public function __construct($id) {
        $this->id = $id;
    }
}
