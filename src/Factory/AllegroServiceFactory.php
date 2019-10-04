<?php
/**
 * Author: Adrian Szuszkiewicz <me@imper.info>
 * Github: https://github.com/imper86
 * Date: 20.09.2019
 * Time: 18:02
 */

namespace Imper86\AllegroApiBundle\Factory;


use Imper86\AllegroRestApiSdk\AllegroAuth;
use Imper86\AllegroRestApiSdk\AllegroAuthInterface;
use Imper86\AllegroRestApiSdk\AllegroClient;
use Imper86\AllegroRestApiSdk\AllegroClientInterface;
use Imper86\AllegroRestApiSdk\Model\Credentials\AppCredentials;
use Psr\Log\LoggerInterface;
use Symfony\Component\Routing\RouterInterface;

class AllegroServiceFactory implements AllegroServiceFactoryInterface
{
    /**
     * @var array
     */
    private $config;
    /**
     * @var RouterInterface
     */
    private $router;
    /**
     * @var LoggerInterface
     */
    private $logger;
    /**
     * @var AppCredentials
     */
    private $credentials;

    public function __construct(array $config, RouterInterface $router, ?LoggerInterface $logger = null)
    {
        $this->config = $config;
        $this->router = $router;
        $this->logger = $logger;
        $this->credentials = new AppCredentials(
            $this->config['client_id'],
            $this->config['client_secret'],
            $this->router->generate($this->config['redirect_route'], [], RouterInterface::ABSOLUTE_URL),
            $this->config['sandbox']
        );
    }

    public function createAuth(): AllegroAuthInterface
    {
        return new AllegroAuth($this->credentials, $this->logger);
    }

    public function createClient(): AllegroClientInterface
    {
        return new AllegroClient($this->credentials, $this->logger);
    }
}
