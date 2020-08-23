<?php

namespace App\Controller;

use Http\Client\HttpAsyncClient;
use Http\Promise\Promise;
use Psr\Http\Message\RequestFactoryInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class HomeController extends AbstractController
{
    /**
     * @Route("/")
     */
    public function index(HttpAsyncClient $client, RequestFactoryInterface $reqFactory): Response
    {
        $mkRequest = function () use ($client, $reqFactory): Promise {
            return $client->sendAsyncRequest($reqFactory->createRequest('GET', 'https://example.com'));
        };

        $promise = $mkRequest()
            ->then($mkRequest)
            ->then(function ($value) { return $value; })
        ;

        return new Response((string) $promise->wait()->getBody());
    }
}
