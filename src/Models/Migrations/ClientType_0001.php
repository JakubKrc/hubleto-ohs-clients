<?php

namespace Hubleto\App\External\WaiBlue\OhsClients\Models\Migrations;

use Hubleto\Framework\Migration;

// The client type on Hubleto's "customers" table is a lookup with a foreign key.
// The Clients app adds its other columns to "customers" right after its migrations (CustomerColumns::installMissingColumns).
class ClientType_0001 extends Migration
{
  public function upgradeSchema(): void
  {
    $this->db->execute("CREATE TABLE IF NOT EXISTS `ohs_client_types` (
      `id` int(8) primary key auto_increment,
      `name` varchar(255) NOT NULL,
      UNIQUE INDEX `name` (`name`)
    ) ENGINE=InnoDB");

    if (!$this->customersHaveColumn('id_ohs_client_type')) {
      $this->db->execute("ALTER TABLE `customers` ADD COLUMN `id_ohs_client_type` int(8) NULL DEFAULT NULL");
    }
  }

  public function downgradeSchema(): void
  {
    $this->db->execute("ALTER TABLE `customers` DROP COLUMN `id_ohs_client_type`");
    $this->db->execute("DROP TABLE IF EXISTS `ohs_client_types`");
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
