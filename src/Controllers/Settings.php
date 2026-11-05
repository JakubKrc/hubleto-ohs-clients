<?php

namespace Hubleto\App\External\WaiBlue\OhsClients\Controllers;

use Hubleto\App\External\WaiBlue\OhsClients\Services\CustomerColumns;

class Settings extends \Hubleto\Erp\Controller
{
  public function getBreadcrumbs(): array
  {
    return array_merge(parent::getBreadcrumbs(), [
      ['url' => 'ohs/clients', 'content' => $this->translate('Client data')],
      ['url' => 'settings', 'content' => $this->translate('Settings')],
    ]);
  }

  public function prepareView(): void
  {
    parent::prepareView();

    $clientsApp = $this->appManager()->getApp(\Hubleto\App\External\WaiBlue\OhsClients\Loader::class);

    /** @var \Hubleto\App\External\WaiBlue\OhsCore\Services\Roles */
    $roles = $this->getService(\Hubleto\App\External\WaiBlue\OhsCore\Services\Roles::class);
    $maySaveSettings = $roles->isActiveUserAdministratorOrManager();
    $this->viewParams['maySaveSettings'] = $maySaveSettings;

    $settingsChanged = $this->router()->urlParamAsBool('settingsChanged');
    if ($settingsChanged && $maySaveSettings) {
      $defaultAuditIntervalMonths = $this->router()->urlParamAsInteger('defaultAuditIntervalMonths');
      if ($defaultAuditIntervalMonths > 0) {
        $clientsApp->saveConfig('defaultAuditIntervalMonths', (string) $defaultAuditIntervalMonths);
      }
      $this->viewParams['settingsSaved'] = true;
    }

    $this->viewParams['defaultAuditIntervalMonths'] = $this->getService(CustomerColumns::class)->getDefaultAuditIntervalMonths();
    $this->setView('@Hubleto:App:External:WaiBlue:OhsClients/Settings.twig');
  }
}
