<?php
defined('BASEPATH') or exit('No direct script access allowed');
date_default_timezone_set('Asia/Kolkata');
//date_default_timezone_set("Asia/Riyadh");
class OrderApiModel extends CI_Model
{
    public function __construct()
    {
        parent::__construct();
        $this->load->library('session');
        $this->session_key = $this->session->userdata('key' . SESS_KEY);
        if (isset($this->session->userdata['current_db' . $this->session_key]) && $this->session->userdata['current_db' . $this->session_key] != "") {
            $dbName = $this->session->userdata['current_db' . $this->session_key];
            $dynamicDB = array(
                'hostname' => MAIN_DB_HOST,
                'username' => MAIN_DB_UNAME,
                'password' => MAIN_DB_PASSWORD,
                'database' => $dbName,
                'dbdriver' => 'mysqli',
                'dbprefix' => '',
                'pconnect' => TRUE,
                'db_debug' => TRUE,
                'cache_on' => FALSE,
                'cachedir' => '',
                'char_set' => 'utf8',
                'dbcollat' => 'utf8_general_ci',
                'swap_pre' => '',
                'encrypt' => FALSE,
                'compress' => FALSE,
                'stricton' => FALSE,
                'failover' => array(),
                'save_queries' => TRUE
            );
            $this->db = $this->load->database($dynamicDB, TRUE);
        }
    }

    public function stringQuery(string $query, bool $return)
    {
        if ($return)
            return $this->db->query($query);
        else
            $this->db->query($query);
    }

    function get_waiting_seconds()
    {
        $result = $this->db->select("IFNULL(settingValue,120) as value")->from("settings")->get();
        if ($result->num_rows() > 0) {
            $data = $result->result()[0];
            return $data->value;
        }
        return 120;
    }

    function get_max_kot_number_for_cart($branchCode, $tableSection, $tableNumber, $custPhone)
    {
        $this->db->select("IFNULL(MAX(kotNumber),0) as kotNumber");
        $this->db->where("branchCode", $branchCode);
        $this->db->where("tableSection", $tableSection);
        $this->db->where("tableNumber", $tableNumber);
        $this->db->where("custPhone", $custPhone);
        $result = $this->db->get("cart");
        if ($result->num_rows() > 0) {
            $data = $result->result()[0];
            return $data->kotNumber;
        }
        return 0;
    }

    function store_cart($data_array = array())
    {
        $data_array['cartDate'] = date('Y-m-d H:i:s');
        $this->db->insert('cart', $data_array);
        $currentId = $this->db->insert_id();
        if ($currentId > 0) {
            return $currentId;
        }
        return false;
    }

    function store_cart_products($data_batch_array = array())
    {
        $result = $this->db->insert_batch('cartproducts', $data_batch_array);
        return $result;
    }

    function trash_cart_master_when_empty_products($cartId)
    {
        $this->db->where("id", $cartId)->delete('cart');
        return true;
    }

    function get_product_detail($productCode)
    {
        $result = $this->db->select("*")->where('code', $productCode)->get('productmaster')->row_array();
        if (!empty($result)) return $result;
        return [];
    }

    function get_product_preparation_time($productCode)
    {
        $result = $this->db->select("preparationTime")->where('code', $productCode)->get('productmaster')->row_array();
        if (!empty($result)) return $result['preparationTime'] ?? 5;
        return 5;
    }

    function update_max_preparation_time($cartId, $max_preparation_time)
    {
        $this->db->where('id', $cartId)->update("cart", ["preparingMinutes" => $max_preparation_time]);
    }

    function get_distinct_table_orders()
    {
        $this->db->select("cart.branchCode,cart.tableSection,cart.tableNumber,cart.custPhone,cart.custName,sectorzonemaster.zoneName,tablemaster.tableNumber as tblNo");
        $this->db->join("tablemaster", "cart.tableNumber=tablemaster.code");
        $this->db->join("sectorzonemaster", "tablemaster.zoneCode=sectorzonemaster.id");
        $this->db->group_by(["cart.branchCode", "cart.tableSection", "cart.tableNumber", "cart.custPhone"]);
        $result = $this->db->get('cart');
        if ($result->num_rows() > 0) {
            return $result->result_array();
        }
        return [];
    }

    function get_kots_for_order($branch, $section, $table, $custPhone)
    {
        $this->db->select("id,kotNumber, kotStatus as status");
        $this->db->where("branchCode", $branch);
        $this->db->where("tableSection", $section);
        $this->db->where("tableNumber", $table);
        $this->db->where("custPhone", $custPhone);
        $result = $this->db->get('cart');
        if ($result->num_rows() > 0) {
            return $result->result_array();
        }
        return [];
    }

    function get_kitchen_orders($branchid)
    {
        $this->db->select("cart.*,sectorzonemaster.zoneName,tablemaster.tableNumber as tblNo");
        $this->db->join("tablemaster", "cart.tableNumber=tablemaster.code");
        $this->db->join("sectorzonemaster", "tablemaster.zoneCode=sectorzonemaster.id");
        $this->db->where("cart.branchCode", $branchid);
        $this->db->where_in("kotStatus", ["PND", "PRE"]);
        $result = $this->db->get('cart');
        if ($result->num_rows() > 0) {
            return $result->result_array();
        }
        return [];
    }

    function change_kot_order_status($cartId, $updateData)
    {
        $this->db->where("id", $cartId)->update("cart", $updateData);
        if ($this->db->affected_rows() > 0) return true;
        return false;
    }

    function get_table_by_unique_id($tokenNumber)
    {
        $this->db->select("tablemaster.*,sectorzonemaster.branchCode,sectorzonemaster.zoneName");
        $this->db->join("sectorzonemaster", "tablemaster.zoneCode=sectorzonemaster.id");
        $this->db->where("tablemaster.urlToken", $tokenNumber);
        $result = $this->db->get("tablemaster")->row_array();
        if (!empty($result)) {
            return $result;
        }
        return [];
    }

    function get_table_main_order_cart($table_array)
    {
        $where = [
            "branchCode" => $table_array['branchCode'],
            "tableSection" => $table_array['zoneCode'],
            "tableNumber" => $table_array['code'],
        ];
        $this->db->select("cart.custPhone,cart.custName");
        $this->db->where($where);
        $this->db->group_by(["cart.branchCode", "cart.tableSection", "cart.tableNumber", "cart.custPhone"]);
        $result = $this->db->get('cart')->row_array();
        if (!empty($result)) {
            return $result;
        }
        return [];
    }

    function get_customer_kot_orders($branchCode, $tableSection, $tableNumber, $custPhone)
    {
        $where = [
            "branchCode" => $branchCode,
            "tableSection" => $tableSection,
            "tableNumber" => $tableNumber,
            "custPhone" => $custPhone,
        ];
        $this->db->select("cart.*");
        $this->db->where($where);
        $this->db->order_by('id', "DESC");
        $result = $this->db->get("cart");
        if ($result->num_rows() > 0) {
            return $result->result_array();
        }
        return [];
    }

    function get_product_info_to_cart($productCode)
    {
        $this->db->select("productmaster.productEngName,productmaster.productImage");
        $this->db->where("code", $productCode);
        $result = $this->db->get("productmaster")->row_array();
        return $result;
    }

    function get_product_combo_info_to_cart($productCode)
    {
        $this->db->select("productcombo.productComboName,productcombo.productComboImage");
        $this->db->where("code", $productCode);
        $result = $this->db->get("productcombo")->row_array();
        return $result;
    }

    function get_customer_cart_products($cartId)
    {
        $this->db->select("cartproducts.*,");
        $this->db->where('cartId', $cartId);
        $this->db->order_by('id', "DESC");
        $result = $this->db->get("cartproducts");
        if ($result->num_rows() > 0) {
            $products = [];
            $res_array = $result->result_array();
            foreach ($res_array as $product) {
                if ($product['isCombo'] == "1") {
                    $res = $this->get_product_combo_info_to_cart($product['productCode']);
                    $ary = $product;
                    $ary['productName'] = $res['productComboName'];
                    $ary['productImage'] = $res['productComboImage'];
                    array_push($products, $ary);
                } else {
                    $res = $this->get_product_info_to_cart($product['productCode']);
                    $ary = $product;
                    $ary['productName'] = $res['productEngName'];
                    $ary['productImage'] = $res['productImage'];
                    array_push($products, $ary);
                }
            }
            return $products;
        }
        return [];
    }

    function get_cart_branch_code($cartId)
    {
        $this->db->select("branchCode");
        $this->db->where('id', $cartId);
        $result = $this->db->get("cart")->row_array();
        if (!empty($result)) {
            return $result['branchCode'];
        }
        return "";
    }

    function get_kot_order_cart_products($cartId)
    {
        $this->db->select("cartproducts.*");
        $this->db->where('cartId', $cartId);
        $this->db->order_by('id', "DESC");
        $result = $this->db->get("cartproducts");
        if ($result->num_rows() > 0) {
            return $result->result_array();
        }
        return [];
    }

    function store_draft_cart($data_array = array())
    {
        $this->db->insert('draftcart', $data_array);
        $currentId = $this->db->insert_id();
        if ($currentId > 0) {
            return $currentId;
        }
        return false;
    }

    function store_draft_cart_products($data_batch_array = array())
    {
        $result = $this->db->insert_batch('draftcartproducts', $data_batch_array);
        return $result;
    }

    function get_distinct_draft_orders()
    {
        $this->db->select("draftcart.*,sectorzonemaster.zoneName,tablemaster.tableNumber as tblNo");
        $this->db->join("tablemaster", "draftcart.tableNumber=tablemaster.code");
        $this->db->join("sectorzonemaster", "tablemaster.zoneCode=sectorzonemaster.id");
        $result = $this->db->get('draftcart');
        if ($result->num_rows() > 0) {
            return $result->result_array();
        }
        return [];
    }

    function get_draft_cart_by_id($draftId)
    {
        $this->db->select("draftcart.*,sectorzonemaster.zoneName,tablemaster.tableNumber as tblNo");
        $this->db->join("tablemaster", "draftcart.tableNumber=tablemaster.code");
        $this->db->join("sectorzonemaster", "tablemaster.zoneCode=sectorzonemaster.id");
        $this->db->where('draftcart.id', $draftId);
        $result = $this->db->get('draftcart');
        if (!empty($result)) {
            return $result->result_array();
        }
        return [];
    }

    function get_draft_products_by_draft_id($draftId)
    {
        $this->db->select("draftcartproducts.*");
        $this->db->select("draftcartproducts.*,productmaster.productEngName");
        $this->db->join('productmaster', 'productmaster.code=draftcartproducts.productCode');
        $this->db->where('draftcartproducts.draftId', $draftId);
        $result = $this->db->get('draftcartproducts');
        if (!empty($result)) {
            return $result->result_array();
        }
        return [];
    }

    function get_combo_products($productCode)
    {
        $result = $this->db->select('productCode')->from("productcombolineentries")->where('productComboCode', $productCode)->get();
        if ($result->num_rows() > 0) {
            $comboResult = $result->result_array();
            $comboProducts = [];
            foreach ($comboResult as $c) {
                array_push($comboProducts, $c['productCode']);
            }
            return $comboProducts;
        }
        return [];
    }

    function get_item_stock_by_itemcode_branchcode(string $itemCode, string $branchCode)
    {
        $result = $this->db->select("IFNULL(SUM(stock),0) as stock")->from("stockinfo")->where('itemCode', $itemCode)->where("branchCode", $branchCode)->get()->row_array();
        if (!empty($result)) {
            $stock = $result['stock'] > 0 ? $result['stock'] : 0;
            return $stock;
        }
        return 0;
    }

    /**
     * @params branchCode string
     * @params productCode string or array
     * @params qty current product order qty
     */
    function check_stock_exists_recipewise(string $branchCode, array|string $productCode, int $qty)
    {
        $instock = "OUTOFSTOCK";
        if (is_array($productCode)) {
            foreach ($productCode as $product) {
                $this->db->select("recipelineentries.itemQty,itemmaster.ingredientFactor,recipelineentries.itemCode");
                $this->db->from("recipelineentries");
                $this->db->join("recipecard", "recipecard.code=recipelineentries.recipeCode");
                $this->db->join("itemmaster", "itemmaster.code=recipelineentries.itemCode");
                $this->db->where("recipecard.productCode", $product);
                $this->db->where("recipecard.isActive", 1);
                $this->db->where("itemmaster.isActive", 1);
                $result = $this->db->get();
                if ($result->num_rows() > 0) {
                    $recipeItems = $result->result();
                    for ($i = 0; $i < count($recipeItems); $i++) {
                        $item = $recipeItems[$i];
                        $requiredQty = $qty * $item->itemQty;
                        $stock = $this->get_item_stock_by_itemcode_branchcode($item->itemCode, $branchCode);
                        if ($stock > 0) {
                            $stockQty = $stock * $item->ingredientFactor;
                            if ($requiredQty > $stockQty) {
                                return  "OUTOFSTOCK";
                            } else {
                                $instock = "INSTOCK";
                            }
                        } else {
                            return  "OUTOFSTOCK";
                        }
                    }
                } else {
                    return  "OUTOFSTOCK";
                }
            }
        } else {
            $this->db->select("recipelineentries.itemQty,itemmaster.ingredientFactor,recipelineentries.itemCode");
            $this->db->from("recipelineentries");
            $this->db->join("recipecard", "recipecard.code=recipelineentries.recipeCode");
            $this->db->join("itemmaster", "itemmaster.code=recipelineentries.itemCode");
            $this->db->where("recipecard.productCode", $productCode);
            $this->db->where("recipecard.isActive", 1);
            $this->db->where("itemmaster.isActive", 1);
            $result = $this->db->get();
            if ($result->num_rows() > 0) {
                $recipeItems = $result->result();
                for ($i = 0; $i < count($recipeItems); $i++) {
                    $item = $recipeItems[$i];
                    $requiredQty = $qty * $item->itemQty;
                    $stock = $this->get_item_stock_by_itemcode_branchcode($item->itemCode, $branchCode);
                    if ($stock > 0) {
                        $stockQty = $stock * $item->ingredientFactor;
                        if ($requiredQty > $stockQty) {
                            return "OUTOFSTOCK";
                        } else {
                            $instock = "INSTOCK";
                        }
                    } else {
                        return "OUTOFSTOCK";
                    }
                }
            } else {
                return "OUTOFSTOCK";
            }
        }
        return $instock;
    }
}
