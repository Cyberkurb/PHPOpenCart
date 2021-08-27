<?php
class Thiessens_ModelCatalogProduct extends ModelCatalogProduct {
	
	public function getProductRelated($product_id) {
		
		$total = 4;
		
		$product_data = array();

		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "product_related pr LEFT JOIN " . DB_PREFIX . "product p ON (pr.related_id = p.product_id) LEFT JOIN " . DB_PREFIX . "product_to_store p2s ON (p.product_id = p2s.product_id) WHERE pr.product_id = '" . (int)$product_id . "' AND p.status = '1' AND p.date_available <= NOW() AND p2s.store_id = '" . (int)$this->config->get('config_store_id') . "'");

		foreach ($query->rows as $result) {
			$product_data[$result['related_id']] = $this->getProduct($result['related_id']);
		}
		
		if(count($product_data) < 4) {
		
			$productCategoryIDs = array();
			
			if(!empty($this->request->get['path'])) {
				$productCategoryIDs = explode('_', (string)$this->request->get['path']);
			}
			
			if(empty($productCategoryIDs)) {
				
				$productCategoriesQuery = $this->db->query("SELECT category_id FROM " . DB_PREFIX . "product_to_category ptc WHERE ptc.product_id = '" . (int)$product_id . "'");
			
				foreach ($productCategoriesQuery->rows as $result) {
					$productCategoryIDs[] = $result['category_id'];
				}
			}
			
			$query_string = "SELECT * FROM " . DB_PREFIX . "product p LEFT JOIN " . DB_PREFIX . "product_to_store p2s ON (p.product_id = p2s.product_id) LEFT JOIN " . DB_PREFIX . "product_to_category p2c		ON (p.product_id = p2c.product_id) WHERE p.product_id != '". $product_id ."' AND ";
			
			if(!empty($productCategoryIDs)) {
				$query_string .= "p2c.category_id IN (". implode(",", $productCategoryIDs) .") AND ";
			}
			
			$query_string .= "p.status = '1' AND p.date_available <= NOW() AND p2s.store_id = '" . (int)$this->config->get('config_store_id') . "' ORDER BY RAND() LIMIT 6"; // ($total - count($product_data))
			
			$additionalProducts = $query = $this->db->query($query_string);
			
			foreach ($additionalProducts->rows as $result) {
				$product_data[$result['product_id']] = $this->getProduct($result['product_id']);
				
				if(count($product_data) == 4) {
					break;
				}
			}
		}
			
		return $product_data;
	}
}
