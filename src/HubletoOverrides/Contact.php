<?php

namespace Hubleto\App\External\WaiBlue\OhsClients\HubletoOverrides;

use Hubleto\App\Community\Contacts\Models\Contact as CommunityContact;

/**
 * Hubleto's Contact model with the Ohs client visibility (registered in the OhsClients loader).
 * Hubleto shows every contact person to everybody (see the patches documents).
 */
class Contact extends CommunityContact
{
  use \Hubleto\App\External\WaiBlue\OhsCore\Models\Traits\BehavesAsCommunityModel;
  use \Hubleto\App\External\WaiBlue\OhsCore\Models\Traits\OffersOnlyAllowedChanges;

  public string $recordManagerClass = ContactRecordManager::class;

  public static function register(): void
  {
    \Hubleto\Framework\DependencyInjection::setServiceProvider(CommunityContact::class, self::class);
  }

  public function __construct()
  {
    parent::__construct();

    $this->behaveAsCommunityModel(CommunityContact::class, 'hubleto-app-community-contacts-loader');

    // Hubleto describes the columns in its constructor, before the name is reset; custom columns depend on it.
    $this->columns = $this->describeColumns();
  }

  // Medical staff see clients and contacts read-only.
  public function describeForm(): \Hubleto\Framework\Description\Form
  {
    $description = parent::describeForm();
    return $this->offerOnlyAllowedChanges($description);
  }

  public function describeTable(): \Hubleto\Framework\Description\Table
  {
    $description = parent::describeTable();
    return $this->offerOnlyAllowedChanges($description);
  }
}
