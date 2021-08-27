<?php
/**
* @version     1.7.0
* @module      WebRotate 360 Product Viewer for OpenCart
* @author      WebRotate 360 LLC
* @copyright   Copyright (C) 2018 WebRotate 360 LLC. All rights reserved.
* @license     GNU General Public License version 2 or later (http://www.gnu.org/copyleft/gpl.html).
*/

class ModelCatalogWebrotate360 extends Model
{
    // This is to avoid exception if the table didn't exist:
    public function ensureTableExists()
    {
        $_tableName = DB_PREFIX . "wr360product";
        $sqlCreateTable = <<<SQL
CREATE TABLE IF NOT EXISTS `$_tableName` (
  `product_id` INT NOT NULL ,
  `root_path` VARCHAR(255) NULL DEFAULT NULL ,
  `config_file_url` VARCHAR(255) NULL DEFAULT NULL ,
  `wr360_enabled` TINYINT(1) NOT NULL DEFAULT '1' ,
  PRIMARY KEY (`product_id`) )
ENGINE = MyISAM
DEFAULT CHARACTER SET = utf8;
SQL;

        $this->db->query($sqlCreateTable);
    }
    
    public function getProducts()
    {
        $defLngId = $this->config->get('config_language_id');
        if (empty($defLngId))
            $defLngId = 1;

        $_tableName = DB_PREFIX . "wr360product";
        $query = $this->db->query("SELECT p.product_id, pd.name, w.wr360_enabled, w.config_file_url, w.root_path, 0 as modified FROM " . DB_PREFIX . "product p INNER JOIN " . DB_PREFIX . "product_description pd ON p.product_id = pd.product_id AND pd.language_id = " . $defLngId . " LEFT JOIN $_tableName w on p.product_id = w.product_id ORDER BY product_id;");
        return $query->rows;
    }

	public function addProduct($data)
    {
        $_tableName = DB_PREFIX . "wr360product";
		$this->db->query("INSERT INTO $_tableName SET product_id = '" . (int)$data['product_id'] . "', root_path = '" . $data['root_path'] . "', config_file_url = '" . $data['config_file_url'] . "', wr360_enabled = '" . (int)$data['wr360_enabled'] . "'");
		$product_id = $this->db->getLastId();
        return $product_id;
	}

	public function saveProducts($submitProducts) {
        foreach($submitProducts as $data) {
            $existing = $this->getProduct($data['product_id']);
            if ($existing != null) {
                $this->editProduct($data['product_id'], $data);
            } else {
                $this->addProduct($data);
            }
        }
    }
    
    public function editProduct($product_id, $data)
    {
        $_tableName = DB_PREFIX . "wr360product";
		$this->db->query("UPDATE $_tableName SET root_path = '" . $data['root_path'] . "', config_file_url = '" . $data['config_file_url'] . "', wr360_enabled = '" . (int)$data['wr360_enabled'] . "' WHERE product_id = '" . (int)$product_id . "'");
	}

	public function deleteProduct($product_id) {
        $_tableName = DB_PREFIX . "wr360product";
		$this->db->query("DELETE FROM $_tableName WHERE product_id = '" . $product_id . "'");
	}

	public function getProduct($product_id) {
        $_tableName = DB_PREFIX . "wr360product";
		$query = $this->db->query("SELECT * FROM $_tableName WHERE product_id = '" . (int)$product_id . "'");
		return $query->row;
	}
}
