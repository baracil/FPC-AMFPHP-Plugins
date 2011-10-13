<?php

/*
 * Copyright
 */

/**
 * User: Bastien Aracil
 * Date: 26/08/11
 *
 * A voter that grants access if a given property of a given parameters of the service is equals to the current user login.
 *
 * The voter has two parameters set at construction.
 *
 * $index : the index of the parameter in the parameters array where the login information is.
 * $loginProperty : the property name of the selected parameter that holds the login.
 *
 * The parameters array is the third parameter of the method accessGranted
 *
 * By default, $index = 0 and $loginProperty = "login"
 *
 */


require_once "IServiceAccessVoter.php";

class FPC_CurrentUserLoginSAVoter implements FPC_IServiceAccessVoter {

    private $_index;

    private $_loginProperty;

    public function __construct($index = 0, $loginProperty = "login") {
        $this->_index = $index;
        $this->_loginProperty = $loginProperty;
    }

    function accessGranted(FPC_IServiceAccessUser $user, $serviceObject, array $parameters)
    {
        $cnt = count($parameters);

        if ( $this->_index >= $cnt) {
            return false;
        }

        $parameter = $parameters[$this->_index];

        if (is_null($this->_loginProperty)) {
            $login = $parameter;
        }
        else if (!isset($parameter->{$this->_loginProperty})) {
            return false;
        }
        else {
            $login = $parameter->{$this->_loginProperty};
        }

        return $login === $user->getLogin();
    }


}
