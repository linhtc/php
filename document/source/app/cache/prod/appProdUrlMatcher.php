<?php

use Symfony\Component\Routing\Exception\MethodNotAllowedException;
use Symfony\Component\Routing\Exception\ResourceNotFoundException;
use Symfony\Component\Routing\RequestContext;

/**
 * appProdUrlMatcher.
 *
 * This class has been auto-generated
 * by the Symfony Routing Component.
 */
class appProdUrlMatcher extends Symfony\Bundle\FrameworkBundle\Routing\RedirectableUrlMatcher
{
    /**
     * Constructor.
     */
    public function __construct(RequestContext $context)
    {
        $this->context = $context;
    }

    public function match($pathinfo)
    {
        $allow = array();
        $pathinfo = rawurldecode($pathinfo);
        $context = $this->context;
        $request = $this->request;

        // homepage
        if ($pathinfo === '/app/example') {
            return array (  '_controller' => 'AppBundle\\Controller\\DefaultController::indexAction',  '_route' => 'homepage',);
        }

        // _welcome
        if (rtrim($pathinfo, '/') === '') {
            if (substr($pathinfo, -1) !== '/') {
                return $this->redirect($pathinfo.'/', '_welcome');
            }

            return array (  '_controller' => 'AppBundle\\Controller\\DefaultController::indexAction',  '_route' => '_welcome',);
        }

        if (0 === strpos($pathinfo, '/admin')) {
            // sc_admin_dashboard
            if ($pathinfo === '/admin/dashboard.html') {
                return array (  '_controller' => 'SmartCafe\\AdminBundle\\Controller\\DashboardController::viewAction',  '_route' => 'sc_admin_dashboard',);
            }

            if (0 === strpos($pathinfo, '/admin/customer')) {
                // sc_admin_customer
                if ($pathinfo === '/admin/customer/view.html') {
                    return array (  '_controller' => 'AdminBundle:Customer:view',  '_route' => 'sc_admin_customer',);
                }

                // sc_admin_customer_get_list
                if ($pathinfo === '/admin/customer/get-list.html') {
                    return array (  '_controller' => 'AdminBundle:Customer:getList',  '_route' => 'sc_admin_customer_get_list',);
                }

            }

            // sc_admin_menu
            if ($pathinfo === '/admin/menu/menu.html') {
                return array (  '_controller' => 'AdminBundle:Menu:view',  '_route' => 'sc_admin_menu',);
            }

        }

        if (0 === strpos($pathinfo, '/lo')) {
            if (0 === strpos($pathinfo, '/log')) {
                if (0 === strpos($pathinfo, '/login')) {
                    // sc_login
                    if ($pathinfo === '/login.html') {
                        return array (  '_controller' => 'SmartCafe\\AdminBundle\\Controller\\LoginController::viewAction',  '_route' => 'sc_login',);
                    }

                    // sc_login_authentication
                    if ($pathinfo === '/login-authentication') {
                        return array (  '_controller' => 'SmartCafe\\AdminBundle\\Controller\\LoginController::authenticationAction',  '_route' => 'sc_login_authentication',);
                    }

                }

                // sc_logout
                if ($pathinfo === '/logout.html') {
                    return array (  '_controller' => 'SmartCafe\\AdminBundle\\Controller\\LoginController::logoutAction',  '_route' => 'sc_logout',);
                }

            }

            // sc_lockscreen
            if ($pathinfo === '/lockscreen.html') {
                return array (  '_controller' => 'SmartCafe\\AdminBundle\\Controller\\LoginController::lockScreenAction',  '_route' => 'sc_lockscreen',);
            }

        }

        throw 0 < count($allow) ? new MethodNotAllowedException(array_unique($allow)) : new ResourceNotFoundException();
    }
}
