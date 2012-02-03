<?php
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
 * Date: 21/07/11
 */
require_once dirname(__FILE__) . "/ClassLoader.php";

class TransactionManager {

    const CONFIG_KEY = "manager";

    private $databaseManager;

    public function __construct($config = null) {
        $filterManager = Amfphp_Core_FilterManager::getInstance();
        $filterManager->addFilter(Amfphp_Core_Gateway::FILTER_DESERIALIZED_REQUEST, $this, "filterDeserializedRequest");
        $filterManager->addFilter(Amfphp_Core_Gateway::FILTER_EXCEPTION_HANDLER, $this,    "filterExceptionHandler");
        $filterManager->addFilter(Amfphp_Core_Gateway::FILTER_DESERIALIZED_RESPONSE, $this,"filterDeserializedResponse");


        $this->databaseManager = null;
        if ($config) {
            if (isset($config[self::CONFIG_KEY])) {
                $pluginConfig = $config[self::CONFIG_KEY];
                if ($pluginConfig instanceof FPCTransactionManager_IDataBaseManager) {
                    $this->databaseManager = $pluginConfig;
                }
                else if ($pluginConfig instanceof FPCTransactionManager_ITransactionManager) {
                    $this->databaseManager = new FPCTransactionManager_RollbackableDatabaseManager($pluginConfig);
                }
            }
        }
    }

    public function filterDeserializedRequest($deserializedRequest) {
        if (!is_null($this->databaseManager)) {
            $this->databaseManager->startProcess();
        }
        return $deserializedRequest;
    }

    public function filterExceptionHandler($handler, $contentType)
    {
        if (!is_null($this->databaseManager)) {
            $this->databaseManager->handleException();
        }
        return $handler;
    }

    public function filterDeserializedResponse($deserializedResponse) {
        if (!is_null($this->databaseManager)) {
            $this->databaseManager->endProcess();
        }
        return $deserializedResponse;
    }

    private function validateConfiguration() {
        if (is_null($this->databaseManager)) {
            throw new Exception("Invalid null configuration for plugin TransactionManager");
        }
    }
}
