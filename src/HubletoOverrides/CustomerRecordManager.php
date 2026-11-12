<?php

namespace Hubleto\App\External\WaiBlue\OhsClients\HubletoOverrides;

use Hubleto\App\External\WaiBlue\OhsCore\Models\RecordManagers\Traits\OffersActiveRecords;

/**
 * Every user sees all clients; dropdowns offer the active ones.
 */
class CustomerRecordManager extends \Hubleto\App\Community\Customers\Models\RecordManagers\Customer
{
  use OffersActiveRecords;

  // Hubleto's parent would limit the clients by user type because of id_owner, so it is left out
  // (customers have no junctions).
  public function prepareReadQuery(mixed $query = null, int $level = 0, array|null $includeRelations = null): mixed
  {
    $query = \Hubleto\Framework\RecordManager::prepareReadQuery($query, $level, $includeRelations);
    return $this->addTagsCount($query);
  }

  // Dropdowns offer active clients only, as in both legacy apps.
  public function restrictToOfferedRecords(mixed $query): mixed
  {
    return $this->restrictToActiveOrChosen($query, 'id_customer');
  }

  // The same virtual column as the community record manager adds.
  private function addTagsCount(mixed $query): mixed
  {
    return $query->selectSub(function ($tagsOfCustomer) {
      $tagsOfCustomer->from('cross_customer_tags')
        ->join('customer_tags', 'customer_tags.id', '=', 'cross_customer_tags.id_tag')
        ->whereColumn('cross_customer_tags.id_customer', 'customers.id')
        ->selectRaw('COUNT(DISTINCT customer_tags.id)');
    }, 'tags_count');
  }
}
