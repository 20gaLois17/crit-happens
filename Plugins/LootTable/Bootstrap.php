<?php

namespace LootTable;

use Doctrine\ORM\OptimisticLockException;
use Doctrine\ORM\ORMException;
use FrontendUserManagement\Services\AccountNavigationService;
use LootTable\Controller\Frontend\FrontendLootTableController;
use LootTable\Models\LootItem;
use LootTable\Models\LootPreference;
use LootTable\Services\LootManagementService;
use Oforge\Engine\Modules\Core\Abstracts\AbstractBootstrap;
use Oforge\Engine\Modules\Core\Exceptions\ConfigElementAlreadyExistException;
use Oforge\Engine\Modules\Core\Exceptions\ConfigOptionKeyNotExistException;
use Oforge\Engine\Modules\Core\Exceptions\ParentNotFoundException;
use Oforge\Engine\Modules\Core\Exceptions\ServiceNotFoundException;
use Oforge\Engine\Modules\I18n\Helper\I18N;

/**
 * Class LootTable
 *
 * @package Plugings\LootTable
 */
class Bootstrap extends AbstractBootstrap {
  public function __construct() {
    $this->models = [
        LootItem::class,
        LootPreference::class,
    ];
    $this->endpoints = [
        FrontendLootTableController::class,
    ];
    $this->services = [
        'loot.management' => LootManagementService::class,
    ];
    $this->dependencies = [
        \FrontendUserManagement\Bootstrap::class,
    ];
  }

  public function install() {
      // Item Types
      I18N::translate('weapon', [
          'de' => 'Waffen / Schilde / Offhand'
      ]);
      I18N::translate('cloth', [
          'de' => 'Stoff'
      ]);
      I18N::translate('leather', [
          'de' => 'Leder'
      ]);
      I18N::translate('mail', [
          'de' => 'Kette'
      ]);
      I18N::translate('plate', [
          'de' => 'Platte'
      ]);
      I18N::translate('other', [
          'de' => 'Schmuck / Umhänge'
      ]);
      // Raid Types
      I18N::translate('mc', [
          'de' => 'Molten Core'
      ]);
      I18N::translate('bwl', [
          'de' => 'Black Wing Lair'
      ]);
  }

    /**
     * @throws ORMException
     * @throws OptimisticLockException
     * @throws ConfigElementAlreadyExistException
     * @throws ConfigOptionKeyNotExistException
     * @throws ParentNotFoundException
     * @throws ServiceNotFoundException
     */
  public function activate() {
      /** @var AccountNavigationService $accountNavigationService */
      $accountNavigationService = Oforge()->Services()->get('frontend.user.management.account.navigation');
      $accountNavigationService->put([
          'name'     => 'frontend_account_loottable',
          'order'    => 3,
          'icon'     => 'profile',
          'path'     => 'frontend_account_loottable',
          'position' => 'sidebar',
      ]);
    }
}
