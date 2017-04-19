<?php
if (! defined('BASEPATH'))
    exit('No direct script access allowed');

class Order_model extends CI_Model
{

    public function __construct ()
    {
        parent::__construct();
        $this->r_db = $this->load->database('vidol_old_read', TRUE);
        $this->w_db = $this->load->database('vidol_old_write', TRUE);
    }
	
    public function __destruct() {
    	$this->r_db->close();
    	unset($this->r_db);
    	$this->w_db->close();
    	unset($this->w_db);
    	//parent::__destruct();
    }
    
    public function insert_paykit_data ($user_id, $data_api)
    {
        if (!empty($user_id) && count($data_api) > 0) {
            $this->r_db->select('u_id, u_mongo_id, u_member_id, u_nick_name');
            $this->r_db->where('u_mongo_id', $user_id);
            $query = $this->r_db->get('User_tbl');
            $user = $query->row();
            //print_r($user);
            foreach ($data_api as $order_key => $order_val) {
            	//print_r($order_val);
//             	stdClass Object (
//             			[packages] => Array (
//             					[0] => stdClass Object (
//             							[name] => 新會員免費體驗
//             							[paymentCycle] => non_recurring_subscription
//             							[validityType] => days
//             							[validAmount] => 31
//             							[order] => 00014650081161020933
//             							[package] => 13
//             							[coupon] =>
//             							[cost] => 0
//             							[finalCost] => 0
//             							[owner] => 44380
//             							[id] => 43095
//             							[createdAt] => 2016-06-04T02:41:56.000Z
//             							[updatedAt] => 2016-06-04T02:41:56.000Z
//             					)
//             			)
//             			[coupon] => 
//             			[id] => 00014650081161020933 
//             			[cost] => 0 
//             			[status] => success 
//             			[type] => reward 
//             			[action] => credit 
//             			[comment] => 
//             			[packageNames] => 新會員免費體驗 
//             			[createdAt] => 2016-06-04T02:41:56.000Z 
//             			[updatedAt] => 2016-06-04T02:41:56.000Z
//             	)
				$package_pk = (count($order_val->packages) >= 1)? $order_val->packages[0]->package : 0;
                $data_order = array(
                        'id' => $order_val->id,
                        'user_id' => $user->u_id,
                        'mongo_id' => $user->u_mongo_id,
                        'member_id' => $user->u_member_id,
                        'nick_name' => $user->u_nick_name,
                        'action' => $order_val->action,
                        'type' => $order_val->type,
                		//'package' => $order_val->packages[0]->package,
                		'package' => $package_pk,
                        'packageNames' => $order_val->packageNames,
                        'comment' => $order_val->comment,
                        'cost' => $order_val->cost,
                        'coupon' => $order_val->coupon,
                        'status' => $order_val->status,
                        'createdAt' => $order_val->createdAt,
                        'updatedAt' => $order_val->updatedAt
                );
                $this->w_db->replace('vidol_old.paykit_order_tbl', $data_order);
                // echo $this->w_db->last_query();
                if (count($order_val->packages) > 0) {
                    foreach ($order_val->packages as $package_key => $package_val) {
                        $data_package = array(
                                'package' => $package_val->package,
                                'id' => $package_val->id,
                                'name' => $package_val->name,
                                'cost' => $package_val->cost,
                                'coupon' => $package_val->coupon,
                                'finalCost' => $package_val->finalCost,
                                'order' => $package_val->order,
                                'owner' => $package_val->owner,
                                'paymentCycle' => $package_val->paymentCycle,
                                'validAmount' => $package_val->validAmount,
                                'validityType' => $package_val->validityType,
                                'createdAt' => $package_val->createdAt,
                                'updatedAt' => $package_val->updatedAt
                        );
                        $this->w_db->replace('paykit_package_tbl', $data_package);
                        // echo $this->w_db->last_query();
                    }
                }
            }
        }
    }
}
