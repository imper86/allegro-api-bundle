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
    
    #budle's default is "logger". You can put null here, or your logger service
    logger_service_id: yourlogger

    #you can define how many times client will try to execute request on failure
    client_default_max_retries: 3

    #your entity manager service
    entity_manager: default

    #this route is built in bundle. you can use your own if you want
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

If you want to modify the response of AllegroApiController::handleCode
please write subscriber/listener for 
[Imper86\AllegroApiBundle\Event\AuthCodeEvent](src/Event/AuthCodeEvent.php)

### Using client
To get your client, inject service 
[AllegroClientManagerInterface](src/Manager/AllegroClientManagerInterface.php)
and use **build** method to create api client 
([AllegroClientInterface](src/Service/AllegroClientInterface.php)).

If you wish to use client credentials grant, skip Authorization part, and
just use **build** method with *null* parameter instead of AllegroAccount 
object.

Bundle will handle tokens, and soap sessionId's for you, so you can use
requests without (**null**) token.

### TokenBundleServiceInterface
Inject [this](src/Service/TokenBundleServiceInterface.php) service if you 
need *TokenBundleInterface* object, refresh token, or sessionId.

## Is that all?
This bundle is on very early stage, so please expect many updates
in future, because I know that many things in here should be done better.

## Maintenance
Currently maintained version is v3.
If you use v1 or v2 you won't receive any bundle updates.

## Contributing
Any help will be very appreciated :)
