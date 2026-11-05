<?php

namespace Hubleto\App\External\WaiBlue\OhsClients\Models\Migrations;

use Hubleto\Framework\Migration;

// The client type on Hubleto's "customers" table becomes a real lookup with a foreign key (models and lookups review B5).
class ClientType_0002 extends Migration
{
  public function upgradeSchema(): void
  {
    $hasOldColumn = $this->customersHaveColumn('ohs_client_type');
    $hasNewColumn = $this->customersHaveColumn('id_ohs_client_type');

    if ($hasOldColumn && !$hasNewColumn) {
      $this->db->execute("ALTER TABLE `customers` CHANGE `ohs_client_type` `id_ohs_client_type` int(8) NULL DEFAULT NULL");
    } elseif (!$hasNewColumn) {
      // A new install: the Clients app adds its other columns to "customers" right after its migrations.
      $this->db->execute("ALTER TABLE `customers` ADD COLUMN `id_ohs_client_type` int(8) NULL DEFAULT NULL");
    }

    // Ids of client types deleted earlier would stop the foreign key.
    $this->db->execute("UPDATE `customers` SET `id_ohs_client_type` = NULL
      WHERE `id_ohs_client_type` IS NOT NULL
        AND `id_ohs_client_type` NOT IN (SELECT `id` FROM `ohs_client_types`)");
  }

  public function downgradeSchema(): void
  {
    $this->db->execute("ALTER TABLE `customers` CHANGE `id_ohs_client_type` `ohs_client_type` int(8) NULL DEFAULT NULL");
  }

  public function upgradeForeignKeys(): void
  {
    $this->db->execute("ALTER TABLE `customers` ADD CONSTRAINT `fk__customers__id_ohs_client_type` FOREIGN KEY (`id_ohs_client_type`) REFERENCES `ohs_client_types` (`id`) ON DELETE RESTRICT ON UPDATE RESTRICT");
  }

  public function downgradeForeignKeys(): void
  {
    $this->db->execute("ALTER TABLE `customers` DROP FOREIGN KEY `fk__customers__id_ohs_client_type`");
  }

  private function customersHaveColumn(string $columnName): bool
  {
    $columns = $this->db->fetchAll(
      "SELECT COLUMN_NAME FROM information_schema.COLUMNS
        WHERE TABLE_SCHEMA = DATABASE() AND TABLE_NAME = 'customers' AND COLUMN_NAME = ?",
      [$columnName]
    );
    return count($columns) > 0;
  }
}
