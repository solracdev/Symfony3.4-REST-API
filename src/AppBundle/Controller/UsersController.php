<?php

namespace AppBundle\Controller;

use AppBundle\Entity\User;
use AppBundle\Exception\ValidationException;
use AppBundle\Merge\EntityMerge;
use AppBundle\Security\TokenStorage;
use FOS\RestBundle\Controller\Annotations as Rest;
use FOS\RestBundle\Controller\ControllerTrait;
use Lexik\Bundle\JWTAuthenticationBundle\Encoder\JWTEncoderInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Validator\ConstraintViolationListInterface;

/**
 * @Security("is_anonymous() or is_authenticated()")
 */
class UsersController extends AbstractController {

    
    use ControllerTrait;
    
    /**
     * @var TokenStorage
     */
    private $tokenStorage;

    /**
     * @var JWTEncoderInterface
     */
    private $jwtEncoder;

    /**
     * @var UserPasswordEncoderInterface
     */
    private $passwordEnconder;

    /**
     * @var EntityMerge
     */
    private $entityMerge;

    /**
     * 
     * @param UserPasswordEncoderInterface $passwordEnconder
     * @param JWTEncoderInterface $jwtEncoder
     * @param EntityMerge $entityMerge
     */
    public function __construct(UserPasswordEncoderInterface $passwordEnconder, JWTEncoderInterface $jwtEncoder, EntityMerge $entityMerge, TokenStorage $tokenStorage) {

        $this->passwordEnconder = $passwordEnconder;
        $this->jwtEncoder = $jwtEncoder;
        $this->entityMerge = $entityMerge;
        $this->tokenStorage = $tokenStorage;
    }

    /**
     * @Rest\View() 
     * @Security("is_granted('show', theUser)", message="Access denied")
     */
    public function getUserAction(?User $theUser) {

        if (null == $theUser) {

            throw new NotFoundHttpException();
        }

        return $theUser;
    }

    /**
     * @Rest\View(statusCode=201)
     * @Rest\NoRoute()
     * @ParamConverter(
     *      "user", 
     *      converter="fos_rest.request_body",
     *      options={"deserializationContext"={"groups"={"Deserialize"}}}
     * )
     */
    public function postUserAction(User $user, ConstraintViolationListInterface $validationErrors) {

        if (count($validationErrors) > 0) {

            throw new ValidationException($validationErrors);
        }

        $this->encodeUserPassword($user);

        $user->setRoles([User::ROLE_USER]);

        $this->persistUser($user);

        return $user;
    }

    /**
     * @Rest\NoRoute()
     * @ParamConverter(
     *      "modifiedUser", 
     *      converter="fos_rest.request_body",
     *      options={
     *              "validator"={"groups"={"Patch"}},
     *              "deserializationContext"={"groups"={"Deserialize"}}
     *              }
     * )
     * @Security("is_granted('edit', theUser)", message="Access denied")
     */
    public function patchUserAction(?User $theUser, User $modifiedUser, ConstraintViolationListInterface $validationErrors) {

        if (null === $theUser) {

            throw new NotFoundHttpException();
        }

        if (count($validationErrors) > 0) {

            throw new ValidationException($validationErrors);
        }

        if (empty($modifiedUser->getPassword())) {

            $modifiedUser->setPassword(null); 
        } 
        
        $this->entityMerge->merge($theUser, $modifiedUser);
        $this->encodeUserPassword($theUser);
        
        // Si el password se ha cambiado se invalida el token para la session actual
        if ($modifiedUser->getPassword()) {
            
            $this->tokenStorage->invalidateToken($theUser->getUsername());
        }

        $this->persistUser($theUser);
        
        return $theUser;
    }

    protected function persistUser(User $user): void {

        $em = $this->getDoctrine()->getManager();

        $em->persist($user);
        $em->flush();
    }

    protected function encodeUserPassword(User $user): void {

        $user->setPassword($this->passwordEnconder->encodePassword($user, $user->getPassword()));
    }

}
