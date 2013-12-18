<?php

namespace Lumbendil\SharedRoutersBundle;

use Lumbendil\SharedRoutersBundle\DependencyInjection\LumbendilSharedRoutersExtension;
use Symfony\Component\HttpKernel\Bundle\Bundle;
use Symfony\Component\HttpKernel\KernelInterface;

class LumbendilSharedRoutersBundle extends Bundle
{
    private $kernel;

    public function __construct(KernelInterface $kernel)
    {
        $this->kernel = $kernel;
    }

    public function getContainerExtension()
    {
        if (!$this->extension) {
            $this->extension = new LumbendilSharedRoutersExtension($this->kernel);
        }

        return $this->extension;
    }
}
