<?php

namespace Oforge\Engine\Modules\TemplateEngine\Core\Manager;

use Oforge\Engine\Modules\Core\Abstracts\AbstractViewManager;
use Oforge\Engine\Modules\Core\Helper\ArrayHelper;
use Oforge\Engine\Modules\TemplateEngine\Core\Twig\TwigFlash;

/**
 * Class ViewManager
 *
 * @package Oforge\Engine\Modules\TemplateEngine\Core\Manager
 */
class ViewManager extends AbstractViewManager {
    /** @var ViewManager $instance */
    protected static $instance;
    /** @var array $viewData */
    private $viewData = [];
    /** @var TwigFlash $twigFlash */
    private $twigFlash;

    /**
     * Create a singleton instance of the ViewManager
     *
     * @return ViewManager
     */
    public static function getInstance() {
        if (!isset(self::$instance)) {
            self::$instance = new ViewManager();
        }

        return self::$instance;
    }

    /**
     * @inheritDoc
     */
    public function Flash() : TwigFlash {
        if (!isset($this->twigFlash)) {
            $this->twigFlash = new TwigFlash();
        }

        return $this->twigFlash;
    }

    /**
     * Assign Data from a Controller to a Template
     *
     * @param array $data
     *
     * @return ViewManager
     */
    public function assign($data) {
        $data           = ArrayHelper::dotToNested($data);
        $this->viewData = ArrayHelper::mergeRecursive($this->viewData, $data);

        return $this;
    }

    /**
     * Fetch View Data. This function should be called from the route middleware
     * so that it can transport the data to the TemplateEngine
     *
     * @return array
     */
    public function fetch() {
        return $this->viewData;
    }

    /**
     * Get a specific key value from the viewData or $default if data with key does not exist.
     *
     * @param string $key
     * @param mixed $default
     *
     * @return mixed|null
     */
    public function get(string $key, $default = null) {
        return ArrayHelper::dotGet($this->viewData, $key, $default);
    }

    /**
     * Exist non empty value with key?
     *
     * @param string $key
     *
     * @return bool
     */
    public function has(string $key) : bool {
        return isset($this->viewData[$key]) && !empty($this->viewData[$key]);
    }

}
