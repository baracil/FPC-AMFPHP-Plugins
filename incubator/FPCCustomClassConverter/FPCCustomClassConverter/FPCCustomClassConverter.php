<?php
/**
 *  This file part is part of amfPHP
 *
 * LICENSE
 * 
 * Copyright (c) 2009-2011, Silex Labs
 * All rights reserved.
 * New BSD license. See http://en.wikipedia.org/wiki/Bsd_license
 *
 * Redistribution and use in source and binary forms, with or without
 * modification, are permitted provided that the following conditions are met:
 *    * Redistributions of source code must retain the above copyright
 *      notice, this list of conditions and the following disclaimer.
 *    * Redistributions in binary form must reproduce the above copyright
 *      notice, this list of conditions and the following disclaimer in the
 *      documentation and/or other materials provided with the distribution.
 *    * Neither the name of Silex Labs nor the
 *      names of its contributors may be used to endorse or promote products
 *      derived from this software without specific prior written permission.
 *
 * THIS SOFTWARE IS PROVIDED BY THE COPYRIGHT HOLDERS AND CONTRIBUTORS "AS IS" AND
 * ANY EXPRESS OR IMPLIED WARRANTIES, INCLUDING, BUT NOT LIMITED TO, THE IMPLIED
 * WARRANTIES OF MERCHANTABILITY AND FITNESS FOR A PARTICULAR PURPOSE ARE
 * DISCLAIMED. IN NO EVENT SHALL BASTIEN ARACIL BE LIABLE FOR ANY
 * DIRECT, INDIRECT, INCIDENTAL, SPECIAL, EXEMPLARY, OR CONSEQUENTIAL DAMAGES
 * (INCLUDING, BUT NOT LIMITED TO, PROCUREMENT OF SUBSTITUTE GOODS OR SERVICES;
 * LOSS OF USE, DATA, OR PROFITS; OR BUSINESS INTERRUPTION) HOWEVER CAUSED AND
 * ON ANY THEORY OF LIABILITY, WHETHER IN CONTRACT, STRICT LIABILITY, OR TORT
 * (INCLUDING NEGLIGENCE OR OTHERWISE) ARISING IN ANY WAY OUT OF THE USE OF THIS
 * SOFTWARE, EVEN IF ADVISED OF THE POSSIBILITY OF SUCH DAMAGE.
 *
 */

/**
 * Base on the AmfphpCustomClassConverter by Ariel Sommeria-Klein.
 * Add the resolution mechanism of classes and the managing of serialized properties
 *
 * Author : Bastien Aracil
 */
require_once dirname(__FILE__)."/ClassLoader.php";

class FPCCustomClassConverter implements Amfphp_Core_Common_ISerializer
{
    const CONTENT_TYPE = "application/x-amf";

    private $objectEncoding = Amfphp_Core_Amf_Constants::AMF0_ENCODING;

    const CONFIG_CLASS_RESOLVER_KEY = "classResolver";

    const CONFIG_PROPERTIES_MANAGER_KEY = "propertiesManager";

    /**
     * class resolver
     * @var FPC_IClassResolver
     */
    public $classResolver;

    /**
     * Properties manager
     * @var FPC_IPropertiesManager
     */
    public $propertiesManager;

    /**
     * constructor.
     * @param array $config optional key/value pairs in an associative array. Used to override default configuration values.
     */
    public function  __construct(array $config = null)
    {
        if ($config && isset($config[self::CONFIG_CLASS_RESOLVER_KEY])) {
            $this->classResolver = $config[self::CONFIG_CLASS_RESOLVER_KEY];
        }

        if (is_null($this->classResolver)) {
            $resolver = new FPC_SmartClassResolver();
            $resolver->addDefaultRootPath(Amfphp_ROOTPATH . "/Services/Vo/");
            $this->classResolver = $resolver;
        }


        if ($config && isset($config[self::CONFIG_PROPERTIES_MANAGER_KEY])) {
            $this->propertiesManager = $config[self::CONFIG_PROPERTIES_MANAGER_KEY];
        }

        if (is_null($this->propertiesManager)) {
            //default
            $manager = new FPC_SmartPropertiesManager();
            $this->propertiesManager = $manager;
        }


        $hookManager = Amfphp_Core_FilterManager::getInstance();
        $hookManager->addFilter(Amfphp_Core_Gateway::FILTER_DESERIALIZED_REQUEST, $this, "filterDeserializedRequest");
        $hookManager->addFilter(Amfphp_Core_Gateway::FILTER_DESERIALIZED_RESPONSE, $this, "filterDeserializedResponse");
        $hookManager->addFilter(Amfphp_Core_Gateway::FILTER_SERIALIZER, $this, "filterSerializer");

    }

    public function filterSerializer($handler, $contentType)
    {
        if (!is_null($contentType) && $contentType == self::CONTENT_TYPE) {
            return $this;
        }
    }

    /**
     * converts untyped objects to their typed counterparts. Loads the class if necessary
     * @param mixed $deserializedRequest
     * @return mixed
     */
    public function filterDeserializedRequest($deserializedRequest)
    {
        $deserializedRequest = Amfphp_Core_Amf_Util::applyFunctionToContainedObjects($deserializedRequest, array($this, "convertToTyped"));
        return $deserializedRequest;

    }

    /**
     * looks at the outgoing packet and sets the explicit type field so that the serializer sends it properly
     * @param mixed $deserializedResponse
     * @return mixed
     */
    public function filterDeserializedResponse($deserializedResponse)
    {
        $deserializedResponse = Amfphp_Core_Amf_Util::applyFunctionToContainedObjects($deserializedResponse, array($this, "markExplicitType"));
        return $deserializedResponse;

    }

    /**
     * if the object contains an explicit type marker, this method attempts to convert it to its typed counterpart
     * if the typed class is already available, then simply creates a new instance of it. If not,
     * attempts to load the file from the available service folders.
     * If then the class is still not available, the object is not converted
     * note: This is not a recursive function. Rather the recusrion is handled by Amfphp_Core_Amf_Util::applyFunctionToContainedObjects.
     * must be public so that Amfphp_Core_Amf_Util::applyFunctionToContainedObjects can call it
     * @param mixed $obj
     * @return mixed
     */
    public function convertToTyped($obj)
    {
        if (!is_object($obj)) {
            return $obj;
        }
        $explicitTypeField = Amfphp_Core_Amf_Constants::FIELD_EXPLICIT_TYPE;
        if (isset($obj->$explicitTypeField)) {
            $explicitType = $obj->$explicitTypeField;

            //Resolve classInfo from the explicitType
            $classInfo = $this->classResolver->resolve($explicitType);

            if (is_null($classInfo)) {
                return $obj;
            }

            if (!class_exists($classInfo->getClassName())) {
                require_once $classInfo->getClassFile();
            }

            $className = $classInfo->getClassName();
            $typedObj = new $className();

            //Set properties with the manager
            $this->propertiesManager->setProperties($typedObj, $obj);

            return $typedObj;
        }

        return $obj;

    }

    /**
     * sets the the explicit type marker on the object and its sub-objects. This is only done if it not already set, as in some cases
     * the service class might want to do this manually.
     * note: This is not a recursive function. Rather the recusrion is handled by Amfphp_Core_Amf_Util::applyFunctionToContainedObjects.
     * must be public so that Amfphp_Core_Amf_Util::applyFunctionToContainedObjects can call it
     *
     * @param mixed $obj
     * @return mixed
     */
    public function markExplicitType($obj)
    {
        if (!is_object($obj)) {
            return $obj;
        }
        $explicitTypeField = Amfphp_Core_Amf_Constants::FIELD_EXPLICIT_TYPE;
        $className = get_class($obj);
        if ($className != "stdClass" && !isset($obj->$explicitTypeField)) {
            $obj->$explicitTypeField = $className;
        }
        return $obj;
    }

    /**
     * Calling this executes the serialization. The return type is noted as a String, but is a binary stream. echo it to the output buffer
     * @param mixed $data the data to serialize.
     * @return String
     */
    public function serialize($data)
    {
        $data->amfVersion = $this->objectEncoding;

        $serializer = new FPC_CustomSerializer($data, $this->propertiesManager);
        return $serializer->serialize();
    }


}

?>
