<?php
/**
 * Copyright: IMPER.INFO Adrian Szuszkiewicz
 * Date: 21.09.2019
 * Time: 17:16
 */

namespace Imper86\AllegroApiBundle\Factory;


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

    public function __construct(TokenBundleService $tokenBundleService, AllegroClientInterface $allegroClient)
    {
        $this->tokenBundleService = $tokenBundleService;
        $this->allegroClient = $allegroClient;
    }

    public function build(AllegroAccount $account, int $maxRequestRetries = 3): AllegroSimpleClientInterface
    {
        return new AllegroSimpleClient($account, $maxRequestRetries, $this->tokenBundleService, $this->allegroClient);
    }
}
