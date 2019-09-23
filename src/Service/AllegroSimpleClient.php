<?php
/**
 * Copyright: IMPER.INFO Adrian Szuszkiewicz
 * Date: 21.09.2019
 * Time: 17:05
 */

namespace Imper86\AllegroApiBundle\Service;


use Imper86\AllegroApiBundle\Entity\AllegroAccount;
use Imper86\AllegroRestApiSdk\AllegroClientInterface;
use Psr\Http\Message\RequestInterface;

class AllegroSimpleClient implements AllegroSimpleClientInterface
{
    /**
     * @var AllegroAccount
     */
    private $account;
    /**
     * @var TokenBundleService
     */
    private $tokenBundleService;
    /**
     * @var AllegroClientInterface
     */
    private $allegroClient;
    /**
     * @var int
     */
    private $retryCount = 0;
    /**
     * @var int
     */
    private $maxRetries;

    public function __construct(
        AllegroAccount $account,
        int $maxRetries,
        TokenBundleService $tokenBundleService,
        AllegroClientInterface $allegroClient
    )
    {
        $this->account = $account;
        $this->tokenBundleService = $tokenBundleService;
        $this->allegroClient = $allegroClient;
        $this->maxRetries = $maxRetries;
    }

    public function restRequest(RequestInterface $request): ?array
    {
        $tokenBundle = $this->tokenBundleService->fetchBundle($this->account);
        $request = $request->withHeader('Authorization', "Bearer {$tokenBundle->getAccessToken()}");
        $response = $this->allegroClient->restRequest($request);

        if ($this->retryCount < $this->maxRetries && 401 === $response->getStatusCode()) {
            $this->retryCount++;
            $this->tokenBundleService->refreshBundle($tokenBundle);

            return $this->restRequest($request);
        }

        $this->retryCount = 0;

        return json_decode((string)$response->getBody(), true);
    }

    public function soapRequest($requestObject)
    {
        try {
            $response = $this->allegroClient->soapRequest($requestObject, $this->account->getSoapSessionId());
            $this->retryCount = 0;

            return $response;
        } catch (\SoapFault $exception) {
            if (
                $this->retryCount < $this->maxRetries &&
                in_array($exception->faultcode, ['ERR_NO_SESSION', 'ERR_SESSION_EXPIRED'])
            ) {
                $this->tokenBundleService->refreshSessionId($this->account);
                $this->retryCount++;

                return $this->soapRequest($requestObject);
            }

            $this->retryCount = 0;

            throw $exception;
        }
    }
}
