<?php
/**
 * Author: Adrian Szuszkiewicz <me@imper.info>
 * Github: https://github.com/imper86
 * Date: 21.09.2019
 * Time: 17:16
 */

namespace Imper86\AllegroApiBundle\Factory;


use Imper86\AllegroApiBundle\Entity\AllegroAccount;
use Imper86\AllegroApiBundle\Service\AllegroClient;
use Imper86\AllegroApiBundle\Service\TokenBundleService;
use Imper86\AllegroRestApiSdk\AllegroClientInterface;

class AllegroSimpleClientFactory
{
    /**
     * @var TokenBundleService
     */
    private $tokenBundleService;
    /**
     * @var AllegroClientInterface
     */
    private $allegroClient;
    /**
     * @var ClientCredentialsAccountFactory
     */
    private $clientCredentialsAccountFactory;
    /**
     * @var AllegroClientInterface[]
     */
    private $instances = [];

    public function __construct(
        TokenBundleService $tokenBundleService,
        AllegroClientInterface $allegroClient,
        ClientCredentialsAccountFactory $clientCredentialsAccountFactory
    )
    {
        $this->tokenBundleService = $tokenBundleService;
        $this->allegroClient = $allegroClient;
        $this->clientCredentialsAccountFactory = $clientCredentialsAccountFactory;
    }

    public function build(AllegroAccount $account, int $maxRequestRetries = 3): AllegroClientInterface
    {
        $ref = &$this->instances[$account->getId()];

        if (!isset($ref)) {
            $ref = new AllegroClient($account, $maxRequestRetries, $this->tokenBundleService, $this->allegroClient);
        }

        $ref->setMaxRetries($maxRequestRetries);

        return $ref;
    }

    public function buildForClient(int $maxRequestRetries = 3): AllegroClientInterface
    {
        return $this->build($this->clientCredentialsAccountFactory->fetchAccount(), $maxRequestRetries);
    }
}
