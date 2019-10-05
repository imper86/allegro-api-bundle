<?php
/**
 * Author: Adrian Szuszkiewicz <me@imper.info>
 * Github: https://github.com/imper86
 * Date: 04.10.2019
 * Time: 14:26
 */

namespace Imper86\AllegroApiBundle\Service;


use Imper86\AllegroApiBundle\Entity\AllegroAccountInterface;
use Imper86\AllegroApiBundle\Manager\AllegroClientManagerInterface;
use Imper86\AllegroRestApiSdk\Model\SoapWsdl\DoGetUserLoginRequest;
use Imper86\AllegroRestApiSdk\Model\SoapWsdl\doGetUserLoginResponse;

/**
 * Class UserLoginFetchService
 * @package Imper86\AllegroApiBundle\Service
 */
class UserLoginFetchService implements UserLoginFetchServiceInterface
{
    /**
     * @var AllegroClientManagerInterface
     */
    private $clientManager;

    /**
     * @param AllegroClientManagerInterface $clientManager
     */
    public function __construct(AllegroClientManagerInterface $clientManager)
    {
        $this->clientManager = $clientManager;
    }

    /**
     * @param AllegroAccountInterface $account
     * @return string
     */
    public function fetch(AllegroAccountInterface $account): string
    {
        $client = $this->clientManager->get();
        $response = $client->soapRequest(new DoGetUserLoginRequest(1, $account->getId()));

        if ($response instanceof doGetUserLoginResponse) {
            return $response->getUserLogin();
        }

        throw new \RuntimeException("Invalid soap api response");
    }

}
