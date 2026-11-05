<?php

namespace Hubleto\App\External\WaiBlue\OhsClients\Controllers;

use Hubleto\App\External\WaiBlue\OhsClients\Services\EmailExport as EmailExportService;

/**
 * Choose a role (or client contacts) and download the e-mail addresses as CSV.
 */
class EmailExport extends \Hubleto\Erp\Controller
{
  public function prepareView(): void
  {
    parent::prepareView();
    $this->viewParams['options'] = $this->getService(EmailExportService::class)->getOptions();
    $this->setView('@Hubleto:App:External:WaiBlue:OhsClients/EmailExport.twig');
  }
}
