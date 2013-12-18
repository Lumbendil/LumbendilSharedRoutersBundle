Shared Routers Bundle
====================

This bundle is meant to ease up the creation of multiple routers. The most common use case is when you need to generate routes for ApplicationA from ApplicationB, which doesn't have the required routes in it's framework router.

### Installation

Add `"lumbendil/shared-routers-bundle": "dev-master@dev"` to your composer.json (at the moment there is no released stable version).

Add the bundle to `app/AppKernel.php` (it needs to receive the `Kernel` as a constructor argument):

    public function registerBundles()
    {
        return array(
            new Lumbendil\SharedRoutersBundle\LumbendilSharedRoutersBundle($this),
        );
    }

### Basic Configuration

In order to use this bundle, you need to add the following config to your `app/config/config.yml`:

    lumbendil_shared_routers:
        routers:
            my_name:
                resource: @MyBundle/Resources/config/routing/file.yml

The name, which in this example is set to `my_name` is configurable, and so is the `resource` value. This will load the routing file specified in `resource` and define a router service, `my_name_router`.

### Contributing

Feel free to create Pull Requests and Issues with any issue you are able to find.
