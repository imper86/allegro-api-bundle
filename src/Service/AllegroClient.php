<?php
/**
 * Author: Adrian Szuszkiewicz <me@imper.info>
 * Github: https://github.com/imper86
 * Date: 21.09.2019
 * Time: 17:05
 */

namespace Imper86\AllegroApiBundle\Service;


use Imper86\AllegroApiBundle\Entity\AllegroAccount;
use Imper86\AllegroApiBundle\Entity\AllegroAccountInterface;
use Imper86\AllegroApiBundle\Model\Response;
use Imper86\AllegroApiBundle\Model\ResponseInterface;
use Imper86\AllegroRestApiSdk\AllegroClientInterface as BaseAllegroClientInterface;
use Psr\Http\Message\RequestInterface;

class AllegroClient implements AllegroClientInterface
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
     * @var BaseAllegroClientInterface
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
        AllegroAccountInterface $account,
        int $maxRetries,
        TokenBundleServiceInterface $tokenBundleService,
        BaseAllegroClientInterface $allegroClient
    )
    {
        $this->account = $account;
        $this->tokenBundleService = $tokenBundleService;
        $this->allegroClient = $allegroClient;
        $this->maxRetries = $maxRetries;
    }

    public function restRequest(RequestInterface $request): ResponseInterface
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

        return new Response($response);
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
                $this->tokenBundleService->fetchSoapSessionId($this->account);
                $this->retryCount++;

                return $this->soapRequest($requestObject);
            }

            $this->retryCount = 0;

            throw $exception;
        }
    }

    /**
     * @return int
     */
    public function getMaxRetries(): int
    {
        return $this->maxRetries;
    }

    /**
     * @param int $maxRetries
     */
    public function setMaxRetries(int $maxRetries): void
    {
        $this->maxRetries = $maxRetries;
    }
}
