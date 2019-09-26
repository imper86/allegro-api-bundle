<?php
/**
 * Copyright: IMPER.INFO Adrian Szuszkiewicz
 * Date: 21.09.2019
 * Time: 17:16
 */

namespace Imper86\AllegroApiBundle\Factory;


use Doctrine\Common\Collections\ArrayCollection;
use Imper86\AllegroApiBundle\Entity\AllegroAccount;
use Imper86\AllegroApiBundle\Service\AllegroSimpleClient;
use Imper86\AllegroApiBundle\Service\AllegroSimpleClientInterface;
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
     * @var AllegroSimpleClientInterface[]
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
        $this->instances = new ArrayCollection();
    }

    public function build(AllegroAccount $account, int $maxRequestRetries = 3): AllegroSimpleClientInterface
    {
        $ref = &$this->instances[$account->getId()];

        if (!isset($ref)) {
            $ref = new AllegroSimpleClient($account, $maxRequestRetries, $this->tokenBundleService, $this->allegroClient);
        }

        $ref->setMaxRetries($maxRequestRetries);

        return $ref;
    }

    public function buildForClient(int $maxRequestRetries = 3): AllegroSimpleClientInterface
    {
        return $this->build($this->clientCredentialsAccountFactory->fetchAccount(), $maxRequestRetries);
    }
}
