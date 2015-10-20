<?php

namespace TimeTable\ApiBundle\Controller;

use AppBundle\Exception\ValidationException;
use FOS\RestBundle\Controller\Annotations\Get;
use FOS\RestBundle\Controller\Annotations\Post;
use FOS\RestBundle\Controller\Annotations\QueryParam;
use FOS\RestBundle\Controller\FOSRestController;
use Nelmio\ApiDocBundle\Annotation\ApiDoc;
use Symfony\Component\HttpFoundation\Request;

/**
 * Class UserController
 * @package TimeTable\ApiBundle\Controller
 *
 *
 */
class UserController extends FOSRestController
{

    /**
     *
     * @ApiDoc(section="User",
     *      statusCodes={
     *         200="Returned when successful",
     *         403="If the user is not authenticated"
     *     }
     * )
     *
     * @Get("/user")
     *
     */
    public function getUserAction()
    {
        $user = $this->get('app.service.user_service')->getUserById($this->getUser()->getId());
        $view = $this->view($user);
        return $this->handleView($view);
    }

    /**
     *
     * @ApiDoc(section="User",
     *      statusCodes={
     *         200="Returned when successful",
     *         403="If the user is not authenticated",
     *         400="Validation failed"
     *     }
     * )
     *
     * @QueryParam(name="email")
     * @QueryParam(name="password")
     * @Post("/user/register")
     *
     */
    public function postRegisterAction(Request $request)
    {

        try {
            $userDTO = $this->get('app.service.user_service')->registerUser(
                $request->get('email'),
                $request->get('password')
            );
            $view = $this->view($userDTO);
        } catch (ValidationException $e) {
            $view = $this->view($e->getConstraintViolationList(), $e->getCode());
        }

        return $this->handleView($view);
    }
}