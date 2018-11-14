<?php

namespace AppBundle\Security;

use Lexik\Bundle\JWTAuthenticationBundle\Encoder\JWTEncoderInterface;
use Lexik\Bundle\JWTAuthenticationBundle\Exception\JWTDecodeFailureException;
use Lexik\Bundle\JWTAuthenticationBundle\TokenExtractor\AuthorizationHeaderTokenExtractor;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Exception\AuthenticationException;
use Symfony\Component\Security\Core\User\User;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\User\UserProviderInterface;
use Symfony\Component\Security\Guard\AbstractGuardAuthenticator;

class TokenAuthenticator extends AbstractGuardAuthenticator {

    /**
     * @var TokenStorage
     */
    private $tokenStorage;

    /**
     * @var JWTEncoderInterface
     */
    private $jwtEnconder;

    public function __construct(JWTEncoderInterface $jwtEnconder, TokenStorage $tokenStorage) {

        $this->jwtEnconder = $jwtEnconder;
        $this->tokenStorage = $tokenStorage;
    }

    public function checkCredentials($credentials, UserInterface $user): bool {

        return true;
    }

    public function getCredentials(Request $request) {

        //$token = $request->headers->get("X-Auth-Token");

        $extractor = new AuthorizationHeaderTokenExtractor("Bearer", "Authorization");

        $token = $extractor->extract($request);


        return (!$token) ? null : $token;
    }

    public function getUser($credentials, UserProviderInterface $userProvider) {
        
        //dump($credentials, $userProvider); die;

        try {

            $data = $this->jwtEnconder->decode($credentials);
            
            if (false == $data) {

                return null;
            }
            
            if (!$this->tokenStorage->isTokenValid($data["username"], $credentials)) {
                
                return null;
            }

            return $userProvider->loadUserByUsername($data["username"]);
            
        } catch (JWTDecodeFailureException $exception) {
            
            return null;
        }
    }

    public function onAuthenticationFailure(Request $request, AuthenticationException $exception) {

        return new JsonResponse(null, Response::HTTP_FORBIDDEN);
    }

    public function onAuthenticationSuccess(Request $request, TokenInterface $token, $providerKey) {

        return null;
    }

    public function start(Request $request, AuthenticationException $authException = null): Response {

        return new JsonResponse(null, Response::HTTP_UNAUTHORIZED);
    }

    public function supportsRememberMe(): bool {

        return false;
    }

}
