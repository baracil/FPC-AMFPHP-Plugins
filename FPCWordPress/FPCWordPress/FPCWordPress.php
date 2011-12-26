<?php
/**
 *   @copyright Copyright (c) 2011, Bastien Aracil
 *   All rights reserved.
 *   New BSD license. See http://en.wikipedia.org/wiki/Bsd_license
 *
 *   Redistribution and use in source and binary forms, with or without
 *   modification, are permitted provided that the following conditions are met:
 *      * Redistributions of source code must retain the above copyright
 *        notice, this list of conditions and the following disclaimer.
 *      * Redistributions in binary form must reproduce the above copyright
 *        notice, this list of conditions and the following disclaimer in the
 *        documentation and/or other materials provided with the distribution.
 *      * The name of Bastien Aracil may not be used to endorse or promote products
 *        derived from this software without specific prior written permission.
 *
 *   THIS SOFTWARE IS PROVIDED BY THE COPYRIGHT HOLDERS AND CONTRIBUTORS "AS IS" AND
 *   ANY EXPRESS OR IMPLIED WARRANTIES, INCLUDING, BUT NOT LIMITED TO, THE IMPLIED
 *   WARRANTIES OF MERCHANTABILITY AND FITNESS FOR A PARTICULAR PURPOSE ARE
 *   DISCLAIMED. IN NO EVENT SHALL BASTIEN ARACIL BE LIABLE FOR ANY
 *   DIRECT, INDIRECT, INCIDENTAL, SPECIAL, EXEMPLARY, OR CONSEQUENTIAL DAMAGES
 *   (INCLUDING, BUT NOT LIMITED TO, PROCUREMENT OF SUBSTITUTE GOODS OR SERVICES;
 *   LOSS OF USE, DATA, OR PROFITS; OR BUSINESS INTERRUPTION) HOWEVER CAUSED AND
 *   ON ANY THEORY OF LIABILITY, WHETHER IN CONTRACT, STRICT LIABILITY, OR TORT
 *   (INCLUDING NEGLIGENCE OR OTHERWISE) ARISING IN ANY WAY OUT OF THE USE OF THIS
 *   SOFTWARE, EVEN IF ADVISED OF THE POSSIBILITY OF SUCH DAMAGE.
 *
 *   @package FPC_AMFPHP_Plugins_FPCWordPress
 */
require_once "ClassLoader.php";

/**
 * To configure the plugin, set the 'path' property of the plugin configuration array to the root path
 * of the WordPress installation (the directory that contains the file 'wp-upload.php').
 *
 */
class FPCWordPress {

    /**
     * Name of the emulated service
     */
    const EMULATED_SERVICE_NAME = "fpcWordPress";

    private $_wordPressPath;

    /**
     * @var FPCWordPress_IWordPressDAO
     */
    private $_wordPressDAO;

    private $wordPressClassInfo;

    public function __construct($config = null) {
        //hook the plugin
        $filterManager = Amfphp_Core_FilterManager::getInstance();
        $filterManager->addFilter(Amfphp_Core_Gateway::FILTER_SERVICE_NAMES_2_CLASS_FIND_INFO, $this, "filterServiceNames2ClassFindInfo");
        $filterManager->addFilter(Amfphp_Core_Common_ServiceRouter::FILTER_SERVICE_OBJECT, $this, "filterServiceObject");

        $this->wordPressClassInfo = new Amfphp_Core_Common_ClassFindInfo(dirname(__FILE__)."/WordPressService.php","FPCWordPress_WordPressService");


        $this->_wordPressPath = null;
        if ($config) {
            if (isset($config['path'])) {
                $path = $config['path'];
                if (file_exists($path) . "/wp-load.php") {
                    $this->_wordPressPath = $path;
                }
            }
        }

        if (is_null($this->_wordPressPath)) {
            throw new Amfphp_Core_Exception("Invalid FPCWordPress configuration");
        }

        $this->_wordPressDAO = new FPCWordPress_WordPressDAO();
    }

    /**
     * Hook point to save the classFindInfo of the {@link FPCWordPress_WordPressService} to override
     * the calls to the fpcAuthentication service
     *
     * @param $serviceNames2ClassFindInfo
     * @return array
     */
    public function filterServiceNames2ClassFindInfo($serviceNames2ClassFindInfo) {
        $serviceNames2ClassFindInfo[self::EMULATED_SERVICE_NAME] = $this->wordPressClassInfo;
        return $serviceNames2ClassFindInfo;
    }

    /**
     * @return
     */
    public function filterServiceObject($serviceObject, $serviceName, $methodName, $parameters) {

        if ($serviceName == self::EMULATED_SERVICE_NAME) {
            $this->setConfiguration($serviceObject);
        }
        return $serviceObject;
    }


    private function setConfiguration(FPCWordPress_WordPressService $service) {
        $service->init($this->_wordPressPath,$this->_wordPressDAO);
    }
}
