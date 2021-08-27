<?php
class ModelAccountRecipe extends Model {
	public function getShoppingList($customer_id) {
        $sql = "SELECT";
        $sql .= " " . DB_PREFIX . "customer_shoppinglist.product_id AS product_id,";
        $sql .= " " . DB_PREFIX . "product_description.name AS recipe_name,";
        $sql .= " " . DB_PREFIX . "customer_shoppinglist.shoppinglist_group_id AS shoppinglist_group_id,";
        $sql .= " " . DB_PREFIX . "customer_slgroup.name AS group_name,";
        $sql .= " " . DB_PREFIX . "customer_shoppinglist.attribute_id AS attribute_id,";
        $sql .= " IF(" . DB_PREFIX . "customer_shoppinglist.attribute_id > 0, " . DB_PREFIX . "attribute_description.name, " . DB_PREFIX . "customer_shoppinglist.details) AS attribute_name,";
        $sql .= " IF(" . DB_PREFIX . "customer_shoppinglist.attribute_id > 0, " . DB_PREFIX . "customer_shoppinglist.details, '') AS attribute_text";
        $sql .= " FROM " . DB_PREFIX . "customer_shoppinglist";
        $sql .= " LEFT JOIN " . DB_PREFIX . "product_description ON " . DB_PREFIX . "product_description.product_id = " . DB_PREFIX . "customer_shoppinglist.product_id";
        $sql .= " LEFT JOIN " . DB_PREFIX . "customer_slgroup on " . DB_PREFIX . "customer_slgroup.shoppinglist_group_id = " . DB_PREFIX . "customer_shoppinglist.shoppinglist_group_id";
        $sql .= " LEFT JOIN " . DB_PREFIX . "attribute_description ON " . DB_PREFIX . "attribute_description.attribute_id = " . DB_PREFIX . "customer_shoppinglist.attribute_id";
        $sql .= " WHERE " . DB_PREFIX . "customer_shoppinglist.customer_id = '" . (int)$customer_id . "'";
        
		$query = $this->db->query($sql);

		return $query->rows;
	}
	public function addNonRecipe($customer_id, $details) {
        $sql = "INSERT INTO " . DB_PREFIX . "customer_shoppinglist";
        $sql .= " (customer_id, details, date_added, date_modified)";
        $sql .= " VALUES ('" . (int)$customer_id . "',";
        $sql .= " '" . $details['details'] . "',";
        $sql .= " , now(), now())";

        $query = $this->db->query($sql);
	}
    public function getListItem($shoppinglist_id){
        $sql = "SELECT * FROM " . DB_PREFIX . "customer_shoppinglist WHERE";
        $sql .= " shoppinglist_id = '" . (int)$shoppinglist_id . "'";
        $query = $this->db->query($sql);

        return $query->row;
    }
    public function ShoppingListUpdate($data){
        $sql = "UPDATE " . DB_PREFIX . "customer_shoppinglist SET";
        $sql .= " date_modified = now()";
        if(isset($data['details'])){
            $sql .= ", details = '" . $this->db->escape($data['details']) . "'"; 
        }
        if(isset($data['status'])){
            $sql .= ", status = '" . (int)$data['status'] . "'";
        }
        if(isset($data['shoppinglist_group_id'])){
            $sql .= ", shoppinglist_group_id = '" . (int)$data['shoppinglist_group_id'] . "'";
        }
        $sql .= " WHERE shoppinglist_id = '" . (int)$data['shoppinglist_id'] . "';";

        $query = $this->db->query($sql);
    }
    public function addFullRecipe($data){
        $sql = "INSERT INTO " . DB_PREFIX . "customer_shoppinglist";
        $sql .= " (shoppinglist_group_id, customer_id, product_id, attribute_id, details, shoppinglist_status_id, date_added, date_modified)";
        $sql .= " SELECT  0 AS shoppinglist_group_id,";
        $sql .= " '" . (int)$data['customer_id'] . "' AS customer_id,";
        $sql .= " " . DB_PREFIX . "product_attribute.product_id AS product_id,";
        $sql .= " " . DB_PREFIX . "product_attribute.attribute_id AS attribute_id,";
        $sql .= " " . DB_PREFIX . "product_attribute.text AS details,";
        $sql .= " 1 AS shoppinglist_status_id,";
        $sql .= " now() AS date_added,";
        $sql .= " now() AS date_modified";
        $sql .= " FROM " . DB_PREFIX . "product_attribute";
        $sql .= " JOIN " . DB_PREFIX . "attribute ON " . DB_PREFIX . "attribute.attribute_id = " . DB_PREFIX . "product_attribute.attribute_id";
        $sql .= " WHERE " . DB_PREFIX . "product_attribute.product_id = '" . (int)$data['product_id'] . "' AND " . DB_PREFIX . "attribute.attribute_group_id = 9;";

        $query = $this->db->query($sql);
    }
}