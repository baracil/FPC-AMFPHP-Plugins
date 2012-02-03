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
 */

require_once dirname(__FILE__)."/ClassLoader.php";

/**
 * The main class of the ServiceAccess plugin.
 *
 * <b>Overview</b>
 * 
 * ServiceAccess is an AMFPHP plugin that manages security access to services easily.
 * For instance with a minimal configuration a method can be secured like this :
 *
 * <code>
 *   /**
 *    * @rolesAllowed USER_WRITE, GROUP_WRITE
 *    *
 *    * This function is allowed to users that have the role
 *    * USER_WRITE or GROUP_WRITE.
 *    {@*}
 *   public function myFunction() {
 *      ...
 *   }
 * </code>
 *
 * If the access is granted, the method is called, otherwise a {@link ServiceAccessException}
 * is thrown.
 *
 * <b>Configuration</b>
 * 
 * The plugins has two configuration parameters all optional. Below is the list of the parameters and a description of
 * their value (in parentheses, the interface the parameter value must implement) :
 *
 * <ul>
 *  <li> <i>voterProvider</i> ({@link FPC_IServiceAccessVoterProvider}) : The voter provider used by the plugin
 * to get the voters associated to a method. By default, the plugin uses {@link FPC_DefaultVoterProvider}.</li>
 *  <li> <i>userProvider</i> ({@link FPC_IServiceAccessUser}) : The user provider used by the plugin to get the
 * login and the roles of the currently authenticated user. By default, the plugin is configured to use the results
 * of the FPCAuthentication plugin with {@link FPC_FPCAuthenticationUser}.</li>
 * </ul>
 *
 * The plugins comes with two user providers and several voter providers. See the documentation of each of them for more detail :
 *
 * <i>User providers</i>
 * <ul>
 * <li> {@link FPC_SessionServiceAccessUser}</li>
 * <li> {@link FPC_FPCAuthenticationUser}</li>
 * </ul>
 *
 * <i>Voter providers</i>
 * <ul>
 * <li> {@link FPC_DefaultVoterProvider} </li>
 * <li> {@link FPC_ReflectionRolesSAVoterProvider} </li>
 * <li> {@link FPC_MethodRolesSAVoterProvider} </li>
 * <li> {@link FPC_ReflectionMethodSAVoterProvider} </li>
 * <li> {@link FPC_CurrentUserLoginSAVoterProvider} </li>
 * <li> {@link ComitySAVoterProvider.php} </li>
 * <li> {@link FPC_CachedSAVoterProvider} </li>
 * <li> {@link FPC_ProxySAVoterProvider} </li>
 * </ul>
 *
 * <b> Behaviour of the default voter provider {@link FPC_DefaultVoterProvider}</b>
 *
 * By default, the plugin uses annotations in the documentation of a method (the comments of the form /** ... {@*})
 * to secure a method. The plugin handles 4 annotations that can have parameters. Below is the syntax of each of them :
 *
 * <ul>
 * <li> <b>@rolesAllowed ROLE[,ROLE]*</b> : the access is granted if the current user has at least one of the listed roles ;
 *<br/></li>
 *
 * <li> <b>@isCurrentUserLogin [index[,loginProperty]]</b> : the access is granted if the property
 *               named 'loginProperty' of the 'index'th parameter of the secured method
 *               is equal to the login of the current user. By default, index=0 and
 *               loginProperty='login'. If 'loginProperty' is set to 'null' then the
 *          	'index'th parameter is directly compared to the login of the current
 *               user ;
 *</li>
 *
 * <li> <b>@checkMethod checkMethod</b> : the access is granted if the method named 'checkMethod'
 *               returns 'true'. The signature of the 'checkMethod' method must be :
 *<code>
 *              function myCheckMethod($serviceName,
 *                                     $methodName,
 *                                     $parameters,
 *                                     FPC_IServiceAccessUser $user) {...}
 *</code>
 *              where $serviceName, $methodName are the names of the service and
 *              the secured method, $parameters the parameters of the secured method
 *              as an array and $user the current user information ;
 *</li>
 *
 *<li> <b>@comityMode (ALL|VETO|MAJORITY)</b> : if a mixed of the previous annotations is found
 *              in the method documentation, this annotation defines how the results
 *              of each of them must be combined to finally grant access. With ALL,
 *              all must grant access, with VETO, only one must, with MAJORITY, the
 *              majority must grant access (rarely used). By default (if the @comityMode
 *              annotation is not found) VETO mode is used. For instance :
 *
 * <code>
 *          /**
 *           * the next annotation can be removed since it is the default behavior
 *           * @comityMode VETO
 *           * @rolesAllowed USER_WRITE, GROUP_READ_ONLY
 *           * @isCurrentUserLogin 1, login
 *           * @checkMethod complexCheck
 *           *
 *           * The access is granted if
 *           * the current user has the role USER_WRITE or GROUP_READ_ONLY
 *           * OR
 *           * the value of $userInfo->login is equal to the current user login
 *           * OR
 *           * the method 'complexCheck' returns true
 *           {@*}
 *          public function myMethod1($dataInfo, $userInfo) {...}
 *
 *         /**
 *           * @comityMode ALL
 *           * @rolesAllowed USER_WRITE, GROUP_READ_ONLY
 *           * @isCurrentUserLogin 1, login
 *           * @checkMethod complexCheck
 *           *
 *           * The access is granted if
 *           * the current user has the role USER_WRITE or GROUP_READ_ONLY
 *           * AND
 *           * the value of $userInfo->login is equal to the current user login
 *           * AND
 *           * the method 'complexCheck' returns true
 *           {@*}
 *          public function myMethod2($dataInfo, $userInfo) {...}
 * </code>
 * </ul>
 *
 * If an annotation is repeated in the method documentation, then only the first one
 * is used except for the @rolesAllowed annotation for which the listed roles are merged, i.e. :
 * <code>
 *  /**
 *   * @rolesAllowed ROLE1, ROLE2
 *   * @rolesAllowed ROLE3, ROLE4
 *   {@*}
 * </code>
 *  is equivalent to
 * <code>
 *  /**
 *   * @rolesAllowed ROLE1, ROLE2, ROLE3, ROLE4
 *   {@*}
 * </code>
 *
 *
 * <b>How it works and how to customize it</b>
 *
 * Here is a quick description of how it works. I will not detailed much since the default
 * behavior should be enough. But if you want to add your own annotations for instance,
 * then you should read the following and also the documentation in the code.
 *
 * The plugin uses voters to grant or denied access to a method. A voter must implements
 * the interface 'FPC_IServiceAccessVoter' that defines one function : 'accessGranted'.
 *
 * Before calling a secured method, the plugin gets the voter associated with
 * the secured method and it calls its function 'accessGranted'.
 * If the function returns true, access to the secured method is granted, otherwise access is denied.
 *
 * Now, the plugin uses a voter-provider to get the voter of a secured method.
 * A voter-provider must implements the interface 'FPC_IServiceAccessVoterProvider'
 * that defines one function : 'getVoter'. The plugin calls the 'getVoter' function
 * with the information about the service and secured method. The function 'getVoter'
 * returns a 'voter' that then will be used to grant or to deny access
 * to the secured method as described previously.
 *
 * By default, the voter-provider used by the plugin handles annotations
 * in the method documentation has described in the previous section
 * (see the class {@link FPC_DefaultVoterProvider}). You can change the default
 * voter-provider with the configuration parameter 'voterProvider'.
 *
 * If you want to create a voter-provider that provides an existing voter
 * you must implements the interface '{@link FPC_IServiceAccessVoterProvider}'.
 * On the other hand if you want to create your own voter, then you must
 * implements the interface '{@link FPC_IServiceAccessVoter}' and also the voter-provider
 * for this voter.
 *
 *
 * <b>Example of creation of a voter-provider</b>
 *
 * Let's create a voter-provider that will provide a FPC_MethodSAVoter
 * that will use the method with the name of the secured method append
 * with 'Check'. For instance, if the called method is 'getUser' then
 * the method called by the voter will be 'getUserCheck'.
 *
 * So let's implements the '{@link FPC_IServiceAccessVoterProvider'},
 * here is the skeleton of a voter-provider
 *
 * <code>
 *  <?php
 *
 *  class FPC_AppendMethodSAVoterProvider implements FPC_IServiceAccessVoterProvider {
 *
 *      /**
 *       * @param $serviceObject the instance of the service
 *       * @param $serviceName the service name
 *       * @param $methodName the secured method name
 *       * @return FPC_IServiceAccessVoter the voter used to determine the access
 *       *         right to the secured method of the given service.
 *       {@*}
 *       function getVoter($serviceObject, $serviceName, $methodName)
 *       {
 *           // TODO: Implement getVoter() method.
 *       }
 *
 *   }
 *  ?>
 * </code>
 *
 * Let's have a look at the FPC_MethodSAVoter constructor :
 *
 * <code>
 *   /**
 *    * @param String $serviceName the name of the service
 *    * @param String $methodName the name of the secured method
 *    * @param String $checkMethodName the name of the method
 *               that will be called to determine the access right
 *    * @param bool $grantedIfNoMethod the value return
 *              if the checkMethod is not found (false by default)
 *    {@*}
 *   public function __construct($serviceName, $methodName,
 *                               $checkMethodName, $grantedIfNoMethod = false) {...}
 * </code>
 *
 * We have the first two parameters from the provider and we will set to 'true'
 * the last parameter (access will be granted if we do not defined the checking method).
 * So we need the value of $checkMethodName. Let's implements 'getVoter()' :
 *
 * <code>
 *  <?php
 *
 *   class FPC_AppendMethodSAVoterProvider implements FPC_IServiceAccessVoterProvider {
 *
 *    /**
 *     * @param $serviceObject the instance of the service
 *     * @param $serviceName the service name
 *     * @param $methodName the secured method name
 *     * @return FPC_IServiceAccessVoter the voter used to determine
 *     *            the access right to the secured method of the given service.
 *     {@*}
 *    function getVoter($serviceObject, $serviceName, $methodName)
 *    {
 *      return new FPC_MethodSAVoter($serviceName, $methodName, "${$methodName}Check", true);
 *    }
 *   }
 *   ?>
 * </code>
 *
 * That's it. Now, to use this voter-provider with the plugin just set the configuration property 'voterProvider'
 * with this voter-provider :
 *
 * <code>
 *   $serviceAccessConfig['voterProvider'] = new FPC_AppendMethodSAVoterProvider();
 * </code>
 *
 * By doing so you will only be able to use this mechanism to check access to secured method.
 * If you also want to use the @rolesAllowed annotation or other provider you need
 * to use a FPC_ComitySAVoterProvider like this :
 *
 * <code>
 *   $serviceAccessConfig['voterProvider'] = new FPC_ComitySAVoterProvider(
 *               array(
 *                    new FPC_ReflectionRolesSAVoterProvider(),
 *                    new FPC_AppendMethodSAVoterProvider(),
 *           ));
 * </code>
 *
 * Moreover, after debugging the provider, it might be useful in production to cache
 * the voters. You can use a '{@link FPC_CachedSAVoterProvider}' like this :
 *
 * <code>
 *   $notCachedProvider = new FPC_ComitySAVoterProvider(
 *               array(
 *                    new FPC_ReflectionRolesSAVoterProvider(),
 *                    new FPC_AppendMethodSAVoterProvider(),
 *           ));
 *   $serviceAccessConfig['voterProvider'] = new FPC_CachedSAVoterProvider($notCachedProvider);
 * </code>
 *
 * <b>Example of creation of a voter and its provider</b>
 *
 * Let's create a voter and a provider that will allow something like this :
 *
 * <code>
 *   /**
 *    * @rolesDenied USER_WRITE, GROUP_WRITE
 *    *
 *    * users with the role USER_WRITE or GROUP_WILL are not allowed to call this method.
 *    {@*}
 *   public function securedMethod() {...}
 * </code>
 *
 * So, first let's create a voter skeleton :
 *
 * <code>
 *   <?php
 *
 *   class DeniedRolesSAVoter implements FPC_IServiceAccessVoter {
 *
 *       /**
 *        * @param FPC_IServiceAccessUser $user the current user information
 *        * @param $serviceObject the instance of the service
 *        * @param array $parameters the parameters that will be passed to the secured method
 *        * @return bool true if access is granted, false otherwise
 *        {@*}
 *       function accessGranted(FPC_IServiceAccessUser $user,
 *                              $serviceObject, array $parameters)
 *       {
 *           // TODO: Implement accessGranted() method.
 *       }
 *
 *   }
 *   ?>
 * </code>
 *
 * We need an array of denied roles. It will be provided by the voter-provider since it depends
 * on the secured method only. So this will be a property of the voter set at construction :
 *
 * <code>
 *   <?php
 *
 *   class DeniedRolesSAVoter implements FPC_IServiceAccessVoter {
 *
 *       private $_deniedRoles;
 *
 *       public function __construct(array $deniedRoles) {
 *           //we could create an associative array to avoid the double loop
 *           //in the accessGranted method but let's keep things simple
 *           $this->_deniedRoles = $deniedRoles;
 *       }
 *
 *       ...
 *
 *   }
 *   ?>
 * </code>
 *
 * Not let's implements the accessGranted method :
 *
 * <code>
 *   <?php
 *
 *   class DeniedRolesSAVoter implements FPC_IServiceAccessVoter {
 *
 *       ...
 *
 *       function accessGranted(FPC_IServiceAccessUser $user,
 *                              $serviceObject, array $parameters)
 *       {
 *           $userRoles = $user->getRoles();
 *           foreach ($userRoles as $userRole) {
 *               foreach ($this->_deniedRoles as $deniedRole) {
 *                   if ($userRole === $deniedRole) {
 *                       return false;
 *                   }
 *               }
 *           }
 *           return true;
 *       }
 *   }
 *  ?>
 * </code>
 *
 * Ok, we are done with the voter, let's create the provider.
 * Since this is a provider that will use annotations, it might be useful to extends
 * the abstract class '{@link FPC_AbstractReflectionSAVoterProvider}' :
 *
 * <code>
 *   <?php
 *
 *   class DeniedRolesSAVoterProvider extends FPC_AbstractReflectionSAVoterProvider {
 *
 *       public function __construct() {
 *           parent::__construct("rolesDenied");
 *       }
 *
 *       /**
 *        * @param $serviceObject the object representing the service
 *        * @param $serviceName the name of the service
 *        * @param $methodName the name of the method
 *        * @param array $values list of values after the $tag in the method comment
 *        * @return FPC_IServiceAccessVoter a voter for this service/method
 *        {@*}
 *       protected function handleValues($serviceObject, $serviceName, $methodName, array $values)
 *       {
 *           // TODO: Implement handleValues() method.
 *       }
 *   }
 * </code>
 *
 * At construction we have to define the annotation tag, {@link FPC_AbstractReflectionSAVoterProvider}
 * will then do all the work (parsing the documentation) and then call the abstract method 'handleValues'.
 * The parameters $values of the 'handleValues' method are the roles listed after the @rolesDenied annotation.
 * So let's finish the provider :
 *
 * <code>
 *   <?php
 *
 *   class DeniedRolesSAVoterProvider extends FPC_AbstractReflectionSAVoterProvider {
 *
 *       public function __construct() {
 *           parent::__construct("rolesDenied");
 *       }
 *
 *       /**
 *        * @param $serviceObject the object representing the service
 *        * @param $serviceName the name of the service
 *        * @param $methodName the name of the method
 *        * @param array $values list of values after the $tag in the method comment
 *        * @return FPC_IServiceAccessVoter a voter for this service/method
 *        {@*}
 *       protected function handleValues($serviceObject, $serviceName, $methodName, array $values)
 *       {
 *           if (count($values) > 0) {
 *               return new DeniedRolesSAVoter($values);
 *           }
 *
 *           //no denied roles, so return a voter that always grants access.
 *           return new FPC_AlloverSAVoter();
 *       }
 *   }
 *   ?>
 * </code>
 * 
 * And that's it.
 * 
 * @package FPC_AMFPHP_Plugins_ServiceAccess
 * @author Bastien Aracil
 */
class ServiceAccess
{

    /**
     * The key used to get from the configuration the {@link FPC_IServiceAccessVoterProvider} used by the plugin
     */
    const CONFIG_VOTER_PROVIDER_KEY = "voterProvider";

    /**
     * The key used to get from the configuration the {@link FPC_IServiceAccessUser} used by the plugin
     */
    const CONFIG_USER_KEY = "userProvider";

    /**
     * @var FPC_IServiceAccessVoterProvider the voter provider used by the plugin
     */
    private $_voterProvider;

    /**
     * @var FPC_IServiceAccessUser the user provider used by the plugin
     */
    private $_user;


    /**
     * Constructor of the plugin
     *
     * @param array $config optional key/value pairs in an associative array. Used to override default configuration values.
     */
    public function  __construct(array $config = null)
    {
        $filterManager = Amfphp_Core_FilterManager::getInstance();
        $filterManager->addFilter(Amfphp_Core_Common_ServiceRouter::FILTER_SERVICE_OBJECT, $this, "filterServiceObject");

        $this->_user = null;
        $this->_voterProvider = null;

        if ($config) {
            if (isset($config[self::CONFIG_VOTER_PROVIDER_KEY])) {
                $this->_voterProvider = $config[self::CONFIG_VOTER_PROVIDER_KEY];
            }

            if (isset($config[self::CONFIG_USER_KEY])) {
                $this->_user = $config[self::CONFIG_USER_KEY];
            }
        }

        /* set default provider */
        if(is_null($this->_voterProvider)) {
            $this->_voterProvider = new FPC_DefaultVoterProvider();
        }

        if (is_null($this->_user)) {
            $this->_user = new FPC_FPCAuthenticationUser();
        }


    }

    /**
     * Plugin hook. Called when the service object is created, just before the method call.
     *
     * From the $serviceName and the $methodName values, the plugin uses the
     * {@link FPC_IServiceAccessVoterProvider} provided at configuration to find
     * the appropriate voter (see {@link FPC_IServiceAccessVoter}). This voter is
     * then used to determine the access status of the user. if the access is denied
     * a {@link ServiceAccessException} is thrown else this method simply returns.
     *
     * @param Object $serviceObject the instance of the service names $serviceName
     * @param String $serviceName the name of the service
     * @param String $methodName the name of the method of the service
     * @param array $parameters the parameters of the called method as array
     * @return void
     */
    public function filterServiceObject($serviceObject, $serviceName, $methodName, $parameters)
    {

        $this->validateConfiguration();

        $voter = $this->_voterProvider->getVoter($serviceObject, $serviceName, $methodName);
        $user = $this->_user;

        if (is_null($voter)) {
            return;
        }

        $granted = $voter->accessGranted($user, $serviceObject, $parameters);

        if (!$granted) {
            throw new ServiceAccessException($serviceName, $methodName);
        }
    }


    private function validateConfiguration() {
        if (!($this->_user instanceof FPC_IServiceAccessUser)) {
            throw new Exception("Invalid configuration for plugin ServiceAccess : userProvider must implement FPC_IServiceAccessUser ");
        }

        if (!($this->_voterProvider instanceof FPC_IServiceAccessVoterProvider)) {
            throw new Exception("Invalid configuration for plugin ServiceAccess : voterProvider must implement FPC_IServiceAccessVoterProvider ");
        }
    }
}
