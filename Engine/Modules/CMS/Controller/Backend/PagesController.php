<?php

namespace Oforge\Engine\Modules\CMS\Controller\Backend;

use Doctrine\ORM\OptimisticLockException;
use Doctrine\ORM\ORMException;
use Oforge\Engine\Modules\AdminBackend\Core\Abstracts\SecureBackendController;
use Oforge\Engine\Modules\Auth\Models\User\BackendUser;
use Oforge\Engine\Modules\CMS\Services\PagesControllerService;
use Oforge\Engine\Modules\Core\Annotation\Endpoint\EndpointAction;
use Oforge\Engine\Modules\Core\Annotation\Endpoint\EndpointClass;
use Oforge\Engine\Modules\Core\Exceptions\ServiceNotFoundException;
use Slim\Http\Request;
use Slim\Http\Response;

/**
 * Class PagesController
 *
 * @package Oforge\Engine\Modules\CMS\Controller\Backend
 * @EndpointClass(path="/backend/content/page", name="backend_content_pages", assetScope="Backend")
 */
class PagesController extends SecureBackendController {
    /**
     * @param Request $request
     * @param Response $response
     *
     * @return mixed
     * @throws ServiceNotFoundException
     * @throws ORMException
     * @throws OptimisticLockException
     * @EndpointAction()
     */
    public function indexAction(Request $request, Response $response) {
        /** @var PagesControllerService $pagesControllerService */
        $pagesControllerService = OForge()->Services()->get('pages.controller.service');

        $data = [];
        if (isset($_POST['cms_form'])) {
            switch ($_POST['cms_form']) {
                case 'cms_page_jstree_form':
                    $data = $pagesControllerService->editPageData($_POST);
                    break;
                case 'cms_page_data_form':
                    $data = $pagesControllerService->updatePagePathData($_POST);
                // DO NOT INSERT A BREAK HERE SO THAT EDIT MODE IS ACTIVATED
                // AFTER UPDATING PAGE DATA
                case 'cms_page_builder_form':
                default:
                    if ($pagesControllerService->checkForValidPagePath($_POST)) {
                        $data = $pagesControllerService->editContentData($_POST);
                    } else {
                        $data = $pagesControllerService->editPagePathData($_POST);
                    }
                    break;
            }
        }

        if (empty($data)) {
            $data = $pagesControllerService->editPagePathData($_POST);
        }

        Oforge()->View()->assign($data);
    }

    /**
     * @param Request $request
     * @param Response $response
     *
     * @return mixed
     * @throws ServiceNotFoundException
     * @throws ORMException
     * @throws OptimisticLockException
     * @EndpointAction()
     */
    public function updateAction(Request $request, Response $response) {
        /** @var PagesControllerService $pagesControllerService */
        $pagesControllerService = OForge()->Services()->get('pages.controller.service');

        $data = [];

        if (isset($_POST['cms_form'])) {
            switch ($_POST['cms_form']) {
                case 'cms_page_builder_form':
                default:
                    if ($pagesControllerService->checkForValidPagePath($_POST)) {
                        $data = $pagesControllerService->editContentData($_POST);
                    } else {
                        $data = $pagesControllerService->editPagePathData($_POST);
                    }
                    break;
            }
        }

        if (!empty($data)) {
            $data["result"] = "success";
        }

        Oforge()->View()->assign($data);
    }

    public function initPermissions() {
        $this->ensurePermissions([
            'indexAction',
            'updateAction',
        ], BackendUser::ROLE_MODERATOR);
    }

}
