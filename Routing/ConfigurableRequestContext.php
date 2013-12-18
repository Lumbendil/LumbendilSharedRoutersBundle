<?php


namespace Lumbendil\SharedRoutersBundle\Routing;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\RequestContext;

class ConfigurableRequestContext extends RequestContext
{
    /**
     * @var RequestContextConfigurator
     */
    private $requestContextConfigurator;
    /**
     * @var array
     */
    private $requestContextConfiguratorOptions;

    public function setConfigurator(RequestContextConfigurator $configurator, array $options = array())
    {
        $this->requestContextConfigurator = $configurator;
        $this->requestContextConfiguratorOptions = $options;
    }

    public function fromRequest(Request $request = null)
    {
        if ($request) {
            parent::fromRequest($request);

            if ($this->requestContextConfigurator) {
                $this->requestContextConfigurator->configure(
                    $this, $request, $this->requestContextConfiguratorOptions
                );
            }
        }
    }
}