<?php
defined('BASEPATH') OR exit('No direct script access allowed');

require(APPPATH.'/libraries/REST_Controller.php');

class Capi extends REST_Controller {

	function __construct()
	{
		parent:: __construct();
		$this->load->model('Mapi');
	}
    
	public function getProduk_get()
	{   
	    $kategori  = $this->input->get('kategori');
		$json= $this->Mapi->getProduk($kategori);
		if ($json) {
		    $this->response($json, 200);
		}else{
		    $this->response([
		        'status' => false,
		        'message' => 'Data tidak ditemukan'
		        ],400);
		}
	}
	
	public function getValidateEmail_get()
	{   
	    $email  = $this->input->get('email');
		$json= $this->Mapi->getValidateEmail($email);
		
		if (!empty($json)) {
		    
		    $this->response([
		        'status' => true,
		        'message' => 'Email terdaftar.',
		        'data' => ['id' => $json]
		        ],200);
		}else{
		    $this->response([
		        'status' => false,
		        'message' => 'Email belum terdaftar.'
		        ],400);
		
		}
	}
	
	public function getLogin_post()
	{  
	
	$email  = $this->input->post('email');
	$password  = md5($this->input->post('password'));
	       
	$json= $this->Mapi->authLogin($email,$password);
		
		if (!empty($json)) {
		    $this->response([
		        'status' => true,
		        'message' => 'Login berhasil.',
		        'data' => ['id' => $json]
		        ],200);
		}else{
		    $this->response([
		        'status' => false,
		        'message' => 'Data tidak ditemukan'
		        ],400);
		}
	}
	
	public function Register_post()
	{
	    $nama 		= $this->input->post('nama');
		$no_telp	= $this->input->post('no_telp');
		$email   	= $this->input->post('email');
		$password   = MD5($this->input->post('password'));
		$status	    = '1';
		$timestamp  = date('Y-m-d H:i:s');
		
		$json    	= $this->Mapi->MRegister($nama,$no_telp,$email,$password,$status,$timestamp);
		
		$json2= $this->Mapi->getValidateEmail($email);
		if ($json2) {
		   	    $this->response([
		        'status' => true,
		        'message' => 'Registrasi berhasil.',
		        'data' => ['id' => $json2]
		        ],200);
		}else{
		    $this->response([
		        'status' => false,
		        'message' => 'Data tidak ditemukan'
		        ],400);
		}
    }
    
    public function updatePass_post()
	{
	   	$id        	= $this->input->post('id');
		$password   = MD5($this->input->post('password'));
		$timestamp  = date('Y-m-d H:i:s');
		
		$json    	= $this->Mapi->updatePass($id,$password,$timestamp);
		
	   	if ($json) {
		   	    $this->response([
		        'status' => true,
		        'message' => 'Update password successfully.'
		        ],200);
		}else{
		    $this->response([
		        'status' => false,
		        'message' => 'Cannot update password'
		        ],400);
		}
    }
	
	public function addCart_post()
	{   
	$nama_produk 		= $this->input->post('nama_produk');
    $harga	            = $this->input->post('harga');
    $qty   	            = $this->input->post('qty');
    $gambar             = $this->input->post('gambar');
    $id_pelanggan       = $this->input->post('id_pelanggan');
    $created_at		    = date('Y-m-d H:i:s');
	     
		$json    	= $this->Mapi->addCart($nama_produk,$harga,$qty,$gambar,$id_pelanggan,$created_at);
	
		if ($json) {
		   	    $this->response([
		        'status' => true,
		        'message' => 'Berhasil ditambahkan ke keranjang.'
		        ],200);
		}else{
		    $this->response([
		        'status' => false,
		        'message' => 'Gagal menambahkan ke keranjang'
		        ],400);
		}
    }
    
    public function updateQtyCart_post()
	{   
	$id         		= $this->input->post('id');
    $qty   	            = $this->input->post('qty');
    $id_pelanggan       = $this->input->post('id_pelanggan');
    $update_at		    = date('Y-m-d H:i:s');
	     
		$json    	= $this->Mapi->updateQtyCart($id,$qty,$id_pelanggan,$update_at);
	
		if ($json) {
		   	    $this->response([
		        'status' => true,
		        'message' => 'Qty berhasil diubah.'
		        ],200);
		}else{
		    $this->response([
		        'status' => false,
		        'message' => 'Gagal meubah qty'
		        ],400);
		}
    }
    
    public function deleteItemCart_post()
	{   
	    $id = $this->input->post('id');
    
    	$json = $this->Mapi->deleteItemCart($id);
	
		if ($json) {
		   	    $this->response([
		        'status' => true,
		        'message' => 'Item berhasil dihapus.'
		        ],200);
		}else{
		    $this->response([
		        'status' => false,
		        'message' => 'Gagal menghapus item'
		        ],400);
		}
    }
    
    public function getTotalItem_get()
	{   
	    $id_pelanggan  = $this->input->get('id_pelanggan');
		$json= $this->Mapi->getTotalItem($id_pelanggan);
		
		if (!empty($json)) {
		    
		    $this->response([
		        'status' => true,
		        'message' => 'Item ditemukan.',
		        'data' => ['totalItem' => $json]
		        ],200);
		}else{
		    $this->response([
		        'status' => false,
		        'message' => 'Keranjang kosong.'
		        ],400);
		
		}
	}
	
	public function getCartItem_get()
	{   
	    $id_pelanggan  = $this->input->get('id_pelanggan');
		$json= $this->Mapi->getCartItem($id_pelanggan);
		
		if (!empty($json)) {
		    $this->response($json, 200);
		}else{
		    $this->response([
		        'status' => false,
		        'message' => 'Keranjang kosong.'
		        ],400);
		
		}
	}
	
	public function getTotalBayar_get()
	{   
	    $id_pelanggan  = $this->input->get('id_pelanggan');
		$json= $this->Mapi->getTotalBayar($id_pelanggan);
		
		if (!empty($json)) {
		    foreach($json as $data)
		    {
			$json2['bayar']		= $data['totalBayar'];
			$ongkir             = 0.05 * $data['totalBayar'];
			$json2['ongkir']	= '5000';
		    }
		    
		    $this->response([
		        'status' => true,
		        'message' => 'Data ditemukan.',
		        'data' => ['totalBayar' => $json2['bayar'],
		                   'totalOngkir' => $json2['ongkir']]
		        ],200);
		}else{
		    $this->response([
		        'status' => false,
		        'message' => 'Data tidak ditemukan.'
		        ],400);
		
		}
	}
	
    
    
}

/* End of file Capi.php */
/* Location: ./application/modules/api/controllers/Capi.php */