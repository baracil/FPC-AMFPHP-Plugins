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
 *   @package FPC_AMFPHP_Plugins_ServiceAccess
 *   @subpackage provider
 */

/**
 *
 * Abstract class used for providers using "annotations". The class extending it must set the $tag value at construction.
 * The $tag value is the annotation name searched in the documentation. The $tag does not include the beginning "@".
 *
 * When called, the documentation of the method is searched for the $tag. If found, the values following it are sent
 * to the abstract method 'handleValues'. Values must be separated by a coma. For instance
 * <code>
 *   /**
 *    * Some function documentation
 *    * @MyTag value1, value2, value3
 *    {@*}
 *    public function aFunction() {...}
 * </code>
 *
 * By default, if multiple tags are present in the documentation only the first one is used, for instance :
 *
 * <code>
 *   /**
 *    * Some function documentation
 *    * @MyTag value1, value2, value3
 *    * @MyTag value4, value5, value6
 *    {@*}
 *    public function aFunction() {...}
 * </code>
 *
 * Only value1, value2 and value3 will be used. This behaviour can be changed
 * by setting the secondary parameter of the constructor to 'true'. Then values are merged.
 * So with the previous example, value1, value2, value3, value4, value5 and value6 will be used.
 *
 * @package FPC_AMFPHP_Plugins_ServiceAccess
 * @subpackage provider
 * @author Bastien Aracil
 */
abstract class FPC_AbstractReflectionSAVoterProvider implements FPC_IServiceAccessVoterProvider
{
    /**
     * @var String
     */
    private $_tag;

    /**
     * @var bool
     */
    private $_mergeMultiple;

    /**
     * @param $tag the annotation this provider handles without the beginning '@'
     * @param bool $mergeMultiple if true, the values of multiple occurrences of the $tag annotation will be merged.
     * if false, only the values of the first $tag annotation will be used
     */
    public function __construct($tag, $mergeMultiple = false)
    {
        $this->_tag = $tag;
        $this->_mergeMultiple = $mergeMultiple;
    }

    /**
     * @param $serviceObject the instance of the service
     * @param $serviceName the service name
     * @param $methodName the secured method name
     * @return FPC_IServiceAccessVoter the voter used to determine the access right to the secured method of the given service.
     */
    final public function getVoter($serviceObject, $serviceName, $methodName)
    {
        $methodReflection = new ReflectionMethod($serviceObject, $methodName);
        $comment = $methodReflection->getDocComment();

        $values = $this->extractValues($comment, $this->_tag, $this->_mergeMultiple);

        if (is_null($values)) {
            return null;
        }

        return $this->handleValues($serviceObject, $serviceName, $methodName, $values);
    }

    /**
     * @abstract
     * @param $serviceObject the object representing the service
     * @param $serviceName the name of the service
     * @param $methodName the name of the method
     * @param array $values list of values after the $tag in the method documentation
     * @return FPC_IServiceAccessVoter a voter for this service/method
     */
    abstract protected function handleValues($serviceObject, $serviceName, $methodName, array $values);

    private function extractValues($comment, $tag, $mergeMultiple) {
        if ($comment === false) {
            return null;
        }
        $regexp = "/@". $tag . "(\s+(\w+(\s*,\s*\w+)*))?/";
        preg_match_all($regexp, $comment, $matches);

        if (empty($matches[0])) {
            return null;
        }

        $result = array();
        foreach ($matches[2] as $str) {
            if (!empty($str)) {
                $values = preg_split("/[,\s]+/", $str);
                if ($values !== false) {
                    $result = array_merge($result, $values);
                }
            }
            if (!$mergeMultiple) {
                break;
            }
        }


        return $result;
    }

}
