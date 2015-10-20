<?php


namespace TimeTable\ApiBundle\Controller;

use AppBundle\Exception\ProjectNotFoundException;
use FOS\RestBundle\Controller\Annotations\Get;
use FOS\RestBundle\Controller\FOSRestController;
use Nelmio\ApiDocBundle\Annotation\ApiDoc;

/**
 * Class OverviewController
 * @package TimeTable\ApiBundle\Controller
 */
class OverviewController extends FOSRestController
{
    /**
     * @ApiDoc(section="Overview",
     *      statusCodes={
     *         200="Returned when successful",
     *         403="If the user is not authenticated"
     *     }
     * )
     *
     * @Get("/project-overview/{projectId}")
     */
    public function indexAction($projectId)
    {
        try {
            $projectOverviewDTO = $this->get('app.service.project_service')
                ->getProjectOverview($this->getUser()->getId(), $projectId);
            $view = $this->view($projectOverviewDTO);
        } catch (ProjectNotFoundException $e) {
            $view = $this->view($this->get('translator')->trans('project.not_found'));
        }

        return $this->handleView($view);
    }

}