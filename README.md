# allegro-api-bundle
This is simple symfony4 bundle for [imper86/allegroapi](https://github.com/imper86/allegroapi)

## Installation
```sh
composer require imper86/allegro-api-bundle
```

Add your config in config/packages directory. Example config:
```yaml
imper86_allegro_api:
    sandbox: true
    client_id: '%env(ALLEGRO_CLIENT_ID)%'
    client_secret: '%env(ALLEGRO_CLIENT_SECRET)%'
    logger_service_id: yourlogger
    redirect_route: allegro_api_handle_code
```

Add bundle's routes in config/routes
```yaml
imper86_allegro_api:
    resource: '@Imper86AllegroApiBundle/Resources/config/routes.xml'
```

Add bundle to bundles.php
```php
Imper86\AllegroApiBundle\Imper86AllegroApiBundle::class => ['all' => true],
```

This bundle use doctrine/orm to persist allegro account info, and
store tokens, so please make migrations, or update schema

```sh
./bin/console make:migration
``` 

## Usage

### Authorization
Once you have your setup ready you can start auth code grant process
going to route: http(s)://your.app/allegro-api/start

You'll be then redirected to allegro.pl to confirm authorization.

After that you'll come back to redirect_route specified in config.
If you leave default value, bundle will handle response, and will
get and store your token pair.

### Using client
To get your client, inject service 
**Imper86\AllegroApiBundle\Factory\AllegroSimpleClientFactory**
and use **build** method to create simple client (AllegroSimpleClientInterface).

If you wish to use client credentials grant, skip Authorization part, and
just use **buildForClient** method in **AllegroSimpleClientFactory**.

Bundle will handle tokens, and soap sessionId's for you, so you can use
requests with **null** token.

### TokenBundleService
Inject this service if you need TokenBundleInterface object, refresh token,
or sessionId.

## Is that all?
This bundle is on very early stage, so please expect many updates
in future, because I know that many things in here should be done better.
