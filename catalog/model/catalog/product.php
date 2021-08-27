<?php
class ModelCatalogProduct extends Model {
	public function updateViewed($product_id) {
		$this->db->query("UPDATE " . DB_PREFIX . "product SET viewed = (viewed + 1) WHERE product_id = '" . (int)$product_id . "'");
	}

	public function getProduct($product_id) {
		$query = $this->db->query("SELECT DISTINCT *, pd.name AS name, p.image, m.name AS manufacturer, (SELECT price FROM " . DB_PREFIX . "product_discount pd2 WHERE pd2.product_id = p.product_id AND pd2.customer_group_id = '" . (int)$this->config->get('config_customer_group_id') . "' AND pd2.quantity = '1' AND ((pd2.date_start = '0000-00-00' OR pd2.date_start < NOW()) AND (pd2.date_end = '0000-00-00' OR pd2.date_end > NOW())) ORDER BY pd2.priority ASC, pd2.price ASC LIMIT 1) AS discount, (SELECT price FROM " . DB_PREFIX . "product_special ps WHERE ps.product_id = p.product_id AND ps.customer_group_id = '" . (int)$this->config->get('config_customer_group_id') . "' AND ((ps.date_start = '0000-00-00' OR ps.date_start < DATE_SUB(NOW(), INTERVAL 7 HOUR)) AND (ps.date_end = '0000-00-00' OR ps.date_end > DATE_SUB(NOW(), INTERVAL 7 HOUR))) ORDER BY ps.priority ASC, ps.price ASC LIMIT 1) AS special, (SELECT points FROM " . DB_PREFIX . "product_reward pr WHERE pr.product_id = p.product_id AND pr.customer_group_id = '" . (int)$this->config->get('config_customer_group_id') . "') AS reward, (SELECT ss.name FROM " . DB_PREFIX . "stock_status ss WHERE ss.stock_status_id = p.stock_status_id AND ss.language_id = '" . (int)$this->config->get('config_language_id') . "') AS stock_status, (SELECT wcd.unit FROM " . DB_PREFIX . "weight_class_description wcd WHERE p.weight_class_id = wcd.weight_class_id AND wcd.language_id = '" . (int)$this->config->get('config_language_id') . "') AS weight_class, (SELECT lcd.unit FROM " . DB_PREFIX . "length_class_description lcd WHERE p.length_class_id = lcd.length_class_id AND lcd.language_id = '" . (int)$this->config->get('config_language_id') . "') AS length_class, (SELECT AVG(rating) AS total FROM " . DB_PREFIX . "review r1 WHERE r1.product_id = p.product_id AND r1.status = '1' GROUP BY r1.product_id) AS rating, (SELECT COUNT(*) AS total FROM " . DB_PREFIX . "review r2 WHERE r2.product_id = p.product_id AND r2.status = '1' GROUP BY r2.product_id) AS reviews, p.sort_order FROM " . DB_PREFIX . "product p LEFT JOIN " . DB_PREFIX . "product_description pd ON (p.product_id = pd.product_id) LEFT JOIN " . DB_PREFIX . "product_to_store p2s ON (p.product_id = p2s.product_id) LEFT JOIN " . DB_PREFIX . "manufacturer m ON (p.manufacturer_id = m.manufacturer_id) WHERE p.product_id = '" . (int)$product_id . "' AND pd.language_id = '" . (int)$this->config->get('config_language_id') . "' AND p.status = '1' AND p.date_available <= NOW() AND p2s.store_id = '" . (int)$this->config->get('config_store_id') . "'");

		if ($query->num_rows) {
			
			if($this->config->get('config_currency') == 'CAD'){
				$qty = $query->row['quantity_ca'];
			}
			else{
				$qty = $query->row['quantity'];
			}
			
			
			return array(
				'product_id'       => $query->row['product_id'],
				'name'             => $query->row['name'],
				'description'      => $query->row['description'],
                'short_description'=> $query->row['shortdescription'],
				'meta_title'       => $query->row['meta_title'],
				'meta_description' => $query->row['meta_description'],
				'meta_keyword'     => $query->row['meta_keyword'],
				'tag'              => $query->row['tag'],
				'model'            => $query->row['model'],
				'sku'              => $query->row['sku'],
				'upc'              => $query->row['upc'],
				'ean'              => $query->row['ean'],
				'jan'              => $query->row['jan'],
				'isbn'             => $query->row['isbn'],
				'mpn'              => $query->row['mpn'],
				'location'         => $query->row['location'],
				'quantity'         => $qty,
				'stock_status'     => $query->row['stock_status'],
				'image'            => $query->row['image'],
                'video'            => $query->row['video'],
				'manufacturer_id'  => $query->row['manufacturer_id'],
				'manufacturer'     => $query->row['manufacturer'],
				'price'            => ($query->row['discount'] ? $query->row['discount'] : $query->row['price']),
				'special'          => $query->row['special'],
				'reward'           => $query->row['reward'],
				'points'           => $query->row['points'],
				'tax_class_id'     => $query->row['tax_class_id'],
				'date_available'   => $query->row['date_available'],
				'weight'           => $query->row['weight'],
				'weight_class_id'  => $query->row['weight_class_id'],
				'length'           => $query->row['length'],
				'width'            => $query->row['width'],
				'height'           => $query->row['height'],
				'length_class_id'  => $query->row['length_class_id'],
				'subtract'         => $query->row['subtract'],
				'rating'           => round($query->row['rating']),
				'reviews'          => $query->row['reviews'] ? $query->row['reviews'] : 0,
				'minimum'          => $query->row['minimum'],
				'sort_order'       => $query->row['sort_order'],
				'status'           => $query->row['status'],
				'date_added'       => $query->row['date_added'],
				'date_modified'    => $query->row['date_modified'],
				'viewed'           => $query->row['viewed'],
				'quantity_wa'      => $query->row['quantity_wa'],
				'quantity_ca'      => $query->row['quantity_ca'],
				'quantity_anoka'   => $query->row['quantity_anoka'],
				'quantity_phxdc'   => $query->row['quantity_phxdc'],
				'quantity_europe'  => $query->row['quantity_europe'],
				'quantity_phoenix' => $query->row['quantity_phoenix'],
				'quantity_transit' => $query->row['quantity_transit'],
				'quantity_amazen'  => $query->row['quantity_amazen'],
				'quantity_fife'  => $query->row['quantity_fife'],
				'expected_availablity' => $query->row['expected_availablity'],
				'quantity_onwater' => $query->row['quantity_onwater']
			);
		} else {
			return false;
		}
	}

	public function getProduct_oe($product_id) {
		$query = $this->db->query("SELECT DISTINCT *, pd.name AS name, p.image, m.name AS manufacturer, (SELECT price FROM " . DB_PREFIX . "product_discount pd2 WHERE pd2.product_id = p.product_id AND pd2.customer_group_id = '" . (int)$this->config->get('config_customer_group_id') . "' AND pd2.quantity = '1' AND ((pd2.date_start = '0000-00-00' OR pd2.date_start < NOW()) AND (pd2.date_end = '0000-00-00' OR pd2.date_end > NOW())) ORDER BY pd2.priority ASC, pd2.price ASC LIMIT 1) AS discount, (SELECT price FROM " . DB_PREFIX . "product_special ps WHERE ps.product_id = p.product_id AND ps.customer_group_id = '" . (int)$this->config->get('config_customer_group_id') . "' AND ((ps.date_start = '0000-00-00' OR ps.date_start < NOW()) AND (ps.date_end = '0000-00-00' OR ps.date_end > NOW())) ORDER BY ps.priority ASC, ps.price ASC LIMIT 1) AS special, (SELECT points FROM " . DB_PREFIX . "product_reward pr WHERE pr.product_id = p.product_id AND pr.customer_group_id = '" . (int)$this->config->get('config_customer_group_id') . "') AS reward, (SELECT ss.name FROM " . DB_PREFIX . "stock_status ss WHERE ss.stock_status_id = p.stock_status_id AND ss.language_id = '" . (int)$this->config->get('config_language_id') . "') AS stock_status, (SELECT wcd.unit FROM " . DB_PREFIX . "weight_class_description wcd WHERE p.weight_class_id = wcd.weight_class_id AND wcd.language_id = '" . (int)$this->config->get('config_language_id') . "') AS weight_class, (SELECT lcd.unit FROM " . DB_PREFIX . "length_class_description lcd WHERE p.length_class_id = lcd.length_class_id AND lcd.language_id = '" . (int)$this->config->get('config_language_id') . "') AS length_class, (SELECT AVG(rating) AS total FROM " . DB_PREFIX . "review r1 WHERE r1.product_id = p.product_id AND r1.status = '1' GROUP BY r1.product_id) AS rating, (SELECT COUNT(*) AS total FROM " . DB_PREFIX . "review r2 WHERE r2.product_id = p.product_id AND r2.status = '1' GROUP BY r2.product_id) AS reviews, p.sort_order FROM " . DB_PREFIX . "product p LEFT JOIN " . DB_PREFIX . "product_description pd ON (p.product_id = pd.product_id) LEFT JOIN " . DB_PREFIX . "product_to_store p2s ON (p.product_id = p2s.product_id) LEFT JOIN " . DB_PREFIX . "manufacturer m ON (p.manufacturer_id = m.manufacturer_id) WHERE p.product_id = '" . (int)$product_id . "' AND pd.language_id = '" . (int)$this->config->get('config_language_id') . "' AND p.status = '1' AND p.date_available <= NOW() AND p2s.store_id = '" . (int)$this->config->get('config_store_id') . "'");

		if ($query->num_rows) {
			return array(
				'product_id'       => $query->row['product_id'],
				'name'             => $query->row['name'],
				'description'      => $query->row['description'],
				'model'            => $query->row['model'],
				'sku'              => $query->row['sku'],
				'upc'              => $query->row['upc'],
				'quantity'         => $query->row['quantity'],
				'image'            => $query->row['image'],
				'price'            => ($query->row['discount'] ? $query->row['discount'] : $query->row['price']),
				'special'          => $query->row['special'],
				'tax_class_id'     => $query->row['tax_class_id'],
				'date_available'   => $query->row['date_available'],
				'weight'           => $query->row['weight'],
				'weight_class_id'  => $query->row['weight_class_id'],
				'length'           => $query->row['length'],
				'width'            => $query->row['width'],
				'height'           => $query->row['height'],
				'length_class_id'  => $query->row['length_class_id'],
				'subtract'         => $query->row['subtract'],
				'quantity_wa'      => $query->row['quantity_wa'],
				'quantity_ca'      => $query->row['quantity_ca'],
				'quantity_anoka'   => $query->row['quantity_anoka'],
				'quantity_europe'  => $query->row['quantity_europe'],
				'quantity_phoenix' => $query->row['quantity_phoenix'],
				'quantity_phxdc'   => $query->row['quantity_phxdc'],
				'quantity_transit' => $query->row['quantity_transit'],
				'quantity_amazen'  => $query->row['quantity_amazen'],
				'quantity_fife'  => $query->row['quantity_fife'],
				'expected_availablity' => $query->row['expected_availablity'],
				'quantity_onwater' => $query->row['quantity_onwater'],
				'quantity_onorder' => $query->row['quantity_onorder']
			);
		} else {
			return false;
		}
	}

	public function getProducts($data = array()) {
		$sql = "SELECT p.product_id, (SELECT AVG(rating) AS total FROM " . DB_PREFIX . "review r1 WHERE r1.product_id = p.product_id AND r1.status = '1' GROUP BY r1.product_id) AS rating, (SELECT price FROM " . DB_PREFIX . "product_discount pd2 WHERE pd2.product_id = p.product_id AND pd2.customer_group_id = '" . (int)$this->config->get('config_customer_group_id') . "' AND pd2.quantity = '1' AND ((pd2.date_start = '0000-00-00' OR pd2.date_start < NOW()) AND (pd2.date_end = '0000-00-00' OR pd2.date_end > NOW())) ORDER BY pd2.priority ASC, pd2.price ASC LIMIT 1) AS discount, (SELECT price FROM " . DB_PREFIX . "product_special ps WHERE ps.product_id = p.product_id AND ps.customer_group_id = '" . (int)$this->config->get('config_customer_group_id') . "' AND ((ps.date_start = '0000-00-00' OR ps.date_start < NOW()) AND (ps.date_end = '0000-00-00' OR ps.date_end > NOW())) ORDER BY ps.priority ASC, ps.price ASC LIMIT 1) AS special";

		if (!empty($data['filter_category_id'])) {
			if (!empty($data['filter_sub_category'])) {
				$sql .= " FROM " . DB_PREFIX . "category_path cp LEFT JOIN " . DB_PREFIX . "product_to_category p2c ON (cp.category_id = p2c.category_id)";
			} else {
				$sql .= " FROM " . DB_PREFIX . "product_to_category p2c";
			}

			if (!empty($data['filter_filter'])) {
				$sql .= " LEFT JOIN " . DB_PREFIX . "product_filter pf ON (p2c.product_id = pf.product_id) LEFT JOIN " . DB_PREFIX . "product p ON (pf.product_id = p.product_id)";
			} else {
				$sql .= " LEFT JOIN " . DB_PREFIX . "product p ON (p2c.product_id = p.product_id)";
			}
		}
        
        else {
			$sql .= " FROM " . DB_PREFIX . "product p";
		}

		$sql .= " LEFT JOIN " . DB_PREFIX . "product_description pd ON (p.product_id = pd.product_id) LEFT JOIN " . DB_PREFIX . "product_to_store p2s ON (p.product_id = p2s.product_id) WHERE pd.language_id = '" . (int)$this->config->get('config_language_id') . "' AND p.status = '1' AND p.date_available <= NOW() AND p2s.store_id = '" . (int)$this->config->get('config_store_id') . "'";

		if (!empty($data['filter_category_id'])) {
			if (!empty($data['filter_sub_category'])) {
				$sql .= " AND cp.path_id = '" . (int)$data['filter_category_id'] . "'";
			} else {
				$sql .= " AND p2c.category_id = '" . (int)$data['filter_category_id'] . "'";
			}

			if (!empty($data['filter_filter'])) {
				$implode = array();

				$filters = explode(',', $data['filter_filter']);

				foreach ($filters as $filter_id) {
					$implode[] = (int)$filter_id;
				}

				$sql .= " AND pf.filter_id IN (" . implode(',', $implode) . ")";
			}
		}

		if (!empty($data['filter_name']) || !empty($data['filter_tag'])) {
			$sql .= " AND (";

			if (!empty($data['filter_name'])) {
				$implode = array();

				$words = explode(' ', trim(preg_replace('/\s+/', ' ', $data['filter_name'])));

				foreach ($words as $word) {
					$implode[] = "pd.name LIKE '%" . $this->db->escape($word) . "%'";
				}

				if ($implode) {
					$sql .= " " . implode(" AND ", $implode) . "";
				}

				if (!empty($data['filter_description'])) {
					$sql .= " OR pd.description LIKE '%" . $this->db->escape($data['filter_name']) . "%'";
				}
			}

			if (!empty($data['filter_name']) && !empty($data['filter_tag'])) {
				$sql .= " OR ";
			}

			if (!empty($data['filter_tag'])) {
				$implode = array();

				$words = explode(' ', trim(preg_replace('/\s+/', ' ', $data['filter_tag'])));

				foreach ($words as $word) {
					$implode[] = "pd.tag LIKE '%" . $this->db->escape($word) . "%'";
				}

				if ($implode) {
					$sql .= " " . implode(" AND ", $implode) . "";
				}
			}

			if (!empty($data['filter_name'])) {
				$sql .= " OR LCASE(p.model) = '" . $this->db->escape(utf8_strtolower($data['filter_name'])) . "'";
				$sql .= " OR LCASE(p.sku) = '" . $this->db->escape(utf8_strtolower($data['filter_name'])) . "'";
				$sql .= " OR LCASE(p.upc) = '" . $this->db->escape(utf8_strtolower($data['filter_name'])) . "'";
				$sql .= " OR LCASE(p.ean) = '" . $this->db->escape(utf8_strtolower($data['filter_name'])) . "'";
				$sql .= " OR LCASE(p.jan) = '" . $this->db->escape(utf8_strtolower($data['filter_name'])) . "'";
				$sql .= " OR LCASE(p.isbn) = '" . $this->db->escape(utf8_strtolower($data['filter_name'])) . "'";
				$sql .= " OR LCASE(p.mpn) = '" . $this->db->escape(utf8_strtolower($data['filter_name'])) . "'";
			}

			$sql .= ")";
		}

		if (!empty($data['filter_manufacturer_id'])) {
			$sql .= " AND p.manufacturer_id = '" . (int)$data['filter_manufacturer_id'] . "'";
		}

		$sql .= " GROUP BY p.product_id";

		$sort_data = array(
			'pd.name',
			'p.model',
			'p.quantity',
			'p.price',
			'rating',
			'p.sort_order',
			'p.date_added'
		);

		if (isset($data['sort']) && in_array($data['sort'], $sort_data)) {
			if ($data['sort'] == 'pd.name' || $data['sort'] == 'p.model') {
				$sql .= " ORDER BY LCASE(" . $data['sort'] . ")";
			} elseif ($data['sort'] == 'p.price') {
				$sql .= " ORDER BY (CASE WHEN special IS NOT NULL THEN special WHEN discount IS NOT NULL THEN discount ELSE p.price END)";
			} else {
				$sql .= " ORDER BY " . $data['sort'];
			}
		} else {
			$sql .= " ORDER BY p.sort_order";
		}

		if (isset($data['order']) && ($data['order'] == 'DESC')) {
			$sql .= " DESC, LCASE(pd.name) DESC";
		} else {
			$sql .= " ASC, p.product_id DESC";
		}

		if (isset($data['start']) || isset($data['limit'])) {
			if ($data['start'] < 0) {
				$data['start'] = 0;
			}

			if ($data['limit'] < 1) {
				$data['limit'] = 20;
			}

			$sql .= " LIMIT " . (int)$data['start'] . "," . (int)$data['limit'];
		}

		$product_data = array();

		$query = $this->db->query($sql);

		foreach ($query->rows as $result) {
			$product_data[$result['product_id']] = $this->getProduct($result['product_id']);
		}

		return $product_data;
	}

	public function getProductSpecials($data = array()) {
		$sql = "SELECT DISTINCT ps.product_id, (SELECT AVG(rating) FROM " . DB_PREFIX . "review r1 WHERE r1.product_id = ps.product_id AND r1.status = '1' GROUP BY r1.product_id) AS rating FROM " . DB_PREFIX . "product_special ps LEFT JOIN " . DB_PREFIX . "product p ON (ps.product_id = p.product_id) LEFT JOIN " . DB_PREFIX . "product_description pd ON (p.product_id = pd.product_id) LEFT JOIN " . DB_PREFIX . "product_to_store p2s ON (p.product_id = p2s.product_id) WHERE p.status = '1' AND p.date_available <= NOW() AND p2s.store_id = '" . (int)$this->config->get('config_store_id') . "' AND ps.customer_group_id = '" . (int)$this->config->get('config_customer_group_id') . "' AND ((ps.date_start = '0000-00-00' OR ps.date_start < NOW()) AND (ps.date_end = '0000-00-00' OR ps.date_end > NOW())) GROUP BY ps.product_id";

		$sort_data = array(
			'pd.name',
			'p.model',
			'ps.price',
			'rating',
			'p.sort_order'
		);

		if (isset($data['sort']) && in_array($data['sort'], $sort_data)) {
			if ($data['sort'] == 'pd.name' || $data['sort'] == 'p.model') {
				$sql .= " ORDER BY LCASE(" . $data['sort'] . ")";
			} else {
				$sql .= " ORDER BY " . $data['sort'];
			}
		} else {
			$sql .= " ORDER BY p.sort_order";
		}

		if (isset($data['order']) && ($data['order'] == 'DESC')) {
			$sql .= " DESC, LCASE(pd.name) DESC";
		} else {
			$sql .= " ASC, LCASE(pd.name) ASC";
		}

		if (isset($data['start']) || isset($data['limit'])) {
			if ($data['start'] < 0) {
				$data['start'] = 0;
			}

			if ($data['limit'] < 1) {
				$data['limit'] = 20;
			}

			$sql .= " LIMIT " . (int)$data['start'] . "," . (int)$data['limit'];
		}

		$product_data = array();

		$query = $this->db->query($sql);

		foreach ($query->rows as $result) {
			$product_data[$result['product_id']] = $this->getProduct($result['product_id']);
		}

		return $product_data;
	}

	public function getLatestProducts($limit) {
		$product_data = $this->cache->get('product.latest.' . (int)$this->config->get('config_language_id') . '.' . (int)$this->config->get('config_store_id') . '.' . $this->config->get('config_customer_group_id') . '.' . (int)$limit);

		if (!$product_data) {
			$query = $this->db->query("SELECT p.product_id FROM " . DB_PREFIX . "product p LEFT JOIN " . DB_PREFIX . "product_to_store p2s ON (p.product_id = p2s.product_id) WHERE p.status = '1' AND p.date_available <= NOW() AND p2s.store_id = '" . (int)$this->config->get('config_store_id') . "' ORDER BY p.date_added DESC LIMIT " . (int)$limit);

			foreach ($query->rows as $result) {
				$product_data[$result['product_id']] = $this->getProduct($result['product_id']);
			}

			$this->cache->set('product.latest.' . (int)$this->config->get('config_language_id') . '.' . (int)$this->config->get('config_store_id') . '.' . $this->config->get('config_customer_group_id') . '.' . (int)$limit, $product_data);
		}

		return $product_data;
	}

	public function getPopularProducts($limit) {
		$product_data = $this->cache->get('product.popular.' . (int)$this->config->get('config_language_id') . '.' . (int)$this->config->get('config_store_id') . '.' . $this->config->get('config_customer_group_id') . '.' . (int)$limit);
	
		if (!$product_data) {
			$query = $this->db->query("SELECT p.product_id FROM " . DB_PREFIX . "product p LEFT JOIN " . DB_PREFIX . "product_to_store p2s ON (p.product_id = p2s.product_id) WHERE p.status = '1' AND p.date_available <= NOW() AND p2s.store_id = '" . (int)$this->config->get('config_store_id') . "' ORDER BY p.viewed DESC, p.date_added DESC LIMIT " . (int)$limit);
	
			foreach ($query->rows as $result) {
				$product_data[$result['product_id']] = $this->getProduct($result['product_id']);
			}
			
			$this->cache->set('product.popular.' . (int)$this->config->get('config_language_id') . '.' . (int)$this->config->get('config_store_id') . '.' . $this->config->get('config_customer_group_id') . '.' . (int)$limit, $product_data);
		}
		
		return $product_data;
	}

	public function getBestSellerProducts($limit) {
		$product_data = $this->cache->get('product.bestseller.' . (int)$this->config->get('config_language_id') . '.' . (int)$this->config->get('config_store_id') . '.' . $this->config->get('config_customer_group_id') . '.' . (int)$limit);

		if (!$product_data) {
			$product_data = array();

			$query = $this->db->query("SELECT op.product_id, SUM(op.quantity) AS total FROM " . DB_PREFIX . "order_product op LEFT JOIN `" . DB_PREFIX . "order` o ON (op.order_id = o.order_id) LEFT JOIN `" . DB_PREFIX . "product` p ON (op.product_id = p.product_id) LEFT JOIN " . DB_PREFIX . "product_to_store p2s ON (p.product_id = p2s.product_id) WHERE o.order_status_id > '0' AND p.status = '1' AND p.date_available <= NOW() AND p2s.store_id = '" . (int)$this->config->get('config_store_id') . "' GROUP BY op.product_id ORDER BY total DESC LIMIT " . (int)$limit);

			foreach ($query->rows as $result) {
				$product_data[$result['product_id']] = $this->getProduct($result['product_id']);
			}

			$this->cache->set('product.bestseller.' . (int)$this->config->get('config_language_id') . '.' . (int)$this->config->get('config_store_id') . '.' . $this->config->get('config_customer_group_id') . '.' . (int)$limit, $product_data);
		}

		return $product_data;
	}

	public function getProductAttributes($product_id) {
		$product_attribute_group_data = array();

		$product_attribute_group_query = $this->db->query("SELECT ag.attribute_group_id, agd.name FROM " . DB_PREFIX . "product_attribute pa LEFT JOIN " . DB_PREFIX . "attribute a ON (pa.attribute_id = a.attribute_id) LEFT JOIN " . DB_PREFIX . "attribute_group ag ON (a.attribute_group_id = ag.attribute_group_id) LEFT JOIN " . DB_PREFIX . "attribute_group_description agd ON (ag.attribute_group_id = agd.attribute_group_id) WHERE pa.product_id = '" . (int)$product_id . "' AND agd.language_id = '" . (int)$this->config->get('config_language_id') . "' GROUP BY ag.attribute_group_id ORDER BY ag.sort_order, agd.name");

		foreach ($product_attribute_group_query->rows as $product_attribute_group) {
			$product_attribute_data = array();

			$product_attribute_query = $this->db->query("SELECT a.attribute_id, ad.name, pa.text FROM " . DB_PREFIX . "product_attribute pa LEFT JOIN " . DB_PREFIX . "attribute a ON (pa.attribute_id = a.attribute_id) LEFT JOIN " . DB_PREFIX . "attribute_description ad ON (a.attribute_id = ad.attribute_id) WHERE pa.product_id = '" . (int)$product_id . "' AND a.attribute_group_id = '" . (int)$product_attribute_group['attribute_group_id'] . "' AND ad.language_id = '" . (int)$this->config->get('config_language_id') . "' AND pa.language_id = '" . (int)$this->config->get('config_language_id') . "' ORDER BY a.sort_order, ad.name");

			foreach ($product_attribute_query->rows as $product_attribute) {
				$product_attribute_data[] = array(
					'attribute_id' => $product_attribute['attribute_id'],
					'name'         => $product_attribute['name'],
					'text'         => $product_attribute['text']
				);
			}

			$product_attribute_group_data[] = array(
				'attribute_group_id' => $product_attribute_group['attribute_group_id'],
				'name'               => $product_attribute_group['name'],
				'attribute'          => $product_attribute_data
			);
		}

		return $product_attribute_group_data;
	}

	public function getProductOptions($product_id) {
		$product_option_data = array();

		$product_option_query = $this->db->query("SELECT * FROM " . DB_PREFIX . "product_option po LEFT JOIN `" . DB_PREFIX . "option` o ON (po.option_id = o.option_id) LEFT JOIN " . DB_PREFIX . "option_description od ON (o.option_id = od.option_id) WHERE po.product_id = '" . (int)$product_id . "' AND od.language_id = '" . (int)$this->config->get('config_language_id') . "' ORDER BY o.sort_order");

		foreach ($product_option_query->rows as $product_option) {
			$product_option_value_data = array();

			$product_option_value_query = $this->db->query("SELECT *, (SELECT ss.name FROM " . DB_PREFIX . "stock_status ss WHERE ss.language_id = " . (int) $this->config->get('config_language_id') . " AND ss.stock_status_id = pov.stock_status_id) AS stock_status_id FROM " . DB_PREFIX . "product_option_value pov LEFT JOIN " . DB_PREFIX . "option_value ov ON (pov.option_value_id = ov.option_value_id) LEFT JOIN " . DB_PREFIX . "option_value_description ovd ON (ov.option_value_id = ovd.option_value_id) WHERE pov.product_id = '" . (int)$product_id . "' AND pov.product_option_id = '" . (int)$product_option['product_option_id'] . "' AND ovd.language_id = '" . (int)$this->config->get('config_language_id') . "' ORDER BY ov.sort_order");

			foreach ($product_option_value_query->rows as $product_option_value) {
				$product_option_value_data[] = array(
					'model'                => $product_option_value['model'],
        			'stock_status_id'      => $product_option_value['stock_status_id'],
					'product_option_value_id' => $product_option_value['product_option_value_id'],
					'option_value_id'         => $product_option_value['option_value_id'],
					'name'                    => $product_option_value['name'],
					'image'                   => $product_option_value['image'],
					'quantity'                => $product_option_value['quantity'],
					'subtract'                => $product_option_value['subtract'],
					'price'                   => $product_option_value['price'],
					'price_prefix'            => $product_option_value['price_prefix'],
					'weight'                  => $product_option_value['weight'],
					'weight_prefix'           => $product_option_value['weight_prefix']
				);
			}

			$product_option_data[] = array(
				'product_option_id'    => $product_option['product_option_id'],
				'product_option_value' => $product_option_value_data,
				'option_id'            => $product_option['option_id'],
				'name'                 => $product_option['name'],
				'type'                 => $product_option['type'],
				'value'                => $product_option['value'],
				'required'             => $product_option['required']
			);
		}

		return $product_option_data;
	}

	public function getProductDiscounts($product_id) {
		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "product_discount WHERE product_id = '" . (int)$product_id . "' AND customer_group_id = '" . (int)$this->config->get('config_customer_group_id') . "' AND quantity > 1 AND ((date_start = '0000-00-00' OR date_start < NOW()) AND (date_end = '0000-00-00' OR date_end > NOW())) ORDER BY quantity ASC, priority ASC, price ASC");

		return $query->rows;
	}

	public function getProductImages($product_id) {
		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "product_image WHERE product_id = '" . (int)$product_id . "' ORDER BY sort_order ASC");

		return $query->rows;
	}

	public function getProductHover($product_id) {
		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "product_image WHERE product_id = '" . (int)$product_id . "' ORDER BY sort_order ASC LIMIT 1");

		return $query->row;
	}

	public function getProductRelated($product_id) {
		$product_data = array();
		$query = $this->db->query("SELECT count(*) AS totalfound FROM " . DB_PREFIX . "product_related pr LEFT JOIN " . DB_PREFIX . "product p ON (pr.related_id = p.product_id) LEFT JOIN " . DB_PREFIX . "product_to_store p2s ON (p.product_id = p2s.product_id) WHERE pr.product_id = '" . (int)$product_id . "' AND p.status = '1' AND p.date_available <= NOW() AND p2s.store_id = '" . (int)$this->config->get('config_store_id') . "'");
		if($query->row['totalfound'] < 4){
			$qp_sql = "SELECT " . DB_PREFIX . "product.product_id AS product_id FROM " . DB_PREFIX . "product";
			$qp_sql .= " JOIN " . DB_PREFIX . "product_to_store ON " . DB_PREFIX . "product_to_store.product_id = " . DB_PREFIX . "product.product_id";
			$qp_sql .= " JOIN " . DB_PREFIX . "product_to_category ON " . DB_PREFIX . "product_to_category.product_id = " . DB_PREFIX . "product.product_id";
			$qp_sql .= " WHERE " . DB_PREFIX . "product_to_store.store_id = '" . (int)$this->config->get('config_store_id') . "'";
			$qp_sql .= " AND " . DB_PREFIX . "product_to_category.category_id IN (59, 60, 67, 68, 69, 70, 72, 75, 80, 81, 82, 83, 114, 120, 121, 123, 124, 127, 129, 130, 131, 132, 133, 149, 148) AND " . DB_PREFIX . "product.status = 1";
			$qp_sql .= " ORDER BY RAND() LIMIT 9;";
			$qr_sql = "SELECT " . DB_PREFIX . "product.product_id AS product_id FROM " . DB_PREFIX . "product";
			$qr_sql .= " JOIN " . DB_PREFIX . "product_to_store ON " . DB_PREFIX . "product_to_store.product_id = " . DB_PREFIX . "product.product_id";
			$qr_sql .= " JOIN " . DB_PREFIX . "product_to_category ON " . DB_PREFIX . "product_to_category.product_id = " . DB_PREFIX . "product.product_id";
			$qr_sql .= " WHERE " . DB_PREFIX . "product_to_store.store_id = '" . (int)$this->config->get('config_store_id') . "'";
			$qr_sql .= " AND " . DB_PREFIX . "product_to_category.category_id IN (78, 79, 84, 85, 86, 87, 88, 89, 90, 91, 92, 93, 94, 95, 96, 97, 98, 99, 100, 101, 102, 103, 104, 105, 106, 107, 108, 109, 110, 111, 112, 113, 115, 146) AND " . DB_PREFIX . "product.status = 1";
			$qr_sql .= " ORDER BY RAND() LIMIT 9;";
			$query_products = $this->db->query($qp_sql);
			$query_recipes = $this->db->query($qr_sql);

			foreach ($query_products->rows as $result) {
				$product_data[$result['product_id']] = $this->getProduct($result['product_id']);
			}
			foreach ($query_recipes->rows as $resultr) {
				$product_data[$resultr['product_id']] = $this->getProduct($resultr['product_id']);
			}

		}
		else{
			$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "product_related pr LEFT JOIN " . DB_PREFIX . "product p ON (pr.related_id = p.product_id) LEFT JOIN " . DB_PREFIX . "product_to_store p2s ON (p.product_id = p2s.product_id) WHERE pr.product_id = '" . (int)$product_id . "' AND p.status = '1' AND p.date_available <= NOW() AND p2s.store_id = '" . (int)$this->config->get('config_store_id') . "'");
			foreach ($query->rows as $result) {
				$product_data[$result['related_id']] = $this->getProduct($result['related_id']);
			}
		}
		return $product_data;
	}

	public function getFeatured() {
		$product_data = array();
			$qg_sql = "SELECT " . DB_PREFIX . "product.product_id AS product_id FROM " . DB_PREFIX . "product";
			$qg_sql .= " JOIN " . DB_PREFIX . "product_to_store ON " . DB_PREFIX . "product_to_store.product_id = " . DB_PREFIX . "product.product_id";
			$qg_sql .= " JOIN " . DB_PREFIX . "product_to_category ON " . DB_PREFIX . "product_to_category.product_id = " . DB_PREFIX . "product.product_id";
			$qg_sql .= " WHERE " . DB_PREFIX . "product_to_category.category_id IN (60, 80, 81, 82, 83, 114, 121, 123, 124, 149, 148, 180, 171, 172, 173, 174) AND " . DB_PREFIX . "product.status = 1";
			$qg_sql .= " GROUP BY " . DB_PREFIX . "product.product_id ORDER BY RAND() LIMIT 9;";

			$qp_sql = "SELECT " . DB_PREFIX . "product.product_id AS product_id FROM " . DB_PREFIX . "product";
			$qp_sql .= " JOIN " . DB_PREFIX . "product_to_store ON " . DB_PREFIX . "product_to_store.product_id = " . DB_PREFIX . "product.product_id";
			$qp_sql .= " JOIN " . DB_PREFIX . "product_to_category ON " . DB_PREFIX . "product_to_category.product_id = " . DB_PREFIX . "product.product_id";
			$qp_sql .= " WHERE " . DB_PREFIX . "product_to_store.store_id = '" . (int)$this->config->get('config_store_id') . "'";
			$qp_sql .= " AND " . DB_PREFIX . "product_to_category.category_id IN (59, 67, 68, 69, 70, 72, 75, 120, 127, 129, 130, 131, 132, 133, 157, 158, 159, 160, 161, 162) AND " . DB_PREFIX . "product.status = 1";
			$qp_sql .= " GROUP BY " . DB_PREFIX . "product.product_id ORDER BY RAND() LIMIT 9;";
			
			$qr_sql = "SELECT " . DB_PREFIX . "product.product_id AS product_id FROM " . DB_PREFIX . "product";
			$qr_sql .= " JOIN " . DB_PREFIX . "product_to_store ON " . DB_PREFIX . "product_to_store.product_id = " . DB_PREFIX . "product.product_id";
			$qr_sql .= " JOIN " . DB_PREFIX . "product_to_category ON " . DB_PREFIX . "product_to_category.product_id = " . DB_PREFIX . "product.product_id";
			$qr_sql .= " WHERE " . DB_PREFIX . "product_to_store.store_id = '" . (int)$this->config->get('config_store_id') . "'";
			$qr_sql .= " AND " . DB_PREFIX . "product_to_category.category_id IN (78, 79, 84, 85, 86, 87, 88, 89, 90, 91, 92, 93, 94, 95, 96, 97, 98, 99, 100, 101, 102, 103, 104, 105, 106, 107, 108, 109, 110, 111, 112, 113, 115, 146) AND " . DB_PREFIX . "product.status = 1";
			$qr_sql .= " GROUP BY " . DB_PREFIX . "product.product_id ORDER BY date_added DESC LIMIT 9;";
			$query_grills = $this->db->query($qg_sql);
			$query_products = $this->db->query($qp_sql);
			$query_recipes = $this->db->query($qr_sql);

			foreach ($query_products->rows as $result) {
				$product_data[$result['product_id']] = $result['product_id'];
			}
			foreach ($query_grills->rows as $resultg) {
				$product_data[$resultg['product_id']] = $resultg['product_id'];
			}
			foreach ($query_recipes->rows as $resultr) {
				$product_data[$resultr['product_id']] = $resultr['product_id'];
			}

		return $product_data;
	}
	public function getWoodPellet() {
		$product_data = array();
			$qg_sql = "SELECT " . DB_PREFIX . "product.product_id AS product_id FROM " . DB_PREFIX . "product";
			$qg_sql .= " JOIN " . DB_PREFIX . "product_to_store ON " . DB_PREFIX . "product_to_store.product_id = " . DB_PREFIX . "product.product_id";
			$qg_sql .= " JOIN " . DB_PREFIX . "product_to_category ON " . DB_PREFIX . "product_to_category.product_id = " . DB_PREFIX . "product.product_id";
			$qg_sql .= " WHERE " . DB_PREFIX . "product_to_store.store_id = '" . (int)$this->config->get('config_store_id') . "'";
			$qg_sql .= " AND " . DB_PREFIX . "product_to_category.category_id IN (172) AND " . DB_PREFIX . "product.status = 1";
			$qg_sql .= " ORDER BY RAND() LIMIT 4;";
			$query_grills = $this->db->query($qg_sql);

			foreach ($query_grills->rows as $resultg) {
				$product_data[$resultg['product_id']] = $resultg['product_id'];
			}

		return $product_data;
	}
	public function getCharcoalGrill() {
		$product_data = array();
			$qg_sql = "SELECT " . DB_PREFIX . "product.product_id AS product_id FROM " . DB_PREFIX . "product";
			$qg_sql .= " JOIN " . DB_PREFIX . "product_to_store ON " . DB_PREFIX . "product_to_store.product_id = " . DB_PREFIX . "product.product_id";
			$qg_sql .= " JOIN " . DB_PREFIX . "product_to_category ON " . DB_PREFIX . "product_to_category.product_id = " . DB_PREFIX . "product.product_id";
			$qg_sql .= " WHERE " . DB_PREFIX . "product_to_store.store_id = '" . (int)$this->config->get('config_store_id') . "'";
			$qg_sql .= " AND " . DB_PREFIX . "product_to_category.category_id IN (173) AND " . DB_PREFIX . "product.status = 1";
			$qg_sql .= " ORDER BY RAND() LIMIT 4;";
			$query_grills = $this->db->query($qg_sql);

			foreach ($query_grills->rows as $resultg) {
				$product_data[$resultg['product_id']] = $resultg['product_id'];
			}

		return $product_data;
	}
	public function getGasGrill() {
		$product_data = array();
			$qg_sql = "SELECT " . DB_PREFIX . "product.product_id AS product_id FROM " . DB_PREFIX . "product";
			$qg_sql .= " JOIN " . DB_PREFIX . "product_to_store ON " . DB_PREFIX . "product_to_store.product_id = " . DB_PREFIX . "product.product_id";
			$qg_sql .= " JOIN " . DB_PREFIX . "product_to_category ON " . DB_PREFIX . "product_to_category.product_id = " . DB_PREFIX . "product.product_id";
			$qg_sql .= " WHERE " . DB_PREFIX . "product_to_store.store_id = '" . (int)$this->config->get('config_store_id') . "'";
			$qg_sql .= " AND " . DB_PREFIX . "product_to_category.category_id IN (174) AND " . DB_PREFIX . "product.status = 1";
			$qg_sql .= " ORDER BY RAND() LIMIT 4;";
			$query_grills = $this->db->query($qg_sql);

			foreach ($query_grills->rows as $resultg) {
				$product_data[$resultg['product_id']] = $resultg['product_id'];
			}

		return $product_data;
	}
	public function getAccessories() {
		$product_data = array();
			$qg_sql = "SELECT " . DB_PREFIX . "product.product_id AS product_id FROM " . DB_PREFIX . "product";
			$qg_sql .= " JOIN " . DB_PREFIX . "product_to_store ON " . DB_PREFIX . "product_to_store.product_id = " . DB_PREFIX . "product.product_id";
			$qg_sql .= " JOIN " . DB_PREFIX . "product_to_category ON " . DB_PREFIX . "product_to_category.product_id = " . DB_PREFIX . "product.product_id";
			$qg_sql .= " WHERE " . DB_PREFIX . "product_to_store.store_id = '" . (int)$this->config->get('config_store_id') . "'";
			$qg_sql .= " AND " . DB_PREFIX . "product_to_category.category_id IN (175) AND " . DB_PREFIX . "product.status = 1";
			$qg_sql .= " ORDER BY RAND() LIMIT 4;";
			$query_grills = $this->db->query($qg_sql);

			foreach ($query_grills->rows as $resultg) {
				$product_data[$resultg['product_id']] = $resultg['product_id'];
			}

		return $product_data;
	}
	public function getSmokers() {
		$product_data = array();
			$qg_sql = "SELECT " . DB_PREFIX . "product.product_id AS product_id FROM " . DB_PREFIX . "product";
			$qg_sql .= " JOIN " . DB_PREFIX . "product_to_store ON " . DB_PREFIX . "product_to_store.product_id = " . DB_PREFIX . "product.product_id";
			$qg_sql .= " JOIN " . DB_PREFIX . "product_to_category ON " . DB_PREFIX . "product_to_category.product_id = " . DB_PREFIX . "product.product_id";
			$qg_sql .= " WHERE " . DB_PREFIX . "product_to_store.store_id = '" . (int)$this->config->get('config_store_id') . "'";
			$qg_sql .= " AND " . DB_PREFIX . "product_to_category.category_id IN (180) AND " . DB_PREFIX . "product.status = 1";
			$qg_sql .= " ORDER BY RAND() LIMIT 4;";
			$query_grills = $this->db->query($qg_sql);

			foreach ($query_grills->rows as $resultg) {
				$product_data[$resultg['product_id']] = $resultg['product_id'];
			}

		return $product_data;
	}
	public function getAuProducts() {
		$product_data = array();
			$qg_sql = "SELECT " . DB_PREFIX . "product.product_id AS product_id FROM " . DB_PREFIX . "product";
			$qg_sql .= " JOIN " . DB_PREFIX . "product_to_store ON " . DB_PREFIX . "product_to_store.product_id = " . DB_PREFIX . "product.product_id";
			$qg_sql .= " JOIN " . DB_PREFIX . "product_to_category ON " . DB_PREFIX . "product_to_category.product_id = " . DB_PREFIX . "product.product_id";
			$qg_sql .= " WHERE " . DB_PREFIX . "product_to_store.store_id = '" . (int)$this->config->get('config_store_id') . "'";
			$qg_sql .= " AND " . DB_PREFIX . "product.status = 1";
			$qg_sql .= " ORDER BY RAND() LIMIT 4;";
			$query_grills = $this->db->query($qg_sql);

			foreach ($query_grills->rows as $resultg) {
				$product_data[$resultg['product_id']] = $resultg['product_id'];
			}

		return $product_data;
	}
	public function getProductLayoutId($product_id) {
		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "product_to_layout WHERE product_id = '" . (int)$product_id . "' AND store_id = '" . (int)$this->config->get('config_store_id') . "'");

		if ($query->num_rows) {
			return (int)$query->row['layout_id'];
		} else {
			return 0;
		}
	}

	public function getCategories($product_id) {
		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "product_to_category p2c JOIN " . DB_PREFIX . "category_to_store c2s ON (p2c.category_id = c2s.category_id) WHERE product_id = '" . (int)$product_id . "' AND c2s.store_id = '" . (int)$this->config->get('config_store_id') . "' ");

		return $query->rows;
	}
	public function getCategoriesByProductId($product_id) {
          $query = $this->db->query("SELECT pc.*, (!ISNULL(t1.parent_id) + !ISNULL(t2.parent_id) + !ISNULL(t3.parent_id) + !ISNULL(t4.parent_id) + !ISNULL(t5.parent_id))*1000 + IF(t1.sort_order>0,(1000-t1.sort_order),0) + IF(t2.sort_order>0,(1000-t2.sort_order),0) + IF(t3.sort_order>0,(1000-t3.sort_order),0) + IF(t4.sort_order>0,(1000-t4.sort_order),0) + IF(t5.sort_order>0,(1000-t5.sort_order),0) AS d FROM " . DB_PREFIX . "product_to_category pc LEFT JOIN " . DB_PREFIX . "category t1 ON t1.category_id = pc.category_id LEFT JOIN " . DB_PREFIX . "category t2 ON t1.parent_id = t2.category_id LEFT JOIN " . DB_PREFIX . "category t3 ON t2.parent_id = t3.category_id LEFT JOIN " . DB_PREFIX . "category t4 ON t3.parent_id = t4.category_id LEFT JOIN " . DB_PREFIX . "category t5 ON t4.parent_id = t5.category_id WHERE product_id = '" . (int)$product_id . "' ORDER BY d DESC");

          return $query->rows;
    }
	public function getTotalProducts($data = array()) {
		$sql = "SELECT COUNT(DISTINCT p.product_id) AS total";

		if (!empty($data['filter_category_id'])) {
			if (!empty($data['filter_sub_category'])) {
				$sql .= " FROM " . DB_PREFIX . "category_path cp LEFT JOIN " . DB_PREFIX . "product_to_category p2c ON (cp.category_id = p2c.category_id)";
			} else {
				$sql .= " FROM " . DB_PREFIX . "product_to_category p2c";
			}

			if (!empty($data['filter_filter'])) {
				$sql .= " LEFT JOIN " . DB_PREFIX . "product_filter pf ON (p2c.product_id = pf.product_id) LEFT JOIN " . DB_PREFIX . "product p ON (pf.product_id = p.product_id)";
			} else {
				$sql .= " LEFT JOIN " . DB_PREFIX . "product p ON (p2c.product_id = p.product_id)";
			}
		} else {
			$sql .= " FROM " . DB_PREFIX . "product p";
		}

		$sql .= " LEFT JOIN " . DB_PREFIX . "product_description pd ON (p.product_id = pd.product_id) LEFT JOIN " . DB_PREFIX . "product_to_store p2s ON (p.product_id = p2s.product_id) WHERE pd.language_id = '" . (int)$this->config->get('config_language_id') . "' AND p.status = '1' AND p.date_available <= NOW() AND p2s.store_id = '" . (int)$this->config->get('config_store_id') . "'";

		if (!empty($data['filter_category_id'])) {
			if (!empty($data['filter_sub_category'])) {
				$sql .= " AND cp.path_id = '" . (int)$data['filter_category_id'] . "'";
			} else {
				$sql .= " AND p2c.category_id = '" . (int)$data['filter_category_id'] . "'";
			}

			if (!empty($data['filter_filter'])) {
				$implode = array();

				$filters = explode(',', $data['filter_filter']);

				foreach ($filters as $filter_id) {
					$implode[] = (int)$filter_id;
				}

				$sql .= " AND pf.filter_id IN (" . implode(',', $implode) . ")";
			}
		}

		if (!empty($data['filter_name']) || !empty($data['filter_tag'])) {
			$sql .= " AND (";

			if (!empty($data['filter_name'])) {
				$implode = array();

				$words = explode(' ', trim(preg_replace('/\s+/', ' ', $data['filter_name'])));

				foreach ($words as $word) {
					$implode[] = "pd.name LIKE '%" . $this->db->escape($word) . "%'";
				}

				if ($implode) {
					$sql .= " " . implode(" AND ", $implode) . "";
				}

				if (!empty($data['filter_description'])) {
					$sql .= " OR pd.description LIKE '%" . $this->db->escape($data['filter_name']) . "%'";
				}
			}

			if (!empty($data['filter_name']) && !empty($data['filter_tag'])) {
				$sql .= " OR ";
			}

			if (!empty($data['filter_tag'])) {
				$implode = array();

				$words = explode(' ', trim(preg_replace('/\s+/', ' ', $data['filter_tag'])));

				foreach ($words as $word) {
					$implode[] = "pd.tag LIKE '%" . $this->db->escape($word) . "%'";
				}

				if ($implode) {
					$sql .= " " . implode(" AND ", $implode) . "";
				}
			}

			if (!empty($data['filter_name'])) {
				$sql .= " OR LCASE(p.model) = '" . $this->db->escape(utf8_strtolower($data['filter_name'])) . "'";
				$sql .= " OR LCASE(p.sku) = '" . $this->db->escape(utf8_strtolower($data['filter_name'])) . "'";
				$sql .= " OR LCASE(p.upc) = '" . $this->db->escape(utf8_strtolower($data['filter_name'])) . "'";
				$sql .= " OR LCASE(p.ean) = '" . $this->db->escape(utf8_strtolower($data['filter_name'])) . "'";
				$sql .= " OR LCASE(p.jan) = '" . $this->db->escape(utf8_strtolower($data['filter_name'])) . "'";
				$sql .= " OR LCASE(p.isbn) = '" . $this->db->escape(utf8_strtolower($data['filter_name'])) . "'";
				$sql .= " OR LCASE(p.mpn) = '" . $this->db->escape(utf8_strtolower($data['filter_name'])) . "'";
			}

			$sql .= ")";
		}

		if (!empty($data['filter_manufacturer_id'])) {
			$sql .= " AND p.manufacturer_id = '" . (int)$data['filter_manufacturer_id'] . "'";
		}

		$query = $this->db->query($sql);

		return $query->row['total'];
	}

	public function getProfile($product_id, $recurring_id) {
		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "recurring r JOIN " . DB_PREFIX . "product_recurring pr ON (pr.recurring_id = r.recurring_id AND pr.product_id = '" . (int)$product_id . "') WHERE pr.recurring_id = '" . (int)$recurring_id . "' AND status = '1' AND pr.customer_group_id = '" . (int)$this->config->get('config_customer_group_id') . "'");

		return $query->row;
	}

	public function getProfiles($product_id) {
		$query = $this->db->query("SELECT rd.* FROM " . DB_PREFIX . "product_recurring pr JOIN " . DB_PREFIX . "recurring_description rd ON (rd.language_id = " . (int)$this->config->get('config_language_id') . " AND rd.recurring_id = pr.recurring_id) JOIN " . DB_PREFIX . "recurring r ON r.recurring_id = rd.recurring_id WHERE pr.product_id = " . (int)$product_id . " AND status = '1' AND pr.customer_group_id = '" . (int)$this->config->get('config_customer_group_id') . "' ORDER BY sort_order ASC");

		return $query->rows;
	}

	public function getTotalProductSpecials() {
		$query = $this->db->query("SELECT COUNT(DISTINCT ps.product_id) AS total FROM " . DB_PREFIX . "product_special ps LEFT JOIN " . DB_PREFIX . "product p ON (ps.product_id = p.product_id) LEFT JOIN " . DB_PREFIX . "product_to_store p2s ON (p.product_id = p2s.product_id) WHERE p.status = '1' AND p.date_available <= NOW() AND p2s.store_id = '" . (int)$this->config->get('config_store_id') . "' AND ps.customer_group_id = '" . (int)$this->config->get('config_customer_group_id') . "' AND ((ps.date_start = '0000-00-00' OR ps.date_start < NOW()) AND (ps.date_end = '0000-00-00' OR ps.date_end > NOW()))");

		if (isset($query->row['total'])) {
			return $query->row['total'];
		} else {
			return 0;
		}
	}
    
    public function exclusiveProduct($product_id){
        $q_sql = "SELECT " . DB_PREFIX . "product_attribute.product_id AS product_id, ";
        $q_sql .= DB_PREFIX . "product_attribute.attribute_id AS attribute_id, ";
        $q_sql .= DB_PREFIX . "product_attribute.text AS text, ";
        $q_sql .= DB_PREFIX . "attribute.attribute_group_id AS group_id, ";
        $q_sql .= DB_PREFIX . "attribute_description.name AS name ";
        $q_sql .= "FROM " . DB_PREFIX . "product_attribute ";
        $q_sql .= "JOIN " . DB_PREFIX . "attribute ON " . DB_PREFIX . "attribute.attribute_id = ". DB_PREFIX . "product_attribute.attribute_id ";
        $q_sql .= "JOIN " . DB_PREFIX . "attribute_description ON " . DB_PREFIX . "attribute_description.attribute_id = ". DB_PREFIX . "attribute.attribute_id ";
        $q_sql .= "WHERE " . DB_PREFIX . "product_attribute.product_id = '" . (int)$product_id . "' ";
        $q_sql .= "AND " . DB_PREFIX . "attribute.attribute_group_id = 10;";
        
        $query = $this->db->query($q_sql);
        if($query->num_rows > 0){
            return $query->row;    
        }
        else{
            return 0;
        }
        
    }
    public function downloadsProduct($product_id){
        $q_sql = "SELECT ";
        $q_sql .= DB_PREFIX . "download.filename AS filename, ";
        $q_sql .= DB_PREFIX . "download_description.name AS name ";
        $q_sql .= "FROM " . DB_PREFIX . "product_to_download ";
        $q_sql .= "JOIN " . DB_PREFIX . "download ON " . DB_PREFIX . "download.download_id = ". DB_PREFIX . "product_to_download.download_id ";
        $q_sql .= "JOIN " . DB_PREFIX . "download_description ON " . DB_PREFIX . "download_description.download_id = ". DB_PREFIX . "download.download_id ";
        $q_sql .= "WHERE " . DB_PREFIX . "product_to_download.product_id = '" . (int)$product_id . "';";
        
        $query = $this->db->query($q_sql);
        if($query->num_rows > 0){
            return $query->row;    
        }
        else{
            return 0;
        }
        
    }
    public function getOldProduct($product_slug) {
		$query = $this->db->query("SELECT DISTINCT *, pd.name AS name, p.image, m.name AS manufacturer, (SELECT price FROM " . DB_PREFIX . "product_discount pd2 WHERE pd2.product_id = p.product_id AND pd2.customer_group_id = '" . (int)$this->config->get('config_customer_group_id') . "' AND pd2.quantity = '1' AND ((pd2.date_start = '0000-00-00' OR pd2.date_start < NOW()) AND (pd2.date_end = '0000-00-00' OR pd2.date_end > NOW())) ORDER BY pd2.priority ASC, pd2.price ASC LIMIT 1) AS discount, (SELECT price FROM " . DB_PREFIX . "product_special ps WHERE ps.product_id = p.product_id AND ps.customer_group_id = '" . (int)$this->config->get('config_customer_group_id') . "' AND ((ps.date_start = '0000-00-00' OR ps.date_start < NOW()) AND (ps.date_end = '0000-00-00' OR ps.date_end > NOW())) ORDER BY ps.priority ASC, ps.price ASC LIMIT 1) AS special, (SELECT points FROM " . DB_PREFIX . "product_reward pr WHERE pr.product_id = p.product_id AND pr.customer_group_id = '" . (int)$this->config->get('config_customer_group_id') . "') AS reward, (SELECT ss.name FROM " . DB_PREFIX . "stock_status ss WHERE ss.stock_status_id = p.stock_status_id AND ss.language_id = '" . (int)$this->config->get('config_language_id') . "') AS stock_status, (SELECT wcd.unit FROM " . DB_PREFIX . "weight_class_description wcd WHERE p.weight_class_id = wcd.weight_class_id AND wcd.language_id = '" . (int)$this->config->get('config_language_id') . "') AS weight_class, (SELECT lcd.unit FROM " . DB_PREFIX . "length_class_description lcd WHERE p.length_class_id = lcd.length_class_id AND lcd.language_id = '" . (int)$this->config->get('config_language_id') . "') AS length_class, (SELECT AVG(rating) AS total FROM " . DB_PREFIX . "review r1 WHERE r1.product_id = p.product_id AND r1.status = '1' GROUP BY r1.product_id) AS rating, (SELECT COUNT(*) AS total FROM " . DB_PREFIX . "review r2 WHERE r2.product_id = p.product_id AND r2.status = '1' GROUP BY r2.product_id) AS reviews, p.sort_order FROM " . DB_PREFIX . "product p LEFT JOIN " . DB_PREFIX . "product_description pd ON (p.product_id = pd.product_id) LEFT JOIN " . DB_PREFIX . "product_to_store p2s ON (p.product_id = p2s.product_id) LEFT JOIN " . DB_PREFIX . "manufacturer m ON (p.manufacturer_id = m.manufacturer_id) WHERE p.product_slug = '" . $product_slug . "' AND pd.language_id = '" . (int)$this->config->get('config_language_id') . "' AND p.status = '1' AND p.date_available <= NOW() AND p2s.store_id = '" . (int)$this->config->get('config_store_id') . "'");

		if ($query->num_rows) {
			return array(
				'product_id'       => $query->row['product_id'],
				'name'             => $query->row['name'],
				'description'      => $query->row['description'],
                'short_description'=> $query->row['shortdescription'],
				'meta_title'       => $query->row['meta_title'],
				'meta_description' => $query->row['meta_description'],
				'meta_keyword'     => $query->row['meta_keyword'],
				'tag'              => $query->row['tag'],
				'model'            => $query->row['model'],
				'sku'              => $query->row['sku'],
				'upc'              => $query->row['upc'],
				'ean'              => $query->row['ean'],
				'jan'              => $query->row['jan'],
				'isbn'             => $query->row['isbn'],
				'mpn'              => $query->row['mpn'],
				'location'         => $query->row['location'],
				'quantity'         => $query->row['quantity'],
				'stock_status'     => $query->row['stock_status'],
				'image'            => $query->row['image'],
                'video'            => $query->row['video'],
				'manufacturer_id'  => $query->row['manufacturer_id'],
				'manufacturer'     => $query->row['manufacturer'],
				'price'            => ($query->row['discount'] ? $query->row['discount'] : $query->row['price']),
				'special'          => $query->row['special'],
				'reward'           => $query->row['reward'],
				'points'           => $query->row['points'],
				'tax_class_id'     => $query->row['tax_class_id'],
				'date_available'   => $query->row['date_available'],
				'weight'           => $query->row['weight'],
				'weight_class_id'  => $query->row['weight_class_id'],
				'length'           => $query->row['length'],
				'width'            => $query->row['width'],
				'height'           => $query->row['height'],
				'length_class_id'  => $query->row['length_class_id'],
				'subtract'         => $query->row['subtract'],
				'rating'           => round($query->row['rating']),
				'reviews'          => $query->row['reviews'] ? $query->row['reviews'] : 0,
				'minimum'          => $query->row['minimum'],
				'sort_order'       => $query->row['sort_order'],
				'status'           => $query->row['status'],
				'date_added'       => $query->row['date_added'],
				'date_modified'    => $query->row['date_modified'],
				'viewed'           => $query->row['viewed']
			);
		} else {
			return false;
		}
	}
    public function getOldRecipe($recipe_old) {
		$query = $this->db->query("SELECT DISTINCT *, pd.name AS name, p.image, m.name AS manufacturer, (SELECT price FROM " . DB_PREFIX . "product_discount pd2 WHERE pd2.product_id = p.product_id AND pd2.customer_group_id = '" . (int)$this->config->get('config_customer_group_id') . "' AND pd2.quantity = '1' AND ((pd2.date_start = '0000-00-00' OR pd2.date_start < NOW()) AND (pd2.date_end = '0000-00-00' OR pd2.date_end > NOW())) ORDER BY pd2.priority ASC, pd2.price ASC LIMIT 1) AS discount, (SELECT price FROM " . DB_PREFIX . "product_special ps WHERE ps.product_id = p.product_id AND ps.customer_group_id = '" . (int)$this->config->get('config_customer_group_id') . "' AND ((ps.date_start = '0000-00-00' OR ps.date_start < NOW()) AND (ps.date_end = '0000-00-00' OR ps.date_end > NOW())) ORDER BY ps.priority ASC, ps.price ASC LIMIT 1) AS special, (SELECT points FROM " . DB_PREFIX . "product_reward pr WHERE pr.product_id = p.product_id AND pr.customer_group_id = '" . (int)$this->config->get('config_customer_group_id') . "') AS reward, (SELECT ss.name FROM " . DB_PREFIX . "stock_status ss WHERE ss.stock_status_id = p.stock_status_id AND ss.language_id = '" . (int)$this->config->get('config_language_id') . "') AS stock_status, (SELECT wcd.unit FROM " . DB_PREFIX . "weight_class_description wcd WHERE p.weight_class_id = wcd.weight_class_id AND wcd.language_id = '" . (int)$this->config->get('config_language_id') . "') AS weight_class, (SELECT lcd.unit FROM " . DB_PREFIX . "length_class_description lcd WHERE p.length_class_id = lcd.length_class_id AND lcd.language_id = '" . (int)$this->config->get('config_language_id') . "') AS length_class, (SELECT AVG(rating) AS total FROM " . DB_PREFIX . "review r1 WHERE r1.product_id = p.product_id AND r1.status = '1' GROUP BY r1.product_id) AS rating, (SELECT COUNT(*) AS total FROM " . DB_PREFIX . "review r2 WHERE r2.product_id = p.product_id AND r2.status = '1' GROUP BY r2.product_id) AS reviews, p.sort_order FROM " . DB_PREFIX . "product p LEFT JOIN " . DB_PREFIX . "product_description pd ON (p.product_id = pd.product_id) LEFT JOIN " . DB_PREFIX . "product_to_store p2s ON (p.product_id = p2s.product_id) LEFT JOIN " . DB_PREFIX . "manufacturer m ON (p.manufacturer_id = m.manufacturer_id) WHERE p.model = '" . (int)$recipe_old . "' AND pd.language_id = '" . (int)$this->config->get('config_language_id') . "' AND p.status = '1' AND p.date_available <= NOW() AND p2s.store_id = '" . (int)$this->config->get('config_store_id') . "'");

		if ($query->num_rows) {
			return array(
				'product_id'       => $query->row['product_id'],
				'name'             => $query->row['name'],
				'description'      => $query->row['description'],
                'short_description'=> $query->row['shortdescription'],
				'meta_title'       => $query->row['meta_title'],
				'meta_description' => $query->row['meta_description'],
				'meta_keyword'     => $query->row['meta_keyword'],
				'tag'              => $query->row['tag'],
				'model'            => $query->row['model'],
				'sku'              => $query->row['sku'],
				'upc'              => $query->row['upc'],
				'ean'              => $query->row['ean'],
				'jan'              => $query->row['jan'],
				'isbn'             => $query->row['isbn'],
				'mpn'              => $query->row['mpn'],
				'location'         => $query->row['location'],
				'quantity'         => $query->row['quantity'],
				'stock_status'     => $query->row['stock_status'],
				'image'            => $query->row['image'],
                'video'            => $query->row['video'],
				'manufacturer_id'  => $query->row['manufacturer_id'],
				'manufacturer'     => $query->row['manufacturer'],
				'price'            => ($query->row['discount'] ? $query->row['discount'] : $query->row['price']),
				'special'          => $query->row['special'],
				'reward'           => $query->row['reward'],
				'points'           => $query->row['points'],
				'tax_class_id'     => $query->row['tax_class_id'],
				'date_available'   => $query->row['date_available'],
				'weight'           => $query->row['weight'],
				'weight_class_id'  => $query->row['weight_class_id'],
				'length'           => $query->row['length'],
				'width'            => $query->row['width'],
				'height'           => $query->row['height'],
				'length_class_id'  => $query->row['length_class_id'],
				'subtract'         => $query->row['subtract'],
				'rating'           => round($query->row['rating']),
				'reviews'          => $query->row['reviews'] ? $query->row['reviews'] : 0,
				'minimum'          => $query->row['minimum'],
				'sort_order'       => $query->row['sort_order'],
				'status'           => $query->row['status'],
				'date_added'       => $query->row['date_added'],
				'date_modified'    => $query->row['date_modified'],
				'viewed'           => $query->row['viewed']
			);
		} else {
			return false;
		}
	}
    public function getProducts_downloads($data = array()) {
		$sql = "SELECT p.product_id";
        $sql .= " FROM " . DB_PREFIX . "product_to_download p2d";
        $sql .= " LEFT JOIN " . DB_PREFIX . "product_to_category p2c ON (p2d.product_id = p2c.product_id)";
        $sql .= " LEFT JOIN " . DB_PREFIX . "product p ON (p2d.product_id = p.product_id)";
		$sql .= " LEFT JOIN " . DB_PREFIX . "product_description pd ON (p.product_id = pd.product_id)";
        $sql .= " LEFT JOIN " . DB_PREFIX . "product_to_store p2s ON (p.product_id = p2s.product_id)";
        $sql .= " WHERE pd.language_id = '" . (int)$this->config->get('config_language_id') . "'";
        $sql .= " AND p.status = '1'";
        $sql .= " AND p.date_available <= NOW()";
        $sql .= " AND p2s.store_id = '" . (int)$this->config->get('config_store_id') . "'";

		
		$sql .= " GROUP BY p.product_id";

		$sort_data = array(
			'pd.name',
			'p.model',
			'p.quantity',
			'p.price',
			'rating',
			'p.sort_order',
			'p.date_added'
		);

		if (isset($data['sort']) && in_array($data['sort'], $sort_data)) {
			if ($data['sort'] == 'pd.name' || $data['sort'] == 'p.model') {
				$sql .= " ORDER BY LCASE(" . $data['sort'] . ")";
			} elseif ($data['sort'] == 'p.price') {
				$sql .= " ORDER BY (CASE WHEN special IS NOT NULL THEN special WHEN discount IS NOT NULL THEN discount ELSE p.price END)";
			} else {
				$sql .= " ORDER BY " . $data['sort'];
			}
		} else {
			$sql .= " ORDER BY p.sort_order";
		}

		if (isset($data['order']) && ($data['order'] == 'DESC')) {
			$sql .= " DESC, LCASE(pd.name) DESC";
		} else {
			$sql .= " ASC, LCASE(pd.name) ASC";
		}

		$product_data = array();

		$query = $this->db->query($sql);

		foreach ($query->rows as $result) {
			$product_data[$result['product_id']] = $this->getProduct($result['product_id']);
		}

		return $product_data;
	}
	
	public function getTopViewed($category_id){
		$query_sql = "SELECT product_id FROM " . DB_PREFIX . "product";
		$query_sql .= " JOIN " . DB_PREFIX . "product_to_category ON " . DB_PREFIX . "product_to_category.product_id = " . DB_PREFIX . "product.product_id";
		$query_sql .= " JOIN " . DB_PREFIX . "category_path ON " . DB_PREFIX . "product_to_category.category_id = " . DB_PREFIX . "category_path.category_id";
	}
	public function getAllDansonsProducts() {
		$product_data = array();
			$qg_sql = "SELECT " . DB_PREFIX . "product.product_id AS product_id FROM " . DB_PREFIX . "product";
			$qg_sql .= " JOIN " . DB_PREFIX . "product_to_store ON " . DB_PREFIX . "product_to_store.product_id = " . DB_PREFIX . "product.product_id";
			$qg_sql .= " WHERE " . DB_PREFIX . "product_to_store.store_id = '" . (int)$this->config->get('config_store_id') . "'";
			$qg_sql .= " AND " . DB_PREFIX . "product.status = 1 ORDER BY " . DB_PREFIX . "product.sort_order LIMIT 0, 200;";
			$query_grills = $this->db->query($qg_sql);

			foreach ($query_grills->rows as $resultg) {
				$product_data[$resultg['product_id']] = $resultg['product_id'];
			}

		return $product_data;
	}   
	
	public function getAllProductSpecials($product_id) {
		$product_data = array();
			$qg_sql = "SELECT customer_group_id, price FROM " . DB_PREFIX . "product_special ps ";
			$qg_sql .= "WHERE ps.product_id = '" . (int)$product_id ."' AND ((ps.date_start = '0000-00-00' OR ps.date_start < NOW()) ";
			$qg_sql .= "AND (ps.date_end = '0000-00-00' OR ps.date_end > NOW()));";
			$query_grills = $this->db->query($qg_sql);

			foreach ($query_grills->rows as $resultg) {
				$product_data[] = array(
					'customer_group_id'    	=> $resultg['customer_group_id'],
					'price' 				=> $resultg['price']
				);
			}

		return $product_data;
	}

	public function isPackage($product_id){
		$q_sql = "SELECT * ";
        $q_sql .= "FROM " . DB_PREFIX . "product_package ";
        $q_sql .= "WHERE " . DB_PREFIX . "product_package.product_id = '" . (int)$product_id . "';";
        
        $query = $this->db->query($q_sql);

		return $query->rows;
	}

	public function getPackage($product_id){
		$product_data = array();
			$qg_sql = "SELECT pp.add_product_id, pp.price, p.model, pd.name, pp.quantity, (pp.price*pp.quantity) AS total  FROM " . DB_PREFIX . "product_package pp ";
			$qg_sql .= "JOIN " . DB_PREFIX . "product p ON p.product_id = pp.add_product_id ";
			$qg_sql .= "JOIN " . DB_PREFIX . "product_description pd ON pd.product_id = pp.add_product_id ";
			$qg_sql .= "WHERE pp.product_id = '" . (int)$product_id ."' GROUP BY pp.add_product_id;";
			$query_grills = $this->db->query($qg_sql);

			foreach ($query_grills->rows as $resultg) {
				$product_data[] = array(
					'product_id' => $resultg['add_product_id'],
					'name'       => $resultg['name']." - " . $resultg['product_id'] . " Kit Item",
					'model'      => $resultg['model'],
					'option'     => '',
					'download'   => '',
					'quantity'   => $resultg['quantity'],
					'subtract'   => 1,
					'price'      => $resultg['price'],
					'total'      => $resultg['total'],
					'reward'     => $resultg['reward']
				);
			}

		return $product_data;
	}
	
	public function freeaddon($store_id, $subtotal){
		$product_data = array();
			$qg_sql = "SELECT pf.product_id AS product_id, pd.name AS name, pf.quantity AS quantity, p.model AS model FROM " . DB_PREFIX . "product_free pf ";
			$qg_sql .= "JOIN " . DB_PREFIX . "product p ON p.product_id = pf.product_id ";
			$qg_sql .= "JOIN " . DB_PREFIX . "product_description pd ON pd.product_id = pf.product_id ";
			$qg_sql .= "WHERE pf.store_id = '" . (int)$store_id ."' AND pf.dollarvalue >= '" . $subtotal ."' AND ((pf.date_start = '0000-00-00' OR pf.date_start < NOW()) ";
			$qg_sql .= "AND (pf.date_end = '0000-00-00' OR pf.date_end > NOW()));";
			$query_grills = $this->db->query($qg_sql);

			foreach ($query_grills->rows as $resultg) {
				$product_data[] = array(
					'product_id' => $resultg['product_id'],
					'name'       => $resultg['name']." - " . $resultg['product_id'] . " Free Item",
					'model'      => $resultg['model'],
					'option'     => '',
					'download'   => '',
					'quantity'   => $resultg['quantity'],
					'subtract'   => $resultg['quantity'],
					'price'      => 0,
					'total'      => 0,
					'reward'     => 0
				);
			}

		return $product_data;
	}
	
	public function getCookTime($product_id){
		$sql = "SELECT text FROM " . DB_PREFIX . "product_attribute WHERE product_id = '" . (int)$product_id . "' AND attribute_id = 16;";
		$query = $this->db->query($sql);
		
		if ($query->num_rows) {
			return $query->row['text'];
		}
		else{
			return "0";
		}
	}
	
	public function getPrepTime($product_id){
		$sql = "SELECT text FROM " . DB_PREFIX . "product_attribute WHERE product_id = '" . (int)$product_id . "' AND attribute_id = 15;";
		$query = $this->db->query($sql);
		
		if ($query->num_rows) {
			return $query->row['text'];
		}
		else{
			return "0";
		}
	}
	
	public function getDifficulty($product_id){
		$sql = "SELECT text FROM " . DB_PREFIX . "product_attribute WHERE product_id = '" . (int)$product_id . "' AND attribute_id = 17;";
		$query = $this->db->query($sql);
		
		if ($query->num_rows) {
			return $query->row['text'];
		}
		else{
			return "0";
		}
	}
	
	public function getServings($product_id){
		$sql = "SELECT text FROM " . DB_PREFIX . "product_attribute WHERE product_id = '" . (int)$product_id . "' AND attribute_id = 18;";
		$query = $this->db->query($sql);
		
		if ($query->num_rows) {
			return $query->row['text'];
		}
		else{
			return "0";
		}
	}
}
