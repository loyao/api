<?php

namespace App\Http\Controllers\Api\v1;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Psr\Http\Message\ServerRequestInterface;
use Zend\Diactoros\Response as Psr7Response;
use League\OAuth2\Server\Exception\OAuthServerException;
use League\OAuth2\Server\AuthorizationServer;
use Illuminate\Support\Facades\Auth;

class AuthorizationsController extends BaseController
{
    public function getAccessToken(AuthorizationServer $server, ServerRequestInterface $serverRequest)
    {
        try {
            return $server->respondToAccessTokenRequest($serverRequest,new Psr7Response)->withStatus(201);
        }catch (\OAuthException $exception){
            return $this->response->errorUnauthorized($exception->getMessage());
        }
    }
    public function getUserInfo(){
        $user = Auth::user();
        return $user;
    }
}
