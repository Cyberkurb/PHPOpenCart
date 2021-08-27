<?php

/**
 * Better Together for Opencart by That Software Guy.
 *
 * Copyright 2014-2017 That Software Guy, Inc.. All Rights Reserved.
 *
 * See http://www.thatsoftwareguy.com/opencart_better_together.html
 *
 */

function bt_cmp($a, $b)
{
    if ($a['price'] == $b['price'])
        return 0;
    if ($a['price'] < $b['price'])
        return 1;
    return -1;
}

/**
 * Better Together linkage types
 */
define('PROD_TO_PROD', '1');
define('PROD_TO_CAT', '2');
define('CAT_TO_CAT', '3');
define('CAT_TO_PROD', '4');

define('TWOFER_PROD', '11');
define('TWOFER_CAT', '12');

/**
 * Better Together discount class.  For discounts other than twofers.
 */
class bt_discount
{
    var $ident1; // Product id or category
    var $ident2; // Product id or category
    var $type; // % or $ or X
    var $amt; // numerical amount
    var $flavor; // PROD_TO_PROD, PROD_TO_CAT, CAT_TO_CAT, CAT_TO_PROD
    var $isvalid;

    /**
     * Initialization function
     * @param $ident1 - first item in linkage
     * @param $ident2 - second  item in linkage
     * @param $type - percent or dollars off
     * @param $amt - amount - dollar or percentage amount to be deducted
     * @param $flavor - see defines above.  PROD_TO_PROD, etc.
     */
    function init($ident1, $ident2, $type, $amt, $flavor)
    {
        $this->isvalid = 0;
        if ($type != "$" && $type != "%" && $type != 'X') {
            die("Bad type " . $type);
        }
        if ($flavor != PROD_TO_PROD && $flavor != PROD_TO_CAT && $flavor != CAT_TO_PROD && $flavor != CAT_TO_CAT
        ) {
            die("Bad flavor " . $flavor);
        }
        $this->ident1 = $ident1; // Product id or category
        $this->ident2 = $ident2; // Product id or category
        $this->type = $type; // % or $ or X
        $this->amt = $amt; // numerical amount
        $this->flavor = $flavor; // PROD_TO_PROD, PROD_TO_CAT, CAT_TO_CAT, CAT_TO_PROD
        $this->isvalid = 1;
    }

    function getid()
    {
        return $this->ident1;
    }
}

/**
 * Better Together twofer discount class
 */
class bt_twofer
{
    var $ident1; // Product or category id
    var $ident2; // Product or category id
    var $flavor; // Can only be TWOFER_PROD or TWOFER_CAT
    var $isvalid;

    function init($ident1, $ident2, $flavor)
    {
        $this->isvalid = 0;
        if ($flavor != TWOFER_PROD && $flavor != TWOFER_CAT) {
            exit();
            bailout("Bad flavor " . $flavor);
        }
        $this->ident1 = $ident1; // Product id or category
        $this->ident2 = $ident2; // Product id or category
        $this->flavor = $flavor; // Is the twofer for a product or a category?
        $this->isvalid = 1;

    }

    function getid()
    {
        return $this->ident1;
    }
}

class ModelExtensionTotalBetterTogether extends Model
{

    protected $discountlist = array();
    protected $xselllist  = array();
    protected $twoferlist = array();
    protected $initialized = false; 
    public function construct()
    {
        $this->setup();
        $this->initialized = true; 
    }


    /**
     * Determines if the item is eligible for a twofer discount.
     * @param $discount_item
     * @return bool
     */
    private function is_twofer($discount_item)
    {
        for ($dis = 0, $n = count($this->twoferlist); $dis < $n; $dis++) {
            $li = $this->twoferlist[$dis];

            // Based on type, check ident1
            if (($li->flavor == TWOFER_PROD) && ($li->ident1 == $discount_item['product_id'])
            ) {
                return true;
            } elseif (($li->flavor == TWOFER_CAT) && (in_array($li->ident1, $discount_item['categories']))) {
                
                return true;
            }
            else{
                return false;
            }
        }
    }

    /**
     * Computes the discount for this item.  Modifies remaining items to disallow double dipping.
     * @param $discount_item
     * @param $all_items
     * @param array $already_discounted_items
     * @param int $bt_one_to_many
     * @return float|int - discounted amount.
     */

    private function get_discount($discount_item, &$all_items, &$already_discounted_items = array(), $bt_one_to_many = 0)
    {
        for ($dis = 0, $n = count($this->discountlist); $dis < $n; $dis++) {
            $li = $this->discountlist[$dis];

            // Based on type, check ident1
            if (($li->flavor == PROD_TO_PROD) || ($li->flavor == PROD_TO_CAT)
            ) {
                if ($li->ident1 != $discount_item['product_id']) {
                    continue;
                }
            } else { // CAT_TO_CAT, CAT_TO_PROD
                if (!in_array($li->ident1, $discount_item['categories'])) {
                    continue;
                }
            }

            for ($i = sizeof($all_items) - 1; $i >= 0; $i--) {
                if ($all_items[$i]['quantity'] == 0)
                    continue;
                $match = 0;
                if (($li->flavor == PROD_TO_PROD) || ($li->flavor == CAT_TO_PROD)
                ) {
                    if ($all_items[$i]['product_id'] == $li->ident2) {
                        $match = 1;
                    }
                } 
                elseif (($li->flavor == TWOFER_CAT)) { // CAT_TO_CAT, PROD_TO_CAT
                    if (in_array($li->ident1, $all_items[$i]['categories'])) {
                        $match = 1;
                    }
                }
                else { // CAT_TO_CAT, PROD_TO_CAT
                    if (in_array($li->ident2, $all_items[$i]['categories'])) {
                        $match = 1;
                    }
                }
                if ($match == 1 && $bt_one_to_many != 0) {
                    $id = $all_items[$i]['product_id'];
                    if ($bt_one_to_many == 1) {
                        if (in_array($id, $already_discounted_items)) {
                            continue;
                        }
                        $already_discounted_items[] = $id;
                    }
                }

                if ($match == 1) {
                    $all_items[$i]['quantity'] -= 1;
                    if ($li->type == "$") {
                        $discount = $li->amt;
                    } else { // %
                        $discount = $all_items[$i]['price'] * $li->amt / 100;
                    }
                    return $discount;
                }
            }
        }

        return 0;
    }

    /**
     * Called from setup() - creates a 2 for 1 prod discount
     * @param $ident1 - product to be discounted
     */
    private function add_twoforone_prod($ident1)
    {
        $d = new bt_twofer;
        $d->init($ident1, TWOFER_PROD);
        if ($d->isvalid == 1) {
            $this->twoferlist[] =& $d;
        }
    }

    /**
     * Called from setup() - creates a 2 for 1 cat discount
     * @param $ident1 - cat to be discounted
     */
    private function add_twoforone_cat($ident1, $ident2)
    {
        $d = new bt_twofer;
        $d->init($ident1, $ident2, TWOFER_CAT);
        if ($d->isvalid == 1) {
            $this->twoferlist[] =& $d;
            $this->discountlist[] =& $d;
        }
    }

    private function add_prod_to_prod($ident1, $ident2, $type, $amt)
    {
        $d = new bt_discount;
        $d->init($ident1, $ident2, $type, $amt, PROD_TO_PROD);
        if ($d->isvalid == 1) {
            if ($type == 'X') {
            $this->xselllist[] = &$d;
         } else {
            $this->discountlist[] = &$d;
         }
        }
    }

    /**
     * Called from setup() - creates a prod to cat discount
     * @param $ident1 - First product
     * @param $ident2 - Second category product
     * @param $type - $, % or X
     * @param $amt - amount off (if type is $ or %)
     */
    private function add_prod_to_cat($ident1, $ident2, $type, $amt)
    {
        $d = new bt_discount;
        $d->init($ident1, $ident2, $type, $amt, PROD_TO_CAT);
        if ($d->isvalid == 1) {
            if ($type == 'X') {
                $this->xselllist[] =& $d;
            } else {
                $this->discountlist[] =& $d;
            }
        }
    }

    /**
     * Called from setup() - creates a cat to cat discount
     * @param $ident1 - First category
     * @param $ident2 - Second category
     * @param $type - $, % or X
     * @param $amt - amount off (if type is $ or %)
     */
    private function add_cat_to_cat($ident1, $ident2, $type, $amt)
    {
        $d = new bt_discount;
        $d->init($ident1, $ident2, $type, $amt, CAT_TO_CAT);
        if ($d->isvalid == 1) {
            if ($type == 'X') {
                $this->xselllist[] =& $d;
            } else {
                $this->discountlist[] =& $d;
            }
        }
    }

    /**
     * Called from setup() - creates a cat to prod discount
     * @param $ident1 - First category
     * @param $ident2 - Second product
     * @param $type - $, % or X
     * @param $amt - amount off (if type is $ or %)
     */
    private function add_cat_to_prod($ident1, $ident2, $type, $amt)
    {
        $d = new bt_discount;
        $d->init($ident1, $ident2, $type, $amt, CAT_TO_PROD);
        if ($d->isvalid == 1) {
            if ($type == 'X') {
                $this->xselllist[] =& $d;
            } else {
                $this->discountlist[] =& $d;
            }
        }
    }

    private function get_category_list($prid)
    {
        $category_list = array();
        $query = $this->db->query("SELECT pc.category_id FROM " . DB_PREFIX . "product_to_category pc JOIN " . DB_PREFIX . "category_to_store cs ON cs.category_id = pc.category_id  WHERE product_id = '" . (int)$prid . "' AND store_id = ".$this->config->get('config_store_id'));

        if ($query->num_rows) {
            foreach ($query->rows as $result) {
                $category_list[] = $result['category_id'];
            }
        }
        return $category_list;

    }

    private function reduce_taxes($product, $count, $discount, $total)
    {

        if ($this->config->get('better_together_tax_recalculation') == 0) {
           return;
        }
        $per_item_discount = $discount / $count;
        if ($product['tax_class_id']) {
            $tax_rates = $this->tax->getRates($product['total'] - ($product['total'] - $per_item_discount), $product['tax_class_id']);

            foreach ($tax_rates as $tax_rate) {
                if ($tax_rate['type'] == 'P') {
                    $total['taxes'][$tax_rate['tax_rate_id']] -= $tax_rate['amount'] * $count;

                }
            }
        }
    }

    public function getTotal($total)
    {

        $this->construct(); 
        $products = $this->cart->getProducts();
        reset($products);
        usort($products, "bt_cmp");
        $discountable_products = array();
        // Build discount list
        for ($i = 0, $n = sizeof($products); $i < $n; $i++) {
            $products[$i]['categories'] = $this->get_category_list($products[$i]['product_id']);
            $discountable_products[$i] = $products[$i];
        }

        // Now compute discounts
        $discount = 0;
        $bt_one_to_many = false; // MODULE_ORDER_TOTAL_BETTER_TOGETHER_ONE_TO_MANY;
        for ($i = 0, $n = sizeof($discountable_products); $i < $n; $i++) {
            // Is it a twofer?
            
            if ($this->is_twofer($discountable_products[$i])) {
                //$npairs = in_array($discountable_products[$i]['categories'][0], $products[$i]['categories']);
                $npairs = (int)($discountable_products[$i]['quantity'] / 2);
                $discountable_products[$i]['quantity'] -= ($npairs * 2);
                $item_discountable = $npairs * $discountable_products[$i]['price'];
                $discount += $item_discountable;
                $this->reduce_taxes($discountable_products[$i], $npairs, $item_discountable, $total);
            }
            
            // Otherwise, do regular bt processing
            $already_discounted_items = array();
            while ($discountable_products[$i]['quantity'] > 0) {
                if ($bt_one_to_many == 0) {
                    $discountable_products[$i]['quantity'] -= 1;
                }
                $item_discountable = $this->get_discount($discountable_products[$i], $discountable_products, $already_discounted_items, $bt_one_to_many);
                if ($item_discountable == 0) {
                    if ($bt_one_to_many == 0) {
                        $discountable_products[$i]['quantity'] += 1;
                        break;
                    } else {
                        if (sizeof($already_discounted_items) > 0) {
                            $discountable_products[$i]['quantity'] -= 1;
                            $already_discounted_items = array();
                            continue;
                        } else {
                            break;
                        }
                    }
                } else {
                    $discount += $item_discountable;
                    $this->reduce_taxes($discountable_products[$i], 1, $item_discountable, $total);
                }
            }
        }
        if ($discount > 0) {
            $this->load->language('extension/total/better_together');
            $total['totals'][] = array(
                'code' => 'better_together',
                'title' => $this->language->get('text_better_together'),
                'value' => -$discount,
                'sort_order' => $this->config->get('better_together_sort_order')
            );

            $total['total'] -= $discount;
        }
    }

    private function getRuleList() {
        $string1 = "SELECT * FROM " . DB_PREFIX . 'better_together_admin' . " WHERE ACTIVE = 'Y' ORDER BY sort_order DESC, id DESC";
        try { 
           $dl1 = $this->db->query($string1); 
        } catch(Exception $e) { 
            return false; 
        }
        $discount_query_val = array(); 
        foreach ($dl1->rows as $row) {
            $discount_query_val[] = $row;
        }
        $rulelist = array();

        foreach($discount_query_val as $discounts) { 
            $id = $discounts['id'];
            $linkage_type = $discounts['linkage_type'];
            $discount_units = $discounts['discount_units'];
            $discount_amount = $discounts['discount_amount'];
            $field1 = $discounts['field1'];
            $field2 = $discounts['field2'];
            $active = $discounts['active'];
            $sort_order = $discounts['sort_order'];
            $start_date = $discounts['start_date'];
            $end_date = $discounts['end_date'];
            if ($active == 'Y') {
                $rulelist[] = array(
                    'id' => $id,
                    'linkage_type' => $linkage_type,
                    'discount_units' => $discount_units,
                    'discount_amount' => $discount_amount,
                    'field1' => $field1,
                    'field2' => $field2,
                    'active' => $active,
                    'sort_order' => $sort_order,
                    'start_date' => $start_date, 
                    'end_date' => $end_date
                    );
            }
        }
        foreach ($rulelist as $discount) { 
            $lt = $discount['linkage_type'];
          
            // build exp
            if ($lt == 'BT_2FOR1PROD') {
               $this->add_twoforone_prod($discount['field1']);
            } else if ($lt == 'BT_2FOR1CAT') {
               $this->add_twoforone_cat($discount['field1']);
            } else if ($lt == 'BT_PROD2PROD') {
               $this->add_prod_to_prod($discount['field1'], $discount['field2'], $discount['discount_units'], $discount['discount_amount']);
            } else if ($lt == 'BT_CAT2PROD') {
               $this->add_cat_to_prod($discount['field1'], $discount['field2'], $discount['discount_units'], $discount['discount_amount']);
            } else if ($lt == 'BT_CAT2CAT') {
               $this->add_cat_to_cat($discount['field1'], $discount['field2'], $discount['discount_units'], $discount['discount_amount']);
            } else if ($lt == 'BT_PROD2CAT') {
               $this->add_prod_to_cat($discount['field1'], $discount['field2'], $discount['discount_units'], $discount['discount_amount']);
            } 
        } 
        return true; 
    }

    function getProductCount_Cat($cat) {
        //$string1 = "SELECT * FROM " . DB_PREFIX . 'better_together_admin' . " WHERE ACTIVE = 'Y' ORDER BY sort_order DESC, id DESC";
       
        //$dl1 = $this->db->query($string1); 

        $products = $this->cart->getProducts();
        reset($products);
        usort($products, "bt_cmp");
        $discountable_products = array();
        // Build discount list
        $categories = array();
        for ($i = 0, $n = sizeof($products); $i < $n; $i++) {
            $query = $this->db->query("SELECT pc.category_id AS pid FROM " . DB_PREFIX . "product_to_category pc JOIN " . DB_PREFIX . "category_to_store cs ON cs.category_id = pc.category_id  WHERE product_id = '" . (int)$products[$i]['product_id'] . "' AND store_id = ".(int)$this->config->get('config_store_id')." LIMIT 1;");
            
            $categories[] = $query->row['pid'];
            //$discountable_products[$i] = $products[$i];
        }

        $counts = array_count_values($categories);
     
        return $counts[$cat];
    }


    function bnok($ident, $flavor, $ident_id) {
/*
       if (function_exists('bnok_pi')) {
          return bnok_pi($ident, $flavor, $ident_id);
       }
*/
       return false;
    }
    function checkbbn($ident1, $ident2, &$bbn_string) {
/*
       if (function_exists('checkbbn_pi')) {
          return checkbbn_pi($ident, $ident2, &$bbn_string);
       }
*/
       return false;
    }

    function get_discount_info($id) {
       global $currencies;
       $catlist = $this->get_category_list($id); 
       $response_arr = array();
       if (!$this->initialized) {
          $this->construct();
       } 
       for ($dis = 0, $n = count($this->twoferlist); $dis < $n; $dis++) {
          $li = $this->twoferlist[$dis];
          $match = 0;
          $bbn = false;
          $bbn_string = '';
          $disc_string = $first_image = $second_image = $first_href = $disc_href = '';
          if (($li->flavor == TWOFER_PROD) && ($li->ident1 == $id)
          ) {
             $match = 1;
             if ($this->nocontext == 0) {
                $disc_string = $this->language->get('TWOFER_PROMO_STRING');
                // Can we buy both now?
                if ($this->nocontext == 0) {
                   if ($this->checkbbn($li->ident1, $li->ident1, $bbn_string)) {
                      $bbn = true;
                   }
                }
             }
             else {
                $disc_link = '<a href="' . $this->url->link('product/product', 'product_id=' . $li->ident1) . '">' . $this->model_catalog_product->getProduct($li->ident1)['name'] . '</a>';
                $disc_string = $this->language->get('QUALIFY');
                $disc_string .= $this->language->get('GET_THIS');
                $disc_string .= $this->language->get('SECOND');
                $disc_string .= $disc_link;
                $disc_string .= $this->language->get('FREE');
             }
 
             $first_href = '<a href="' . $this->url->link('product/product', 'product_id=' . $li->ident1) . '">';
             $disc_href = '<a href="' . $this->url->link('product/product', 'product_id=' . $li->ident1) . '">';
             // $first_image = zen_get_products_image($li->ident1);
             // $second_image = zen_get_products_image($li->ident1);
          }
          else if (($li->flavor == TWOFER_CAT) && (in_array($li->ident1, $catlist))
          ) {
             $match = 1;
             if ($this->nocontext == 0) {
                $disc_string = $this->language->get('TWOFER_CAT_PROMO_STRING');
             }
             else {
                $disc_link = '<a href="' . $this->url->link('product/product', 'product_id=' . $id) . '">' . $this->model_catalog_product->getProduct($id)['name'] . '</a>';
                $disc_string = $this->language->get('QUALIFY');
                $disc_string .= $this->language->get('GET_THIS');
                $disc_string .= $this->language->get('SECOND');
                $disc_string .= $disc_link;
                $disc_string .= $this->language->get('FREE');
             }
 
              $first_href = '<a href="' . $this->url->link('product/category', 'path=' . $li->ident1) . '">';
             $disc_href = '<a href="' . $this->url->link('product/category', 'path=' . $li->ident1) . '">';
             // $first_image = zen_image(DIR_WS_IMAGES . zen_get_categories_image($li->ident1), zen_get_category_name($li->ident1, $_SESSION['languages_id']), SMALL_IMAGE_WIDTH, SMALL_IMAGE_HEIGHT);
             // $second_image = zen_image(DIR_WS_IMAGES . zen_get_categories_image($li->ident1), zen_get_category_name($li->ident1, $_SESSION['languages_id']), SMALL_IMAGE_WIDTH, SMALL_IMAGE_HEIGHT);
          }
          if ($match == 1) {
                $info['data'] = $disc_string;
 
                $info['first_image'] = // $first_image;
                $info['second_image'] = $second_image;
                $info['first_href'] = $first_href;
                $info['ident1'] = $li->ident1;
                $info['ident2'] = $li->ident2;
                $info['disc_href'] = $disc_href;
                $info['ident1_bnok'] = $this->bnok($li->ident1, $li->flavor, 1);
                $info['ident2_bnok'] = $this->bnok($li->ident1, $li->flavor, 1);
                if ($bbn) {
                   $info['bbn_string'] = $bbn_string;
                }
                $response_arr[] = $info;
          }
       }
 
       for ($dis = 0, $n = count($this->discountlist); $dis < $n; $dis++) {
          $li = $this->discountlist[$dis];
          $match = 0;
          $bbn = false;
          $disc_link = '';
          $first_href = '';
          // $first_image = '';
          $disc_href = '';
          $second_image = '';
          $bbn_string = '';
          if (($li->flavor == PROD_TO_PROD) && ($li->ident1 == $id)
          ) {
             $match = 1;
             // Can we buy both now?
             if ($this->nocontext == 0) {
                if ($this->checkbbn($li->ident1, $li->ident2, $bbn_string)) {
                   $bbn = true;
                }
             }
             $disc_link = '<a href="' . $this->url->link('product/product', 'product_id=' . $li->ident2) . '">' . $this->model_catalog_product->getProduct($li->ident2)['name'] . '</a>';
             $first_href = '<a href="' . $this->url->link('product/product', 'product_id=' . $li->ident1) . '">'; 
             $disc_href = '<a href="' . $this->url->link('product/product', 'product_id=' . $li->ident2) . '">'; 
             // $first_image = zen_get_products_image($li->ident1);
             // $second_image = zen_get_products_image($li->ident2);
 
          }
          else if (($li->flavor == PROD_TO_CAT) && ($li->ident1 == $id)
          ) {
             $match = 1;
             $disc_link = '<a href="' . $this->url->link('product/category', 'path=' . $li->ident2) . '">' . $this->model_catalog_category->getCategory($li->ident2)['name'] . '</a>';
             $first_href = '<a href="' . $this->url->link('product/product', 'product_id=' . $li->ident1) . '">';
             $disc_href = '<a href="' . $this->url->link('product/category', 'path=' . $li->ident2) . '">';
             // $first_image = zen_get_products_image($li->ident1);
             // $second_image = zen_image(DIR_WS_IMAGES . zen_get_categories_image($li->ident2), zen_get_category_name($li->ident2, $_SESSION['languages_id']), SMALL_IMAGE_WIDTH, SMALL_IMAGE_HEIGHT);
          }
          else if (($li->flavor == CAT_TO_CAT) && (in_array($li->ident1, $catlist))
          ) {
             $match = 1;
             $disc_link = '<a href="' . $this->url->link('product/category', 'path=' . $li->ident2) . '">' . $this->model_catalog_category->getCategory($li->ident2)['name'] . '</a>';
             $first_href = '<a href="' . $this->url->link('product/category', 'path=' . $li->ident1) . '">';
             $disc_href = '<a href="' . $this->url->link('product/category', 'path=' . $li->ident2) . '">';
             // $first_image = zen_image(DIR_WS_IMAGES . zen_get_categories_image($li->ident1), zen_get_category_name($li->ident1, $_SESSION['languages_id']), SMALL_IMAGE_WIDTH, SMALL_IMAGE_HEIGHT);
             // $second_image = zen_image(DIR_WS_IMAGES . zen_get_categories_image($li->ident2), zen_get_category_name($li->ident2, $_SESSION['languages_id']), SMALL_IMAGE_WIDTH, SMALL_IMAGE_HEIGHT);
          }
          else if (($li->flavor == CAT_TO_PROD) && (in_array($li->ident1, $catlist))
          ) {
             $match = 1;
             $disc_link = '<a href="' . $this->url->link('product/product', 'product_id=' . $li->ident2) . '">' . $this->model_catalog_product->getProduct($li->ident2)['name'] . '</a>';
             $first_href = '<a href="' . $this->url->link('product/category', 'path=' . $li->ident1) . '">';
             $disc_href = '<a href="' . $this->url->link('product/product', 'product_id=' . $li->ident2) . '">';
             // $first_image = zen_image(DIR_WS_IMAGES . zen_get_categories_image($li->ident1), zen_get_category_name($li->ident1, $_SESSION['languages_id']), SMALL_IMAGE_WIDTH, SMALL_IMAGE_HEIGHT);
             // $second_image = zen_get_products_image($li->ident2);
          }
 
          if ($match == 1) {
             if ($this->nocontext == 0)
                $disc_string = $this->language->get('BUY_THIS_ITEM');
             else
                $disc_string = $this->language->get('QUALIFY');
             if (($li->flavor == PROD_TO_PROD) || ($li->flavor == CAT_TO_PROD)
             ) {
                $disc_string .= $this->language->get('GET_THIS');
             }
             else {
                $disc_string .= $this->language->get('GET_ANY');
             }
             if (($li->flavor == PROD_TO_PROD) && ($li->ident1 == $li->ident2) && ($this->nocontext == 0)
             ) {
                $disc_string .= $this->language->get('SECOND_ONE');
             }
             else {
                $disc_string .= $disc_link;
             }
             $disc_string .= " ";
             if ($li->type == "%") {
                if ($li->amt != 100) {
                   $str_amt = $li->amt . "%";
                   $off_string = sprintf($this->language->get('OFF_STRING_PCT'), $str_amt);
                }
                else {
                   $off_string = $this->language->get('FREE_STRING');
                }
                $disc_string .= $off_string;
             }
             else {
                $curr_string = $this->currency->format($li->amt, $this->session->data['currency']); 
                $off_string = sprintf($this->language->get('OFF_STRING_CURR'), $curr_string);
                $disc_string .= $off_string;
             }
                $info['data'] = $disc_string;
 
                // $info['first_image'] = $first_image;
                // $info['second_image'] = $second_image;
                $info['first_href'] = $first_href;
                $info['disc_href'] = $disc_href;
                $info['ident1'] = $li->ident1;
                $info['ident2'] = $li->ident2;
                $info['flavor'] = $li->flavor;
                $info['ident1_bnok'] = $this->bnok($li->ident1, $li->flavor, 1);
                $info['ident2_bnok'] = $this->bnok($li->ident2, $li->flavor, 2);
                if ($bbn) {
                   $info['bbn_string'] = $bbn_string;
                }
                $response_arr[] = $info;
          }
       }
       return $response_arr; 
    }

   function get_discount_info_both($id) {
     $content1 = $this->get_discount_info($id); 
     $content2 = $this->get_reverse_discount_info($id);  
     return array('title' => $this->language->get('text_better_together_marketing') ,
                    'content' => array_merge($content1, $content2));
   }

   function get_reverse_discount_info($id) {
      global $currencies;
       $catlist = $this->get_category_list($id); 
       $response_arr = array();
       if (!$this->initialized) {
          $this->construct();
       } 

      for ($dis = 0, $n = count($this->discountlist); $dis < $n; $dis++) {
         $li = $this->discountlist[$dis];
         $match = 0;
         $bbn = false;
         $disc_link = '';
         $first_href = '';
         $first_image = '';
         $disc_href = '';
         $second_image = '';
         $bbn_string = '';
         if ($li->ident2 == $li->ident1) {
            continue;
         }
         $this_string = $this->language->get('REV_GET_DISC');
         if (($li->flavor == PROD_TO_PROD) && ($li->ident2 == $id)
         ) {
            $match = 1;
             $disc_link = '<a href="' . $this->url->link('product/product', 'product_id=' . $li->ident1) . '">' . $this->model_catalog_product->getProduct($li->ident1)['name'] . '</a>';
             $first_href = '<a href="' . $this->url->link('product/product', 'product_id=' . $li->ident1) . '">';
             $disc_href = '<a href="' . $this->url->link('product/product', 'product_id=' . $li->ident2) . '">';
            // $first_image = zen_get_products_image($li->ident1);
            // $second_image = zen_get_products_image($li->ident2);
            if ($this->nocontext == 1) {
               $this_string = $this->language->get('GET_YOUR_PROD') . $this->model_catalog_product->getProduct($li->ident2)['name']; 
            }

            // Can we buy both now?
            if ($this->nocontext == 0) {
               if ($this->checkbbn($li->ident1, $li->ident2, $bbn_string)) {
                  $bbn = true;
               }
            }
         }
         else if (($li->flavor == PROD_TO_CAT) && in_array($li->ident2, $catlist)
         ) {
            $match = 1;
             $disc_link = '<a href="' . $this->url->link('product/product', 'product_id=' . $li->ident1) . '">' . $this->model_catalog_product->getProduct($li->ident1)['name'] . '</a>';
             $first_href = '<a href="' . $this->url->link('product/product', 'product_id=' . $li->ident1) . '">';
             $disc_href = '<a href="' . $this->url->link('product/category', 'path=' . $li->ident2) . '">'; 
            // $first_image = zen_get_products_image($li->ident1);
            // $second_image = zen_image(DIR_WS_IMAGES . zen_get_categories_image($li->ident2), zen_get_category_name($li->ident2, $_SESSION['languages_id']), SMALL_IMAGE_WIDTH, SMALL_IMAGE_HEIGHT);
            if ($this->nocontext == 1) {
               $this_string = $this->language->get('GET_YOUR_CAT') .  $this->model_catalog_category->getCategory($li->ident2)['name'];
            }
         }
         else if (($li->flavor == CAT_TO_CAT) && in_array($li->ident2, $catlist)
         ) {
            $match = 1;
             $disc_link = '<a href="' . $this->url->link('product/category', 'path=' . $li->ident1) . '">' . $this->model_catalog_category->getCategory($li->ident1)['name'] . '</a>';
             $first_href = '<a href="' . $this->url->link('product/category', 'path=' . $li->ident1) . '">';
             $disc_href = '<a href="' . $this->url->link('product/category', 'path=' . $li->ident2) . '">';
            // $first_image = zen_image(DIR_WS_IMAGES . zen_get_categories_image($li->ident1), zen_get_category_name($li->ident1, $_SESSION['languages_id']), SMALL_IMAGE_WIDTH, SMALL_IMAGE_HEIGHT);
            // $second_image = zen_image(DIR_WS_IMAGES . zen_get_categories_image($li->ident2), zen_get_category_name($li->ident2, $_SESSION['languages_id']), SMALL_IMAGE_WIDTH, SMALL_IMAGE_HEIGHT);
            if ($this->nocontext == 1) {
               $this_string = $this->language->get('GET_YOUR_CAT') . $this->model_catalog_category->getCategory($li->ident2)['name']; 
            }
         }
         else if (($li->flavor == CAT_TO_PROD) && ($li->ident2 == $id)
         ) {
            $match = 1;
             $disc_link = '<a href="' . $this->url->link('product/category', 'path=' . $li->ident1) . '">' . $this->model_catalog_category->getCategory($li->ident1)['name'] . '</a>';
             $first_href = '<a href="' . $this->url->link('product/category', 'path=' . $li->ident1) . '">';
             $disc_href = '<a href="' . $this->url->link('product/product', 'product_id=' . $li->ident2) . '">';
            // $first_image = zen_image(DIR_WS_IMAGES . zen_get_categories_image($li->ident1), zen_get_category_name($li->ident1, $_SESSION['languages_id']), SMALL_IMAGE_WIDTH, SMALL_IMAGE_HEIGHT);
            // $second_image = zen_get_products_image($li->ident2);
            if ($this->nocontext == 1) {
               $this_string = $this->language->get('GET_YOUR_PROD') . $this->model_catalog_product->getProduct($li->ident2)['name']; 
            }
         }
         if ($match == 1) {
            if (($li->flavor == PROD_TO_PROD) || ($li->flavor == PROD_TO_CAT)
            ) {
               $disc_string = $this->language->get('REV_GET_THIS');
            }
            else { // CAT_TO_CAT, CAT_TO_PROD
               $disc_string = $this->language->get('REV_GET_ANY');
            }
            $disc_string .= $disc_link;
            $disc_string .= $this_string;
            if ($li->type == "%") {
               if ($li->amt != 100) {
                  $str_amt = $li->amt . "%";
                  $off_string = sprintf($this->language->get('OFF_STRING_PCT'), $str_amt);
               }
               else {
                  $off_string = $this->language->get('FREE_STRING');
               }
               $disc_string .= $off_string;
            }
            else {
               $curr_string = $this->currency->format($li->amt, $this->session->data['currency']); 
               $off_string = sprintf($this->language->get('OFF_STRING_CURR'), $curr_string);
               $disc_string .= $off_string;
            }
               $info['data'] = $disc_string;
               // $info['first_image'] = $first_image;
               // $info['second_image'] = $second_image;
               $info['first_href'] = $first_href;
               $info['disc_href'] = $disc_href;
               $info['ident1'] = $li->ident1;
               $info['ident2'] = $li->ident2;
               $info['flavor'] = $li->flavor;
               $info['ident1_bnok'] = $this->bnok($li->ident1, $li->flavor, 1);
               $info['ident2_bnok'] = $this->bnok($li->ident2, $li->flavor, 2);
               if ($bbn) {
                  $info['bbn_string'] = $bbn_string;
               }
               $response_arr[] = $info;
         }
      }
      return $response_arr;
   }

    // Configuration is done here, unless you have Better Together Admin!
    // http://www.thatsoftwareguy.com/opencart_better_together_admin.html
    private function setup()
    {
        //if ($this->getRuleList()) { 
          // return; 
        //}

        // Add all linkages here if not using Better Together Admin 
        // Some examples are provided:
        // $this->add_twoforone_prod(40);
        //$this->add_twoforone_cat(67);
        // $this->add_prod_to_prod(30, 30, "%", 100);
        // $this->add_prod_to_prod(29, 28, "%", 50);
         /*
         $this->add_cat_to_cat(67, 67, "%", 100);
         $this->add_cat_to_cat(67, 67, "%", 100);
         $this->add_cat_to_cat(67, 67, "%", 100);
         $this->add_cat_to_cat(67, 67, "%", 100);
         $this->add_cat_to_cat(67, 67, "%", 100);
         $this->add_cat_to_cat(67, 67, "%", 100);
         $this->add_cat_to_cat(67, 67, "%", 100);
         $this->add_cat_to_cat(67, 67, "%", 100);
         $this->add_cat_to_cat(67, 67, "%", 100);
         $this->add_cat_to_cat(67, 67, "%", 100);
         $this->add_cat_to_cat(127, 127, "%", 100);
         $this->add_cat_to_cat(127, 127, "%", 100);
         $this->add_cat_to_cat(127, 127, "%", 100);
         $this->add_cat_to_cat(127, 127, "%", 100);
         $this->add_cat_to_cat(127, 127, "%", 100);
         $this->add_cat_to_cat(127, 127, "%", 100);
         $this->add_cat_to_cat(127, 127, "%", 100);
         $this->add_cat_to_cat(127, 127, "%", 100);
         $this->add_cat_to_cat(127, 127, "%", 100);
         $this->add_cat_to_cat(127, 127, "%", 100);
         */
        //$this->add_cat_to_cat(67, 67, "%", 100);
        if(date('Y-m-d', strtotime('-7 hours')) == '2019-12-09'){
            $total_cat_count = $this->getProductCount_Cat(67);
      
            if($total_cat_count >= 3 ){
                $this->add_cat_to_cat(67, 67, "%", 100);
                //$this->add_cat_to_cat(67, 67, "%", 100);
            }
        }
        
        if(date('Y-m-d', strtotime('-7 hours')) == '2019-12-15'){
            $total_cat_count = $this->getProductCount_Cat(67);
      
            if($total_cat_count >= 3 ){
                $this->add_cat_to_cat(67, 67, "%", 100);
                //$this->add_cat_to_cat(67, 67, "%", 100);
            }
        }
        
        //$this->add_twoforone_cat(67, 67);

    }
}

?>
