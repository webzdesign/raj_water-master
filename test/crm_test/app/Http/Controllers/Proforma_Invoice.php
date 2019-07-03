<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use App\Models\Data_model;
use App\Libraries\Utility;

use Session;
use DB;
use Redirect;
use Validator;
use PDF;

class Proforma_Invoice extends Controller
{
	public $table="inquiry";
	public $primary_id="inquiry_id";
	public $field = "Proforma_invoice";
	public $msgName = "Proforma Invoice";
	public $view = "proforma_invoice";
	public $controller = "Proforma_Invoice";
	public $module_name = "proforma_invoice";
	public $utility;
	public $role_id;
	public $user_id;
	public function __construct()
    {
		if (!Session::has('raj_user_id'))
		{
			$msg = array('error' => 'You Must First Login To Access');
			Redirect::to('/')->send()->with($msg);
		}
		$this->role_id = Session::get('raj_role_id');
		$this->user_id = Session::get('raj_user_id');

		if($this->role_id != '1')
		{
			$permission = Data_model::get_permission($this->module_name);
			if(empty($permission))
			{
				Redirect::to('/')->send();
			}
		}

		date_default_timezone_set("Asia/Kolkata");
		$this->utility = new Utility();

	}

	public function index()
	{
		if($this->role_id != '1')
		{
			$permission = Data_model::get_permission($this->module_name);
			$data['add_permission'] =  $permission[0]->add;
		}
		$data['role_id'] = $this->role_id;
		$data['utility'] = $this->utility;

		$data['result'] = Data_model::db_query("select order_book.order_book_date,order_book.order_id,order_book.generate_invoice,inquiry.product_id,inquiry.inquiry_id,quatation.quatation_no,quatation.quatation_id,revise_quatation.revise_quatation_no,revise_quatation.revise_id,customer_master.name,customer_master.mobile,customer_master.mobile_2,customer_master.mobile_3,customer_master.email,customer_master.email_2,country_master.country_name,state_master.state_name,city_master.city_name,product_master.product_name,u1.username as revise_user,u2.username as quot_user,u3.username as order_user,proforma_invoice.invoice_number,proforma_invoice.id as proforma_id from order_book
		INNER JOIN inquiry ON inquiry.inquiry_id = order_book.inquiry_id
		LEFT JOIN revise_quatation ON revise_quatation.revise_id = order_book.rquot_id
		LEFT JOIN quatation ON quatation.quatation_id = order_book.quot_id
		INNER JOIN customer_master ON customer_master.customer_id = inquiry.customer_id
		INNER JOIN country_master ON country_master.country_id = customer_master.country_id
		INNER JOIN product_master ON product_master.product_id = inquiry.product_id
		LEFT JOIN state_master ON state_master.state_id = customer_master.state_id
		LEFT JOIN city_master ON city_master.city_id = customer_master.city_id
		LEFT JOIN proforma_invoice ON proforma_invoice.order_id = order_book.order_id
		LEFT JOIN users as u1 ON u1.id = revise_quatation.added_by
		LEFT JOIN users as u2 ON u2.id = quatation.added_by
		LEFT JOIN users as u3 ON u3.id = order_book.order_by Order By order_book.order_book_date desc");

		$data['controller_name'] = $this->controller;
		$data['msgName']=$this->msgName;
		return view($this->view.'/manage',$data);
	}
	public function get_rate(Request $request)
	{
		$quatation_product_id = $request->quatation_product_id;
		$country_id = trim($request->country_id);
		$get_cur = Data_model::retrive('country_master','*',array('country_id'=>$country_id),'country_name','DESC');

		if(! empty($get_cur))
		{
			$cur_type = $get_cur[0]->cur_type;
		}
		else
		{
			$cur_type = 'inr';
		}

		$get_rate = Data_model::retrive('rate_master','*',array('cur_type'=>'Doller'),'cur_type','ASC');
		$doller_rate = $get_rate[0]->rate;

		$get_rate = Data_model::retrive('quatation_product','*',array('p_id'=>$quatation_product_id),'p_id','');
		if(count($get_rate))
		{
			if($cur_type == 'dollar')
			{
				$new_rate = number_format((float)floatval($get_rate[0]->rate)/floatval($doller_rate),2,'.','');
				$new_rate = ceil($new_rate);
				$data[0] = $new_rate;
			}
			else
			{
				$data[0] = $get_rate[0]->rate;
			}
		}
		else
		{
			$data[0]=0;
		}

		echo json_encode($data);
	}

	public function get_search_data(Request $request)
	{
		$search  = trim($request->input('search'));
		$from_date  = date('Y-m-d',strtotime($request->input('from_date')));
		$to_date  = date('y-m-d',strtotime($request->input('to_date')));
		$data['utility'] = $this->utility;
		$search_keyword = '%'.$search.'%';

		$from_d = $request->input('from_date');
		$to_d = $request->input('to_date');

		if($from_d != '' && $to_d != '')
		{
			$data['result'] = Data_model::db_query("select order_book.order_book_date,order_book.order_id,order_book.generate_invoice,inquiry.product_id,inquiry.inquiry_id,quatation.quatation_no,quatation.quatation_id,revise_quatation.revise_quatation_no,revise_quatation.revise_id,customer_master.name,customer_master.mobile,customer_master.mobile_2,customer_master.mobile_3,customer_master.email,customer_master.email_2,country_master.country_name,state_master.state_name,city_master.city_name,product_master.product_name,u1.username as revise_user,u2.username as quot_user,u3.username as order_user from order_book
			INNER JOIN inquiry ON inquiry.inquiry_id = order_book.inquiry_id
			LEFT JOIN revise_quatation ON revise_quatation.revise_id = order_book.rquot_id
			LEFT JOIN quatation ON quatation.quatation_id = order_book.quot_id
			INNER JOIN customer_master ON customer_master.customer_id = inquiry.customer_id
			INNER JOIN country_master ON country_master.country_id = customer_master.country_id
			INNER JOIN product_master ON product_master.product_id = inquiry.product_id
			LEFT JOIN state_master ON state_master.state_id = customer_master.state_id
			LEFT JOIN city_master ON city_master.city_id = customer_master.city_id
			LEFT JOIN users as u1 ON u1.id = revise_quatation.added_by
			LEFT JOIN users as u2 ON u2.id = quatation.added_by
			LEFT JOIN users as u3 ON u3.id = order_book.order_by
			where ( order_book.order_book_date >= '".$from_date."' AND order_book.order_book_date <= '".$to_date."' )  AND ( inquiry.inquiry_no LIKE '".$search_keyword."' OR quatation.quatation_no LIKE '".$search_keyword."' OR u2.username LIKE '".$search_keyword."' OR customer_master.name LIKE '".$search_keyword."' OR customer_master.mobile LIKE '".$search_keyword."' OR customer_master.mobile_2 LIKE '".$search_keyword."' OR customer_master.mobile_3 LIKE '".$search_keyword."' ) Order By order_book.order_book_date desc ");
		}
		else
		{
			$data['result'] = Data_model::db_query("select order_book.order_book_date,order_book.order_id,order_book.generate_invoice,inquiry.product_id,inquiry.inquiry_id,quatation.quatation_no,quatation.quatation_id,revise_quatation.revise_quatation_no,revise_quatation.revise_id,customer_master.name,customer_master.mobile,customer_master.mobile_2,customer_master.mobile_3,customer_master.email,customer_master.email_2,country_master.country_name,state_master.state_name,city_master.city_name,product_master.product_name,u1.username as revise_user,u2.username as quot_user,u3.username as order_user from order_book
			INNER JOIN inquiry ON inquiry.inquiry_id = order_book.inquiry_id
			LEFT JOIN revise_quatation ON revise_quatation.revise_id = order_book.rquot_id
			LEFT JOIN quatation ON quatation.quatation_id = order_book.quot_id
			INNER JOIN customer_master ON customer_master.customer_id = inquiry.customer_id
			INNER JOIN country_master ON country_master.country_id = customer_master.country_id
			INNER JOIN product_master ON product_master.product_id = inquiry.product_id
			LEFT JOIN state_master ON state_master.state_id = customer_master.state_id
			LEFT JOIN city_master ON city_master.city_id = customer_master.city_id
			LEFT JOIN users as u1 ON u1.id = revise_quatation.added_by
			LEFT JOIN users as u2 ON u2.id = quatation.added_by
			LEFT JOIN users as u3 ON u3.id = order_book.order_by
			where inquiry.inquiry_no LIKE '".$search_keyword."' OR quatation.quatation_no LIKE '".$search_keyword."' OR u2.username LIKE '".$search_keyword."' OR customer_master.name LIKE '".$search_keyword."' OR customer_master.mobile LIKE '".$search_keyword."' OR customer_master.mobile_2 LIKE '".$search_keyword."' OR customer_master.mobile_3 LIKE '".$search_keyword."' Order By order_book.order_book_date desc ");
		}

		return view($this->view.'/search_data',$data);
	}
	public function generate_invoice($id)
	{
		$order_id = $this->utility->decode($id);
		$data['controller_name'] = $this->controller;
		$data['msgName']=$this->msgName;

		$check_order = Data_model::retrive('order_book','*',array('order_id'=>$order_id),'order_id','ASC');

		if($check_order[0]->quot_id != 0)
		{
			$data['result'] = Data_model::db_query("select order_book.order_book_date,order_book.order_id,order_book.generate_invoice,inquiry.product_id,inquiry.inquiry_id,quatation.*,customer_master.name,customer_master.country_id,country_master.cur_type from order_book
			INNER JOIN inquiry ON inquiry.inquiry_id = order_book.inquiry_id
			INNER JOIN customer_master ON customer_master.customer_id = inquiry.customer_id
			INNER JOIN country_master ON country_master.country_id = customer_master.country_id
			LEFT JOIN quatation ON quatation.quatation_id = order_book.quot_id
			where order_book.order_id = ".$order_id." ");
		}
		else
		{
			$data['result'] = Data_model::db_query("select order_book.order_book_date,order_book.order_id,order_book.generate_invoice,inquiry.product_id,inquiry.inquiry_id,revise_quatation.*,customer_master.name,customer_master.country_id,country_master.cur_type from order_book
			INNER JOIN inquiry ON inquiry.inquiry_id = order_book.inquiry_id
			INNER JOIN customer_master ON customer_master.customer_id = inquiry.customer_id
			INNER JOIN country_master ON country_master.country_id = customer_master.country_id
			LEFT JOIN revise_quatation ON revise_quatation.revise_id = order_book.rquot_id
			where order_book.order_id = ".$order_id." ");
		}
		$data['order_id'] = $order_id;
		$data['quatation_product'] = Data_model::retrive('quatation_product','*',array('delete_status'=>0),'name','ASC');

		return view($this->view.'/generate_invoice',$data);
	}
	public function add_invoice(Request $request)
	{
		$user_id = $this->user_id;
		$customer_name  = $request->input('customer_name');
		$product_id  = implode(",",$request->input('quatation_product_id'));
		$rate  = implode(",",$request->input('rate'));
		$qty  = implode(",",$request->input('qty'));
		$amount  = implode(",",$request->input('amount'));

		$total = $request->input('total');
		$discount = $request->input('discount');
		$total_amount  = $request->input('total_amount');
		$gst_amount  = $request->input('gst_amount');
		$grand_total  = $request->input('grand_total');

		$order_id  = trim($request->input('order_id'));
		$date = date('Y-m-d H:i:s');

		if(date('m') >= 4)
		{
			$last_year = date('Y');
			$year = date('y') +1;
	    }
	    else
	    {
			$year = date('y');
			$last_year = date('Y') - 1;
	    }
		$new_year = $last_year."-".$year;

		$data_check = array('customer_name'=>$customer_name,
						'order_id'=>$order_id,
						'product_id'=>$product_id,
						'rate'=>$rate,
						'qty'=>$qty,
						'amount'=>$amount,
						'total'=>$total,
						'discount'=>$discount,
						'total_amount'=>$total_amount,
						'gst_amount'=>$gst_amount,
						'grand_total'=>$grand_total,
						'added_by'=>$user_id,
						'added_date'=>$date,
						'year'=>$new_year);

		$inserted_id = Data_model::store('proforma_invoice',$data_check);

		/* Year Wise get proforma Number */
		$get_num = Data_model::db_query("select * from `proforma_invoice` where `year`='".$new_year."' Order By `i_no` desc limit 1 ");

		if(empty($get_num)){
			$yearNo = '1';
		}
		else{
			$yearNo = $get_num[0]->i_no+1;
		}

		$invoice_num = 'RW/'.$new_year.'/PI-'.($yearNo);
		$update_data = array('i_no'=>$yearNo,'invoice_number'=>$invoice_num);
		Data_model::restore('proforma_invoice',$update_data,array('id'=>$inserted_id));

		/* send sms */

		Data_model::restore('order_book',array('generate_invoice'=>1),array('order_id'=>$order_id));

		$msg = array('success' => $this->msgName.' Generate Sucessfully');

		return redirect($this->controller)->with($msg);
	}
	public function print_pdf($id,$type)
	{
		$data['utility'] = $this->utility;
		$order_id = $this->utility->decode($id);
		$type = $this->utility->decode($type);

		/* get order id */
		 $order = DB::table('proforma_invoice')
		 ->where('invoice_number',"=",$order_id)
		 ->get();

		 $order_check = DB::table('order_book')
		 ->where('order_id',"=",$order[0]->order_id)
		 ->get();
		 if($order_check[0]->quot_id == 0)
		 {
			 $quot = 'no';
		 }
		 else
		 {
			 $quot = 'yes';
		 }

		if($quot == 'yes')
		{
			$data['result'] = Data_model::db_query("select order_book.*,inquiry.inquiry_id,proforma_invoice.*,quatation.quatation_no,customer_master.* from order_book
			INNER JOIN inquiry ON inquiry.inquiry_id = order_book.inquiry_id
			INNER JOIN proforma_invoice ON proforma_invoice.order_id = order_book.order_id
			LEFT JOIN quatation ON quatation.quatation_id = order_book.quot_id
			INNER JOIN customer_master ON customer_master.customer_id = inquiry.customer_id  where proforma_invoice.invoice_number = '".$order_id."' ");
		}
		else
		{
			$data['result'] = Data_model::db_query("select order_book.*,inquiry.inquiry_id,proforma_invoice.*,revise_quatation.revise_quatation_no,customer_master.* from order_book
			INNER JOIN inquiry ON inquiry.inquiry_id = order_book.inquiry_id
			INNER JOIN proforma_invoice ON proforma_invoice.order_id = order_book.order_id
			LEFT JOIN revise_quatation ON revise_quatation.revise_id = order_book.rquot_id
			INNER JOIN customer_master ON customer_master.customer_id = inquiry.customer_id  where proforma_invoice.invoice_number = '".$order_id."' ");
		}

		$get_cur = Data_model::retrive('country_master','*',array('country_id'=>$data['result'][0]->country_id),'country_name','DESC');

		if(! empty($get_cur))
		{
			$cur_type = $get_cur[0]->cur_type;
		}
		else
		{
			$cur_type = 'inr';
		}
		$data['cur_type'] = $cur_type;


		$data['specification'] = Data_model::retrive('specification_master','*',array(),'sequence','ASC');
		$data['quatation_product'] = Data_model::retrive('quatation_product','*',array(),'name','ASC');
		$data['company'] = Data_model::retrive('company','*',array(),'name','ASC');
		$data['controller_name'] = $this->controller;
		$data['quot'] = $quot;

		$data['letter_head'] = Data_model::retrive('letterhead_master','*',array(),'letterhead_name','ASC');

		$pdf = PDF::loadView($this->view.'/print',$data)->setPaper('a4','portrait');
		if($type == 'print')
		{
			return $pdf->stream('Proforma_Invoice_'.date('d-m-Y H:i:s'));
		}
		else
		{
			return $pdf->download('Proforma_Invoice_'.date('d-m-Y H:i:s'));
		}
	}

	public function view($id)
	{
		$data['utility'] = $this->utility;
		$order_id = $this->utility->decode($id);


		 $order_check = DB::table('order_book')
		 ->where('order_id',"=",$order_id)
		 ->get();
		 if($order_check[0]->quot_id == 0)
		 {
			 $quot = 'no';
		 }
		 else
		 {
			 $quot = 'yes';
		 }

		if($quot == 'yes')
		{
			$data['result'] = Data_model::db_query("select order_book.*,inquiry.*,quatation.*,customer_master.*,country_master.country_name,state_master.state_name,city_master.city_name,product_master.product_name,follow_up.added_by,u2.username as quot_user,u3.username as foll_user from order_book
			INNER JOIN inquiry ON inquiry.inquiry_id = order_book.inquiry_id
			LEFT JOIN quatation ON quatation.quatation_id = order_book.quot_id
			INNER JOIN customer_master ON customer_master.customer_id = inquiry.customer_id
			INNER JOIN country_master ON country_master.country_id = customer_master.country_id
			INNER JOIN product_master ON product_master.product_id = inquiry.product_id
			LEFT JOIN state_master ON state_master.state_id = customer_master.state_id
			LEFT JOIN city_master ON city_master.city_id = customer_master.city_id
			LEFT JOIN (select * from follow_up order by follow_up_id desc LIMIT 1) follow_up ON follow_up.inquiry_id = inquiry.inquiry_id
			LEFT JOIN users as u2 ON u2.id = quatation.added_by
			LEFT JOIN users as u3 ON u3.id = follow_up.added_by where order_book.order_id = ".$order_id." ");
		}
		else
		{
			$data['result'] = Data_model::db_query("select order_book.*,inquiry.*,revise_quatation.*,customer_master.*,country_master.country_name,state_master.state_name,city_master.city_name,product_master.product_name,follow_up.added_by,u1.username as revise_user,u3.username as foll_user from order_book
			INNER JOIN inquiry ON inquiry.inquiry_id = order_book.inquiry_id
			LEFT JOIN revise_quatation ON revise_quatation.revise_id = order_book.rquot_id
			INNER JOIN customer_master ON customer_master.customer_id = inquiry.customer_id
			INNER JOIN country_master ON country_master.country_id = customer_master.country_id
			INNER JOIN product_master ON product_master.product_id = inquiry.product_id
			LEFT JOIN state_master ON state_master.state_id = customer_master.state_id
			LEFT JOIN city_master ON city_master.city_id = customer_master.city_id
			LEFT JOIN (select * from follow_up order by follow_up_id desc LIMIT 1) follow_up ON follow_up.inquiry_id = inquiry.inquiry_id
			LEFT JOIN users as u1 ON u1.id = revise_quatation.added_by
			LEFT JOIN users as u3 ON u3.id = follow_up.added_by where order_book.order_id = ".$order_id." ");
		}

		$data['specification'] = Data_model::retrive('specification_master','*',array(),'sequence','ASC');
		$data['quatation_product'] = Data_model::retrive('quatation_product','*',array(),'name','ASC');
		$data['company'] = Data_model::retrive('company','*',array(),'name','ASC');
		$data['controller_name'] = $this->controller;
		$data['quot'] = $quot;
		$data['msgName']=$this->msgName;
		$data['letter_head'] = Data_model::retrive('letterhead_master','*',array(),'letterhead_name','ASC');
		return view($this->view.'/view',$data);
	}

	public function send_sms(Request $request)
	{
		$customer_mno  = $request->input('customer_mno');
		$proforma_id = $request->input('proforma_id');

		/* get quotation no */
		$getNo = Data_model::db_query("select * from `proforma_invoice` where id = '".$proforma_id."' ");
		$perNo = $getNo[0]->invoice_number;

		/* get address */
		$getmsg = Data_model::db_query("select * from `sms_format` where type = 'pro_forma' ");
		$massage = $perNo." ".$getmsg[0]->format;

		for($r=0; $r<count($customer_mno); $r++)
		{
			if($customer_mno[$r] != '')
			{
				$postdata = http_build_query(
						array('username' => 'rajwater',
							'password' => 'rajwater@321',
							'senderid' => 'RWTGPL',
							'route' => '1',
							'unicode' => '2',
							'number' => $customer_mno[$r],
							'message' => $massage
						)
				);

				$opts = array('http' =>
					array(
						'method'  => 'POST',
						'header'  => 'Content-type: application/x-www-form-urlencoded',
						'content' => $postdata
						)
				);

				$context  = stream_context_create($opts);
				$result = trim(file_get_contents('http://buzz.azmarq.com/http-api.php', false, $context));
			}
		}

		$response[0] = 'success';

		echo json_encode($response);
		exit;
	}
	public function get_customer_mobile(Request $request)
	{
		$inq_id = $request->input('inq_id');

		$data_customer = Data_model::db_query("select `inquiry`.inquiry_id,`customer_master`.* from `inquiry` INNER JOIN customer_master ON customer_master.customer_id = inquiry.customer_id where `inquiry`.inquiry_id = ".$inq_id."  ");

		if(! empty($data_customer))
		{
			echo '<option value="">Select</option>';
			if($data_customer[0]->mobile != '')
			{
				echo '<option value="'.$data_customer[0]->mobile.'" >'.$data_customer[0]->mobile.'</option>';
			}
			if($data_customer[0]->mobile_2 != '')
			{
				echo '<option value="'.$data_customer[0]->mobile_2.'" >'.$data_customer[0]->mobile_2.'</option>';
			}
			if($data_customer[0]->mobile_3 != '')
			{
				echo '<option value="'.$data_customer[0]->mobile_3.'" >'.$data_customer[0]->mobile_3.'</option>';
			}
		}
		else
		{
			echo '<option value="">Select</option>';
		}
	}
}
