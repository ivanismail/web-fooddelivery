<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Mapi extends CI_Model {

	public function getProduk($kategori)
     {
        if ($kategori == 'all')
        {
            $sql	= "select * from produk";
            $query	= $this->db->query($sql)->result_array();
			return $query;
        } else 
        {
            try{	
			$sql	= "select * from produk where kategori = ?";
			$query	= $this->db->query($sql,array($kategori))->result_array();
			return $query;
		    }
		catch(Exception $e){
			$this->db->trans_rollback();
			echo $this->db->_error_message();
			die();
		    }
        }
     }
     
     public function getValidateEmail($email)
     {
        try{	
		$sql	= "select * from pelanggan where email = ?";
		$query	= $this->db->query($sql,array($email))->result_array();
			if ($query) {
			    foreach($query as $data)
		        {
			    return $data['id'];
		        }
    		   
    		}
    		return FALSE;
    	    }
    	catch(Exception $e){
    	$this->db->trans_rollback();
    	echo $this->db->_error_message();
    	die();
	   }
    }
    
    public function authLogin($email,$password)
	{
	    try{
    	$sql	= "select * from pelanggan where email = ? and  pass= ?";
    	$query	= $this->db->query($sql,array($email,$password))->result_array();
	    if ($query) {
    	   foreach($query as $data)
		        {
			    return $data['id'];
		        }
    	}
    		return false;
    	}
    	catch(Exception $e){
    	$this->db->trans_rollback();
    	echo $this->db->_error_message();
    	die();
	   }
	}
	
	public function MRegister($nama,$no_telp,$email,$password,$status,$timestamp)
     {
        $sql	= "insert into pelanggan (nama,no_telp,email,pass,sta,create_at) values (?,?,?,?,?,?)";
		$query	= $this->db->query($sql,array($nama,$no_telp,$email,$password,$status,$timestamp));
		if ($query) {
    	   return $query;
    	}
    	return false;
     }
	
    public function updatePass($id,$password,$timestamp)
     {
        $sql	= "update pelanggan set pass=?, update_at=? where id=?";
		$query	= $this->db->query($sql,array($password,$timestamp,$id));
		
		if ($query) {
    	   return $query;
    	}
    	return false;
     }
    
    public function addCart($nama_produk,$harga,$qty,$gambar,$id_pelanggan,$created_at)
     {  
        try{
        $sql = "select * from keranjang where id_pelanggan=? and nama_produk=?";
		$query	= $this->db->query($sql,array($id_pelanggan,$nama_produk))->result_array();;
		
		$expired =2;
		if (!empty($query)) {
		    foreach($query as $data){
		   
		    $qty= $qty + $data['qty'];
		    }
		    $total = $harga * $qty;
		    
    	    $sql2 = "update keranjang set harga=?,qty=?,total=?,gambar=?,expired=?,update_at=? where id_pelanggan=? and nama_produk=?";
    	    
		    $query2	= $this->db->query($sql2,array($harga,$qty,$total,$gambar,$expired,$created_at,$id_pelanggan,$nama_produk));
		    
    	} else {
    	   $total = $harga * $qty;
		    
    	    $sql2 = "insert into keranjang (nama_produk,harga,qty,total,gambar,expired,id_pelanggan,create_at,update_at) values (?,?,?,?,?,?,?,?,?)";
    	    
		     $query2 = $this->db->query($sql2,array($nama_produk,$harga,$qty,$total,$gambar,$expired,$id_pelanggan,$created_at,$created_at));
    	}
    	return $query2;
        }
    	catch(Exception $e){
    	$this->db->trans_rollback();
    	echo $this->db->_error_message();
    	die();
	   }
     }
     
     public function updateQtyCart($id,$qty,$id_pelanggan,$update_at)
     {  
        try{
        $sql = "select * from keranjang where id=?";
		$query	= $this->db->query($sql,array($id))->result_array();;
		
		if (!empty($query)) {
		    foreach($query as $data){
		    $harga= $data['harga'];
		    }
		    $total = $harga * $qty;
		    $sql2 = "update keranjang set qty=?,total=?,update_at=? where id=?";
    	    $query2	= $this->db->query($sql2,array($qty,$total,$update_at,$id));
    	    
    	    return $query2;
		} 
    	return FALSE;
        }
    	catch(Exception $e){
    	$this->db->trans_rollback();
    	echo $this->db->_error_message();
    	die();
	   }
     }
     
     public function deleteItemCart($id)
     {  
        try{
        $sql = "delete from keranjang WHERE id=?";
		$query	= $this->db->query($sql,array($id));
		
		if ($query){
		    return $query;
		}
		return false;
        }
		catch(Exception $e){
    	$this->db->trans_rollback();
    	echo $this->db->_error_message();
    	die();
	   }
        
     }
     
     public function getTotalItem($id_pelanggan)
     {
        try{	
		$sql	= "select sum(qty) as total from keranjang where id_pelanggan = ?";
		$query	= $this->db->query($sql,array($id_pelanggan))->result_array();
			if ($query) {
			    foreach($query as $data)
		        {
			    return $data['total'];
		        }
		  	}
    	    	return FALSE;
    	    }
    	catch(Exception $e){
    	$this->db->trans_rollback();
    	echo $this->db->_error_message();
    	die();
	   }
    }
    
    public function getCartItem($id_pelanggan)
     {
        try{	
		$sql	= "select * from keranjang where id_pelanggan = ?";
		$query	= $this->db->query($sql,array($id_pelanggan))->result_array();
			if ($query) {
    	    return $query;
    	    }
    	    return FALSE;
            }
    	catch(Exception $e){
    	$this->db->trans_rollback();
    	echo $this->db->_error_message();
    	die();
	   }
    }
    
    public function getTotalbayar($id_pelanggan)
     {
        try{	
		$sql= "select sum(total) as totalBayar, (sum(total) * 0.05) as totalOngkir from keranjang where id_pelanggan = ?";
		$query	= $this->db->query($sql,array($id_pelanggan))->result_array();
			if ($query) {
			    return $query;
		  	}
    	    	return FALSE;
    	    }
    	catch(Exception $e){
    	$this->db->trans_rollback();
    	echo $this->db->_error_message();
    	die();
	   }
    }

    public function addTransaction($tgl_pesan,$total_bayar,$alamat_kirim,$latitude,$longitude,$id_pelanggan,$note,$payment,$ongkir,$status)
     {
        $sql    = "insert into pemesanan (tgl_pesan,total_bayar,alamat_kirim,latitude,longitude,id_pelanggan,note,payment,ongkir,status) values (?,?,?,?,?,?,?,?,?,?)";
        $query  = $this->db->query($sql,array($tgl_pesan,$total_bayar,$alamat_kirim,$latitude,$longitude,$id_pelanggan,$note,$payment,$ongkir,$status));
        
        $sql2 = "select * from keranjang where id_pelanggan=? ";
        $query2 = $this->db->query($sql2,array($id_pelanggan))->result_array();
        if ($query2) {
            foreach($query2 as $data){
            $nama_produk[]= $data['nama_produk'];
            $harga[]= $data['harga'];
            $qty[]= $data['qty'];
            $total[]= $data['total'];
            $id_pelanggan= $data['id_pelanggan'];
            $create_at= date('Y-m-d H:i:s');
            $update_at= date('Y-m-d H:i:s');;
            }
            
            $sql5 = "select kd_pemesanan from pemesanan where id_pelanggan=? order by kd_pemesanan desc limit 1";
            $query5 = $this->db->query($sql5,array($id_pelanggan))->result_array();
            foreach($query5 as $data5){
            $kd_pemesanan= $data5['kd_pemesanan'];
            }
            
            for($i = 0; $i < count($nama_produk); $i++){
            $sql3    = "insert into log_pemesanan (nama_produk,harga,qty,total,kd_pemesanan,id_pelanggan,create_at,update_at) values (?,?,?,?,?,?,?,?)";
            $query3  = $this->db->query($sql3,array($nama_produk[$i],$harga[$i],$qty[$i],$total[$i],$kd_pemesanan,$id_pelanggan,$create_at,$update_at));
            
            }
            $sql4   = "delete from keranjang WHERE id_pelanggan=?";
            $query4 = $this->db->query($sql4,array($id_pelanggan));
           if($query4){
                return $query4;
            }else{
                return FALSE;
            }
        }
        return false;
     }
     
     
	
}

/* End of file Mapi.php */
/* Location: ./application/modules/api/models/Mapi.php */