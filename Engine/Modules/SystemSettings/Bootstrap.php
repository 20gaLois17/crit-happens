<?php

namespace Oforge\Engine\Modules\SystemSettings;

use Oforge\Engine\Modules\AdminBackend\Services\BackendNavigationService;
use Oforge\Engine\Modules\SystemSettings\Controller\Backend\SystemSettingsController;
use Oforge\Engine\Modules\Core\Abstracts\AbstractBootstrap;
use Oforge\Engine\Modules\SystemSettings\Services\SystemSettingsService;

class Bootstrap extends AbstractBootstrap
{
    /**
     * Bootstrap constructor.
     */
    public function __construct()
    {
        $this->endpoints = [
            "/backend/settings" => ["controller" => SystemSettingsController::class, "name" => "backend_settings", "asset_scope" => "Backend"]
        ];
    }

    /**
     *
     */
    public function install()
    {
        /**
         * @var $backendNavigation BackendNavigationService
         */
        $backendNavigation = Oforge()->Services()->get("backend.navigation");

        $backendNavigation->put([
            "name" => "backend_settings",
            "order" => 100,
            "parent" => "admin",
            "icon" => "fa fa-gears",
            "path" => "backend_settings"
        ]);
    }
}
