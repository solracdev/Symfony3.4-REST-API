<?php

namespace AppBundle\Controller;

use AppBundle\Security\TokenStorage;
use FOS\RestBundle\Controller\Annotations as Rest;
use FOS\RestBundle\Controller\ControllerTrait;
use Lexik\Bundle\JWTAuthenticationBundle\Encoder\JWTEncoderInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Security\Core\Exception\BadCredentialsException;

/**
 * @Security("is_anonymous() or is_authenticated()")
 */
class TokensController extends AbstractController {

    // FOS Rest Bundle
    use ControllerTrait;

    /**
     * @var TokenStorage
     */
    private $tokenStorage;

    /**
     * @var JWTEncoderInterface
     */
    private $encoder;

    /**
     * @var UserPasswordEncoderInterface
     */
    private $passwordEncoder;

    /**
     * 
     * @param UserPasswordEncoderInterface $passwordEncoder
     * @param JWTEncoderInterface $encoder
     * @param TokenStorage $tokenStorage
     */
    public function __construct(UserPasswordEncoderInterface $passwordEncoder, JWTEncoderInterface $encoder, TokenStorage $tokenStorage) {
        
        $this->passwordEncoder = $passwordEncoder;
        $this->encoder = $encoder;
        $this->tokenStorage = $tokenStorage;
    }

    /**
     * @Rest\View(statusCode=201)
     */
    public function postTokenAction(Request $request) {

        // Buscar al usuario con el parametro username que nos llega por request
        $user = $this->getDoctrine()->getRepository("AppBundle:User")->findOneBy(["username" => $request->getUser()]);

        if (!$user) {

            return new BadCredentialsException();
        }

        // Guardar en la variable si el password es correcto o no
        $isPasswordvalid = $this->passwordEncoder->isPasswordValid($user, $request->getPassword());

        if (!$isPasswordvalid) {

            return new BadCredentialsException();
        }

        $token = $this->encoder->encode(["username" => $user->getUsername(), "time" => time() + 3600]);
        
        $this->tokenStorage->storeToken($user->getUsername(), $token);

        return new JsonResponse(["token" => $token]);
    }

}
