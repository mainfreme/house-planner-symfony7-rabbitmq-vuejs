<?php

namespace App\Infrastructure\User\MessageHandler;


use App\Application\User\Event\UserLoggedInEvent;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;
use Symfony\Contracts\HttpClient\HttpClientInterface;

#[AsMessageHandler(fromTransport: 'async')]
class UserLoggedInEventHandler
{
    public function __construct(
        private HttpClientInterface $httpClient
    )
    {
    }

    public function __invoke(UserLoggedInEvent $event): void
    {
        $user = $event->user;


        $this->httpClient->request('POST', 'http://microservice_ci4:80/api/logged', [
            'json' => [
                'user_id' => $user->getId(),
                'email' => $user->getEmail(),
                'logged_in_at' => (new \DateTimeImmutable())->format(DATE_ATOM),
            ],
        ]);
    }
}
