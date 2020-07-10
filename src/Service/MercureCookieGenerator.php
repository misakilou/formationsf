<?php


namespace App\Service;


use App\Entity\Article;
use App\Entity\User;
use Lcobucci\JWT\Builder;
use Lcobucci\JWT\Signer\Hmac\Sha256;
use Lcobucci\JWT\Signer\Key;

class MercureCookieGenerator
{
    private $secret;

    public function __construct(string $secret)
    {
        $this->secret = $secret;
    }

    public function generatePrivate(Article $article , int $duration = 30){

        $id = $article->getId();
        $token = (new Builder())
            ->expiresAt(time()+$duration)
            ->withClaim('mercure', [
                'subscribe' => [
                    'http://monblog/test-socket/'.$id
                ]
            ])
            ->getToken(new Sha256(), new Key($this->secret))
        ;

        $cookieString = sprintf('mercureAuthorization=%s; path=/.well-known/mercure; secure; httponly; SameSite=strict' , $token);
        return $cookieString ;
    }

}