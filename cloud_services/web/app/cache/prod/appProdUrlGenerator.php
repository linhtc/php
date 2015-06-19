<?php

use Symfony\Component\Routing\RequestContext;
use Symfony\Component\Routing\Exception\RouteNotFoundException;
use Psr\Log\LoggerInterface;

/**
 * appProdUrlGenerator
 *
 * This class has been auto-generated
 * by the Symfony Routing Component.
 */
class appProdUrlGenerator extends Symfony\Component\Routing\Generator\UrlGenerator
{
    private static $declaredRoutes = array(
        'homepage' => array (  0 =>   array (  ),  1 =>   array (    '_controller' => 'AppBundle\\Controller\\DefaultController::indexAction',  ),  2 =>   array (  ),  3 =>   array (    0 =>     array (      0 => 'text',      1 => '/app/example',    ),  ),  4 =>   array (  ),  5 =>   array (  ),),
        '_welcome' => array (  0 =>   array (  ),  1 =>   array (    '_controller' => 'AppBundle\\Controller\\DefaultController::indexAction',  ),  2 =>   array (  ),  3 =>   array (    0 =>     array (      0 => 'text',      1 => '/',    ),  ),  4 =>   array (  ),  5 =>   array (  ),),
        'sc_admin_dashboard' => array (  0 =>   array (  ),  1 =>   array (    '_controller' => 'SmartCafe\\AdminBundle\\Controller\\DashboardController::viewAction',  ),  2 =>   array (  ),  3 =>   array (    0 =>     array (      0 => 'text',      1 => '/admin/dashboard.html',    ),  ),  4 =>   array (  ),  5 =>   array (  ),),
        'sc_admin_customer' => array (  0 =>   array (  ),  1 =>   array (    '_controller' => 'AdminBundle:Customer:view',  ),  2 =>   array (  ),  3 =>   array (    0 =>     array (      0 => 'text',      1 => '/admin/customer/view.html',    ),  ),  4 =>   array (  ),  5 =>   array (  ),),
        'sc_admin_customer_get_list' => array (  0 =>   array (  ),  1 =>   array (    '_controller' => 'AdminBundle:Customer:getList',  ),  2 =>   array (  ),  3 =>   array (    0 =>     array (      0 => 'text',      1 => '/admin/customer/get-list.html',    ),  ),  4 =>   array (  ),  5 =>   array (  ),),
        'sc_admin_menu' => array (  0 =>   array (  ),  1 =>   array (    '_controller' => 'AdminBundle:Menu:view',  ),  2 =>   array (  ),  3 =>   array (    0 =>     array (      0 => 'text',      1 => '/admin/menu/menu.html',    ),  ),  4 =>   array (  ),  5 =>   array (  ),),
        'sc_login' => array (  0 =>   array (  ),  1 =>   array (    '_controller' => 'SmartCafe\\AdminBundle\\Controller\\LoginController::viewAction',  ),  2 =>   array (  ),  3 =>   array (    0 =>     array (      0 => 'text',      1 => '/login.html',    ),  ),  4 =>   array (  ),  5 =>   array (  ),),
        'sc_login_authentication' => array (  0 =>   array (  ),  1 =>   array (    '_controller' => 'SmartCafe\\AdminBundle\\Controller\\LoginController::authenticationAction',  ),  2 =>   array (  ),  3 =>   array (    0 =>     array (      0 => 'text',      1 => '/login-authentication',    ),  ),  4 =>   array (  ),  5 =>   array (  ),),
        'sc_logout' => array (  0 =>   array (  ),  1 =>   array (    '_controller' => 'SmartCafe\\AdminBundle\\Controller\\LoginController::logoutAction',  ),  2 =>   array (  ),  3 =>   array (    0 =>     array (      0 => 'text',      1 => '/logout.html',    ),  ),  4 =>   array (  ),  5 =>   array (  ),),
        'sc_lockscreen' => array (  0 =>   array (  ),  1 =>   array (    '_controller' => 'SmartCafe\\AdminBundle\\Controller\\LoginController::lockScreenAction',  ),  2 =>   array (  ),  3 =>   array (    0 =>     array (      0 => 'text',      1 => '/lockscreen.html',    ),  ),  4 =>   array (  ),  5 =>   array (  ),),
    );

    /**
     * Constructor.
     */
    public function __construct(RequestContext $context, LoggerInterface $logger = null)
    {
        $this->context = $context;
        $this->logger = $logger;
    }

    public function generate($name, $parameters = array(), $referenceType = self::ABSOLUTE_PATH)
    {
        if (!isset(self::$declaredRoutes[$name])) {
            throw new RouteNotFoundException(sprintf('Unable to generate a URL for the named route "%s" as such route does not exist.', $name));
        }

        list($variables, $defaults, $requirements, $tokens, $hostTokens, $requiredSchemes) = self::$declaredRoutes[$name];

        return $this->doGenerate($variables, $defaults, $requirements, $tokens, $parameters, $name, $referenceType, $hostTokens, $requiredSchemes);
    }
}
