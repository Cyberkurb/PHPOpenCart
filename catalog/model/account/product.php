<?php
class ModelAccountProduct extends Model {
	public function addProduct($customer_id, $data) {
        $validate1 = $this->validateSerial($data['serialnumber']);
        //$validate1 = 1;
        if($validate1 == 1){
            $this->db->query("INSERT INTO " . DB_PREFIX . "customer_product SET customer_id = '" . (int)$customer_id . "', serialnumber = '" . $this->db->escape($data['serialnumber']) . "', purchasedate = '" . $this->db->escape($data['purchasedate']) . "', purchaselocation = '" . $this->db->escape($data['purchaselocation']) . "', purchaseproduct_id = '" . $this->db->escape($data['purchaseproduct_id']) . "', dateregistered = '" . $this->db->escape(date('Y-m-d H:i:s')) . "'");
            
             //$this->db->query("INSERT INTO " . DB_PREFIX . "customer_product SET customer_id = '" . (int)$customer_id . "', serialnumber = '" . $this->db->escape($validate12) . "', purchasedate = '" . $this->db->escape($data['purchasedate']) . "', purchaselocation = '" . $this->db->escape($data['purchaselocation']) . "', purchaseproduct_id = '" . $this->db->escape($data['purchaseproduct_id']) . "', dateregistered = '" . $this->db->escape(date('Y-m-d H:i:s')) . "'");

            $product_id = $this->db->getLastId();
            $purchase_location = str_replace("'", "", $data['purchaselocation']);
            $coupon = $this->newProductCoupon($customer_id, $product_id);
            $this->addCouponForReg($product_id, $coupon);
            $product_name = $this->getProductName($data['purchaseproduct_id']);
            $coupon_code = $this->getProductCoupon($coupon);

            $ch1 = curl_init("https://crmorder.pitboss-grills.com/add-product-regv2.php?product_id=" . (int)$product_id);
            curl_setopt($ch1, CURLOPT_RETURNTRANSFER, true);
            curl_exec($ch1);
            curl_close($ch1);

            //$ch = "https://crmorder.pitboss-grills.com/registration-add.php?customer_id=".$customer_id."&serialnumber=" . str_replace(' ', '-', $data['serialnumber']) . "&purchaselocation=" . str_replace(' ', '-', $data['purchaselocation']) . "&coupon=" . str_replace(' ', '-', $coupon)."&product_name=" . str_replace(' ', '-', $product_name);
            $ch = curl_init("https://crmorder.pitboss-grills.com/activecamp_prodreg.php?customer_id=".(int)$customer_id);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_exec($ch);
            curl_close($ch);

        }
        else{
            $product_id = $validate1;
        }
		return $product_id;
	}

	public function editProduct($product_id, $data) {
        $this->db->query("UPDATE " . DB_PREFIX . "customer_product SET serialnumber = '" . $this->db->escape($data['serialnumber']) . "', purchasedate = '" . $this->db->escape($data['purchasedate']) . "', purchaselocation = '" . $this->db->escape($data['purchaselocation']) . "', purchaseproduct_id = '" . $this->db->escape($data['purchaseproduct_id']) . "', date_modified = now() WHERE product_id  = '" . (int)$product_id . "' AND customer_id = '" . (int)$this->customer->getId() . "'");
        
        $ch1 = curl_init("https://crmorder.pitboss-grills.com/add-product-regv2.php?product_id=" . (int)$product_id);
            curl_setopt($ch1, CURLOPT_RETURNTRANSFER, true);
            curl_exec($ch1);
            curl_close($ch1);
        
        return $product_id;
	}

	public function deleteProduct($product_id) {
		$this->db->query("DELETE FROM " . DB_PREFIX . "customer_product WHERE product_id = '" . (int)$product_id . "';");
    }

	public function getProduct($product_id) {
		$product_query = $this->db->query("SELECT DISTINCT * FROM " . DB_PREFIX . "customer_product WHERE product_id = '" . (int)$product_id . "' AND customer_id = '" . (int)$this->customer->getId() . "'");

		if ($product_query->num_rows) {

			$product_data = array(
				'product_id'            => $product_query->row['product_id'],
				'serialnumber'          => $product_query->row['serialnumber'],
				'purchasedate'          => $product_query->row['purchasedate'],
				'purchaselocation'      => $product_query->row['purchaselocation'],
				'purchaseproduct_id'    => $product_query->row['purchaseproduct_id']
			);

			return $product_data;
		} else {
			return false;
		}
    }
    
    public function getProductName($product_id){
        $query_sql = "SELECT name FROM " . DB_PREFIX . "product_description ";
        $query_sql .= "WHERE product_id = '" . (int)$product_id . "';";
        $query = $this->db->query($query_sql);
        return $query->row['name'];
    }
    public function getProductCoupon($coupon_id){
        $query_sql = "SELECT code FROM " . DB_PREFIX . "coupon ";
        $query_sql .= "WHERE coupon_id = '" . (int)$coupon_id . "';";
        $query = $this->db->query($query_sql);
        return $query->row['code'];
    }

	public function getProducts() {
		$product_data = array();

		$query_sql = "SELECT " . DB_PREFIX . "customer_product.*, " . DB_PREFIX . "coupon.code AS code FROM " . DB_PREFIX . "customer_product ";
        $query_sql .= "JOIN " . DB_PREFIX . "coupon ON " . DB_PREFIX . "coupon.coupon_id = " . DB_PREFIX . "customer_product.coupon_id ";
        $query_sql .= "WHERE " . DB_PREFIX . "customer_product.customer_id = '" . (int)$this->customer->getId() . "';";
        
        $query = $this->db->query($query_sql);

		foreach ($query->rows as $result) {
			$product_data[$result['product_id']] = array(
				'product_id'            => $result['product_id'],
				'serialnumber'          => $result['serialnumber'],
				'purchasedate'          => $result['purchasedate'],
				'purchaselocation'      => $result['purchaselocation'],
				'purchaseproduct_id'    => $result['purchaseproduct_id'],
                'dateregistered'        => $result['dateregistered'],
                'coupon_code'           => $result['code']
			);
		}

		return $product_data;
	}
    
    public function getLastProductReg() {

		$query_sql = "SELECT " . DB_PREFIX . "customer_product.*, " . DB_PREFIX . "coupon.code AS code FROM " . DB_PREFIX . "customer_product ";
        $query_sql .= "JOIN " . DB_PREFIX . "coupon ON " . DB_PREFIX . "coupon.coupon_id = " . DB_PREFIX . "customer_product.coupon_id ";
        $query_sql .= "WHERE " . DB_PREFIX . "customer_product.customer_id = '" . (int)$this->customer->getId() . "' ORDER BY " . DB_PREFIX . "customer_product.dateregistered DESC LIMIT 1;";
        
        $query = $this->db->query($query_sql);

        $product_data = array(
            'product_id'            => $query->row['product_id'],
            'serialnumber'          => $query->row['serialnumber'],
            'purchasedate'          => $query->row['purchasedate'],
            'purchaselocation'      => $query->row['purchaselocation'],
            'purchaseproduct_id'    => $query->row['purchaseproduct_id'],
            'dateregistered'        => $query->row['dateregistered'],
            'coupon_code'           => $query->row['code']
        );

		return $product_data;
	}

	public function getTotalProducts() {
		$query = $this->db->query("SELECT COUNT(*) AS total FROM " . DB_PREFIX . "customer_product WHERE customer_id = '" . (int)$this->customer->getId() . "'");

		return $query->row['total'];
	}
    
    public function validateSerial($serialnumber){
        if(strlen($serialnumber) >= 5){
            $valid = 1;
            $factory = strtolower(substr($serialnumber, 0, 2));
            $partnum = strtolower(substr($serialnumber, 2, 5));
            $manyear = strtolower(substr($serialnumber, 7, 2));
            $unitnum = strtolower(substr($serialnumber, -5));

            $factoryq = $this->db->query("SELECT * FROM " . DB_PREFIX . "manufacturer WHERE LEFT(name, 2) = '" . $factory ."';");
        
            if ($factoryq->num_rows) {
                $valid = 1;
                /*
                $partnumq = $this->db->query("SELECT * FROM " . DB_PREFIX . "product WHERE LOWER(model) = '" . $partnum ."';");
                if ($partnumq->num_rows) {
                    $valid = 1;
                }
                else{
                    $valid = 0;
                }
                */
            }
            else{
                $valid = 0;
            }
        }
            else{
                $valid =0;
            }
        return $valid;
    }
    
    public function availProducts(){
        $product_list = array();
        $productlist = $this->db->query("SELECT " . DB_PREFIX . "product_description.product_id AS product_id, " . DB_PREFIX . "product_description.name AS name, " . DB_PREFIX . "product_to_store.store_id AS store_id FROM " . DB_PREFIX . "product_description RIGHT JOIN " . DB_PREFIX . "product_to_store ON " . DB_PREFIX . "product_description.product_id = " . DB_PREFIX . "product_to_store.product_id JOIN " . DB_PREFIX . "product_to_category ON " . DB_PREFIX . "product_description.product_id = " . DB_PREFIX . "product_to_category.product_id WHERE " . DB_PREFIX . "product_to_store.store_id = '" . (int)$this->config->get('config_store_id') . "' AND oc_product_description.product_id > 0 AND " . DB_PREFIX . "product_to_category.category_id IN (80,81,82,83,114,123,124,168,169,157,184,187,186,188,158,160,162,161,185,182,159,171,242,239) GROUP BY " . DB_PREFIX . "product_description.product_id;");
        
        foreach ($productlist->rows as $result) {
			$product_list[] = array(
				'product_id'  => $result["product_id"],
				'name'        => $result["name"],
                'store_id'    => $result["store_id"]
			);
		}
        return $product_list;
    }
    
    public function availProductsCRM(){
        $product_list = array();
        $productlist = $this->db->query("SELECT " . DB_PREFIX . "product_description.product_id AS product_id, " . DB_PREFIX . "product_description.name AS name, " . DB_PREFIX . "product_to_store.store_id AS store_id FROM " . DB_PREFIX . "product_description RIGHT JOIN " . DB_PREFIX . "product_to_store ON " . DB_PREFIX . "product_description.product_id = " . DB_PREFIX . "product_to_store.product_id JOIN " . DB_PREFIX . "product_to_category ON " . DB_PREFIX . "product_description.product_id = " . DB_PREFIX . "product_to_category.product_id WHERE oc_product_description.product_id > 0 AND " . DB_PREFIX . "product_to_category.category_id IN (80,81,82,83,114,123,124,168,169,157,184,187,186,188,158,160,162,161,185,182,159,171) GROUP BY " . DB_PREFIX . "product_description.product_id;");
        
        foreach ($productlist->rows as $result) {
			$product_list[] = array(
				'product_id'  => $result["product_id"],
				'name'        => $result["name"],
                'store_id'    => $result["store_id"]
			);
		}
        return $product_list;
    }
    
    public function newProductCoupon($customer_id, $product_id){
        $name = $customer_id."NewProdReg".$product_id;
        $discount = 25;
        $type_discount = "P"; //P = Percent
        $total = 0;
        $logged = 0;
        $shipping = 0;
        $startdate = date("Y-m-d");
        $enddate = date("Y-m-d", strtotime('+1 year'));
        $seed = str_split('abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789'); // and any other characters
        shuffle($seed); // probably optional since array_is randomized; this may be redundant
        $code = 'NR';
        foreach (array_rand($seed, 7) as $k) $code .= $seed[$k];
        
        $this->db->query("INSERT INTO " . DB_PREFIX . "coupon SET name = '" . $name . "', code = '" . $code . "', discount = '" . (float)$discount . "', type = '" . $type_discount . "', total = '" . (float)$total . "', logged = '" . (int)$logged . "', shipping = '" . (int)$shipping . "', date_start = '" . $this->db->escape($startdate) . "', date_end = '" . $this->db->escape($enddate) . "', uses_total = '1', uses_customer = '1', status = '1', date_added = NOW()");

		$coupon_id = $this->db->getLastId();

		return $coupon_id;
    }
    
    public function addCouponForReg($product_id, $coupon_id){
        $this->db->query("UPDATE " . DB_PREFIX . "customer_product SET coupon_id = '" . (int)$coupon_id . "' WHERE product_id  = '" . (int)$product_id . "'");
    }
    
    public function getAllProductsSearch($search_term = 'pit boss'){
        $query_sql = "SELECT " . DB_PREFIX . "product_description.product_id AS id, ";
        $query_sql .= "CONCAT_WS(' ', " . DB_PREFIX . "product.model, " . DB_PREFIX . "product_description.name) AS text ";
        $query_sql .= "FROM " . DB_PREFIX . "product_description ";
        $query_sql .= "JOIN " . DB_PREFIX . "product ON " . DB_PREFIX . "product.product_id = " . DB_PREFIX . "product_description.product_id ";
        $query_sql .= "WHERE CONCAT_WS(' ', oc_product.model, oc_product_description.name) LIKE '%" . $this->db->escape($search_term) . "%';";
        
        $query = $this->db->query($query_sql);
		return $query->rows;
    }
    
   
}
