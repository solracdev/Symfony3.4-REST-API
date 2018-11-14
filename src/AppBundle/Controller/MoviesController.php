<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Movie;
use AppBundle\Entity\Role;
use AppBundle\Exception\ValidationException;
use AppBundle\Merge\EntityMerge;
use AppBundle\Resource\Filtering\Movie\MovieFilterDefinitionFactory;
use AppBundle\Resource\Filtering\Role\RoleFilterDefinitionFactory;
use AppBundle\Resource\Pagination\Movie\MoviePagination;
use AppBundle\Resource\Pagination\PageRequestFactory;
use AppBundle\Resource\Pagination\Role\RolePagination;
use FOS\RestBundle\Controller\Annotations as Rest;
use FOS\RestBundle\Controller\ControllerTrait;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Cache;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Validator\ConstraintViolationListInterface;
use FOS\HttpCacheBundle\Configuration\InvalidateRoute;


/**
 * @Security("is_anonymous() or is_authenticated()")I
 */
class MoviesController extends AbstractController {

    /**
     * @var MergeEntity
     */
    private $entityMerge;

    /**
     * @var MoviePagination
     */
    private $moviePagination;

    /**
     * @var RolePagination
     */
    private $rolePagination;

    // FOS RestBundle
    use ControllerTrait;

    // Constructor
    public function __construct(EntityMerge $entityMerge, MoviePagination $moviePagination, RolePagination $rolePagination) {

        $this->entityMerge = $entityMerge;
        $this->moviePagination = $moviePagination;
        $this->rolePagination = $rolePagination;
    }

    /**
     * @Rest\View()
     */
    public function getMoviesAction(Request $request) {

        $pageRequestFactory = new PageRequestFactory();
        $page = $pageRequestFactory->fromRequest($request);

        $movieFilterFactory = new MovieFilterDefinitionFactory();
        $movieFilterDefinition = $movieFilterFactory->factory($request);

        return $this->moviePagination->paginate($page, $movieFilterDefinition);
    }

    /**
     * @Rest\View(statusCode=201)
     * @ParamConverter("movie", converter="fos_rest.request_body") //permite pasar el parametro recibido a entidad (lo que esta configurado en config.yml)
     * @Rest\NoRoute()
     */
    public function postMoviesAction(?Movie $movie, ConstraintViolationListInterface $validationErrors) {
        
        if (count($validationErrors) > 0) {

            throw new ValidationException($validationErrors);
        }

        $em = $this->getDoctrine()->getManager();

        $em->persist($movie);
        $em->flush();

        return $movie;
    }

    /**
     * @Rest\View()
     * @InvalidateRoute("get_movie", params={"movie" = {"expression" = "movie.getId()"}})
     * @InvalidateRoute("get_movies")
     */
    public function deleteMovieAction(?Movie $movie) {

        if (null == $movie) {

            return $this->view(null, 404);
        }

        $em = $this->getDoctrine()->getManager();

        $em->remove($movie);
        $em->flush();
    }

    /**
     * @Rest\View()
     * @Cache(public=true, maxage=3600, smaxage=3600) // especificar el cache con la anotacion Cache, de esta manera se puede configurar el cache para cada ruta
     */
    public function getMovieAction(?Movie $movie) {

        if (null == $movie) {

            return $this->view($movie, 404);
        }

        return $movie;
    }

    /**
     * @Rest\View()
     */
    public function getMovieRolesAction(Request $request, Movie $movie) {

        $pageRequestFactory = new PageRequestFactory();
        $page = $pageRequestFactory->fromRequest($request);

        $roleFilterDefinitionFactory = new RoleFilterDefinitionFactory();
        $roleFilterDefinition = $roleFilterDefinitionFactory->factory($request, $movie->getId());
        
        return $this->rolePagination->paginate($page, $roleFilterDefinition);
    }

    /**
     * @Rest\View(statusCode=201)
     * @ParamConverter("role", converter="fos_rest.request_body", options={"deserializationContext"={"groups"={"Deserialize"}}})
     * @Rest\NoRoute()
     */
    public function postMovieRolesAction(Movie $movie, Role $role, ConstraintViolationListInterface $validationErrors) {

        if (count($validationErrors) > 0) {
            
            throw new ValidationException($validationErrors);
        }

        $role->setMovie($movie);

        $em = $this->getDoctrine()->getManager();

        $em->persist($role);
        $movie->getRoles()->add($role);

        $em->persist($movie);

        $em->flush();

        return $role;
    }

    /**
     * @Rest\NoRoute()
     * @ParamConverter("modifiedMovie", converter="fos_rest.request_body",
     *  options={"validator" = {"groups" = {"Patch"}}}
     * )
     * @Security("is_authenticated()")
     */
    public function patchMovieAction(?Movie $movie, Movie $modifiedMovie, ConstraintViolationListInterface $validationErrors) {

        // Comprobar que el parametro recibido no sea null
        if (null == $movie) {

            return $this->view(null, 404);
        }

        // Comprobar que no ha ningun error
        if (count($validationErrors) > 0) {

            throw new ValidationException($validationErrors);
        }

        // llamar al metodo de la instancia entityMerge con los parametros de movie y modifiedMovie
        $this->entityMerge->merge($movie, $modifiedMovie);

        // persist
        $em = $this->getDoctrine()->getManager();
        $em->persist($movie);
        $em->flush();

        // return
        return $movie;
    }

}
