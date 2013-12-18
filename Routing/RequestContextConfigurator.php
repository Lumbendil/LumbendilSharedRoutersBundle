<?php


namespace Lumbendil\SharedRoutersBundle\Routing;


use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\RequestContext;

interface RequestContextConfigurator
{
    /**
     * Configurates the given Request context.
     *
     * @param Request $request
     * @param array $options
     *
     * @return string The subdomain.
     */
    public function configure(RequestContext $context, Request $request, array $options = array());
}