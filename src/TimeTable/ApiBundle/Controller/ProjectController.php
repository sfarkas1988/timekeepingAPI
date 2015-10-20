<?php

namespace TimeTable\ApiBundle\Controller;

use AppBundle\Exception\ProjectNotFoundException;
use AppBundle\Exception\ValidationException;
use FOS\RestBundle\Controller\Annotations\Get;
use FOS\RestBundle\Controller\Annotations\Post;
use FOS\RestBundle\Controller\Annotations\QueryParam;
use FOS\RestBundle\Controller\FOSRestController;
use Nelmio\ApiDocBundle\Annotation\ApiDoc;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Class ProjectController
 * @package TimeTable\ApiBundle\Controller
 *
 */
class ProjectController extends FOSRestController
{
    /**
     *
     * @ApiDoc(section="Project",
     *      statusCodes={
     *         200="Returned when successful",
     *         403="If the user is not authenticated"
     *     }
     * )
     *
     * @Get("/projects")
     *
     */
    public function getProjectsAction()
    {
        $projects = $this->get('app.service.project_service')->getProjectsByUser($this->getUser()->getId());
        $view = $this->view($projects);
        return $this->handleView($view);
    }

    /**
     * @ApiDoc(section="Project",
     *      statusCodes={
     *         200="Returned when successful",
     *         403="If the user is not authenticated",
     *         400="Validation failed"
     *     }
     * )
     *
     * @QueryParam(name="id", description="set when editing a project")
     * @QueryParam(name="title", description="title of the project")
     * @QueryParam(name="description", description="description of the project")
     * @QueryParam(name="hourlyRate", description="hourlyRate of the project")
     *
     * @Post("/project")
     *
     */
    public function postProjectAction(Request $request)
    {
        try {
            $project = $this->get('app.service.project_service')->saveProject(
                $this->getUser()->getId(),
                $request->get('title'),
                $request->get('description'),
                $request->get('hourlyRate'),
                $request->get('id')
            );

            $view = $this->view($project);
        } catch (ValidationException $e) {
            $view = $this->view($e->getConstraintViolationList(), Response::HTTP_BAD_REQUEST);
        }

        return $this->handleView($view);
    }

    /**
     * @param $projectId
     * @return Response
     *
     * @ApiDoc(section="Project",
     *      statusCodes={
     *         200="Returned when successful",
     *         403="If the user is not authenticated",
     *         400="Project not found"
     *     }
     * )
     *
     * @Get("/project/{projectId}")
     */
    public function getProjectAction($projectId)
    {
        try {
            $projectDTO = $this->get('app.service.project_service')
                ->getProjectByUser($this->getUser()->getId(), $projectId);
            $view = $this->view($projectDTO);
        } catch (ProjectNotFoundException $e) {
            $view = $this->view($this->get('translator')->trans('project.not_found'), Response::HTTP_BAD_REQUEST);
        }
        return $this->handleView($view);

    }
}