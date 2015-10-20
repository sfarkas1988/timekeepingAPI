<?php

namespace TimeTable\ApiBundle\Controller;

use AppBundle\Exception\WorkTimeNotActiveException;
use AppBundle\Exception\WorkTimeNotFoundException;
use AppBundle\Exception\ValidationException;
use AppBundle\Exception\WorkTimeNotStoppedException;
use FOS\RestBundle\Controller\Annotations\Get;
use FOS\RestBundle\Controller\Annotations\Post;
use FOS\RestBundle\Controller\Annotations\QueryParam;
use FOS\RestBundle\Controller\FOSRestController;
use Nelmio\ApiDocBundle\Annotation\ApiDoc;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Class WorkTimeController
 * @package TimeTable\ApiBundle\Controller
 *
 */
class WorkTimeController extends FOSRestController
{

    /**
     * @ApiDoc(section="WorkTime",
     *      statusCodes={
     *         200="Returned when successful",
     *         403="If the user is not authenticated",
     *         400="When worktime validation failed"
     *     }
     * )
     *
     * @Post("/work-time/start/{projectId}")
     *
     * @param $projectId
     * @return \Symfony\Component\HttpFoundation\Response
     * @throws \AppBundle\Exception\ProjectNotFoundException
     * @throws \AppBundle\Exception\UserNotFoundException
     */
    public function postStartAction($projectId)
    {
        try {
            $workTimeDTO = $this->get('app.service.work_time_service')
                ->startWorkTime($this->getUser()->getId(), $projectId);
            $view = $this->view($workTimeDTO);
        } catch (WorkTimeNotStoppedException $e) {
            $view = $this->view($e->getWorkTimeDTO(), Response::HTTP_BAD_REQUEST);
        }

        return $this->handleView($view);
    }

    /**
     * @ApiDoc(section="WorkTime",
     *      statusCodes={
     *         200="Returned when successful",
     *         403="If the user is not authenticated",
     *         400="When worktime does not exist or parameter validation failed"
     *     }
     * )
     *
     * @Post("/work-time/stop/{workTimeId}")
     *
     * @QueryParam(
     *      name="duration",
     *      description="duration of the full working time",
     *      nullable=false,
     *      requirements="/([0-9]|0[0-9]|1[0-9]|2[0-3]):[0-5][0-9]/"
     * )
     * @QueryParam(
     *      name="endDate",
     *      description="endDate when workTime ended",
     *      nullable=false,
     *      requirements="%Y-%m-%d %H:%i:%s"
     * )
     *
     * @QueryParam(
     *      name="description",
     *      description="for documentation purpose an optional description",
     *      nullable=true
     * )
     *
     * @param Request $request
     * @param $workTimeId
     * @return Response
     */
    public function postStopAction(Request $request, $workTimeId)
    {
        try {
            $workTimeDTO = $this->get('app.service.work_time_service')
                ->stopWorkTime(
                    $this->getUser()->getId(),
                    $workTimeId,
                    $request->get('endDate'),
                    $request->get('duration'),
                    $request->get('description')
                );

            $view = $this->view($workTimeDTO);
        } catch (WorkTimeNotActiveException $e) {
            $view = $this->view(
                $this->get('translator')->trans('stop_duration.worktime_not_active'),
                Response::HTTP_BAD_REQUEST
            );
        } catch (WorkTimeNotFoundException $e) {
            $view = $this->view(
                $this->get('translator')->trans('stop_duration.worktime_not_found'),
                Response::HTTP_BAD_REQUEST
            );
        } catch (ValidationException $e) {
            $view = $this->view($e->getConstraintViolationList(), Response::HTTP_BAD_REQUEST);
        }

        return $this->handleView($view);
    }

    /**
     * @param $workTimeId
     * @return Response
     *
     * @ApiDoc(section="WorkTime",
     *      statusCodes={
     *         200="Returned when successful",
     *         403="If the user is not authenticated",
     *         400="When worktime does not exist"
     *     }
     * )
     *
     * @Get("/work-time/{workTimeId}")
     */
    public function getWorkTimeAction($workTimeId)
    {
        try {
            $workTimeDTO = $this->get('app.service.work_time_service')
                ->findWorkTimeByUserAndId($this->getUser()->getId(), $workTimeId);
            $view = $this->view($workTimeDTO);
        } catch (WorkTimeNotFoundException $e) {
            $view = $this->view(
                $this->get('translator')->trans('stop_duration.worktime_not_found'),
                Response::HTTP_BAD_REQUEST
            );
        }

        return $this->handleView($view);
    }
}