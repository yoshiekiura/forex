<?php
defined('BASEPATH') OR exit('No direct script access allowed');
include_once('secure_area.php');

class Member extends Secure_area {

	function __construct() 
    {
        parent::__construct();
		$this->load->model(array('m_home','m_member'));
    }
	
	public function index(){
		$member = $this->m_member->get_ib($this->session->userdata('id_member'))->row();
		if($member->valid==0){
			redirect('member/account_verification');
		}
		else if($member->valid==1){
			redirect('member/broker_verification');
		}
		redirect('member/my_profile');
	}
	
	public function my_profile(){
		$data['member'] = $this->m_member->get_ib($this->session->userdata('id_member'))->row();
		$data['active']='my_profile';
		$data['header']='comp/header';
		$data['footer']='comp/footer';
		$data['side_menu']='comp/side_menu';
		$data['content']='main/my_profile';
		$this->load->view('main/template',$data);	
	}
	
	public function my_client(){
		$data['active']='my_client';
		$data['header']='comp/header';
		$data['footer']='comp/footer';
		$data['side_menu']='comp/side_menu';
		$data['content']='main/my_client';
		$data['list_client'] = $this->m_member->list_client($this->session->userdata('id_member'));
		$this->load->view('main/template',$data);	
	}
	public function ajax_grandchild($id_member){
		$list_client= $this->m_member->list_client($id_member);
		foreach($list_client->result() as $clt)
		echo "
		<tr>
			<td>$clt->username</td>
			<td>$clt->first_name $clt->last_name</td>
			<td>$clt->id_member</td>
			<td><a href='".site_url('/member/detail_client/'.$clt->id_member)."' data-toggle='modal' data-target='#detail_client' title='detail client'>View Detail</a></td>
		</tr>";	
	}
	
	public function detail_client($id_member){
		$data['member'] = $this->m_member->get_ib($id_member)->row();
		$this->load->view('main/detail_client',$data);	
	}
	
	public function update_profile(){
		$data=array(
			"pin" => md5($this->input->post('pin')),
			"country" => $this->input->post('country'),
			"province" => $this->input->post('province'),
			"city" => $this->input->post('city'),
			"address" => $this->input->post('address'),
			"postal" => $this->input->post('postal'),
			"id_card_number" => $this->input->post('id_card_number'),
			"phone" => $this->input->post('phone'),
			"bank_name" => $this->input->post('bank_name'),
			"bank_branch" => $this->input->post('bank_branch'),
			"bank_acc_num" => $this->input->post('bank_acc_num'),
			"bank_acc_name" => $this->input->post('bank_acc_name'),
			"fb_username" => $this->input->post('fb_username'),
			"fb_link" => $this->input->post('fb_link'),
			"last_update" => date('Y-m-d H:i:s')
			);
		if(isset($_POST['verify']) and $_FILES['ktp_file']['tmp_name']==''){
			$this->session->set_flashdata('error',"KTP harus di upload untuk proses verifikasi");
			redirect("member/account_verification");
		}
		if(isset($_POST['verify'])){
			$data["valid"]=1;
		}
		if($_FILES['ktp_file']['tmp_name']!=''){
			$path="media/img/member_id/";
			$tmp = $_FILES['ktp_file']['tmp_name'];
			$size = getimagesize($tmp);
			$img_name ="id_card_".$this->session->userdata('id_member').".jpg";
			$image = new SimpleImage();
			$image->load($tmp);
			if($size[0] > 535)
			{$image->resizeToWidth(535);}
			$image->save($path.$img_name);
		}
		
		$this->m_member->update_member($this->session->userdata('id_member'),$data);
		if(!isset($_POST['verify']))
			redirect("member/my_profile");
		else
			redirect("member/broker_verification");
	}
	
	public function account_verification(){
		$data['member'] = $this->m_member->get_ib($this->session->userdata('id_member'))->row();
		if($data['member']->valid!=0){
			redirect('member/broker_verification');
		}
		$data['active']='account_verification';
		$data['header']='comp/header';
		$data['footer']='comp/footer';
		$data['side_menu']='comp/side_menu';
		$data['content']='main/account_verification';
		$this->load->view('main/template',$data);
	}
	
	public function broker_verification(){
		$data['member'] = $this->m_member->get_ib($this->session->userdata('id_member'))->row();
		if($data['member']->valid!=1){
			redirect('member/my_profile');
		}
		if($data['member']->id_refferer!='')
			$broker=$this->m_member->get_broker_refferal($data['member']->id_refferer);
		else
			$broker=$this->m_member->get_broker();
		$data['broker'] = $broker;
		$data['active']='account_verification';
		$data['header']='comp/header';
		$data['footer']='comp/footer';
		$data['side_menu']='comp/side_menu';
		$data['content']='main/broker_verification';
		$this->load->view('main/template',$data);
	}
	public function broker_batch(){
		extract($_POST);
		$i=0;
		foreach($id_broker as $id_br){
			$data[]=array(
						'id_member'=>$this->session->userdata('id_member'),
						'id_broker'=>$id_br,
						'link_ib'=>$link_ib[$i],
						'link_client'=>(isset($link_client[$i])?$link_client[$i]:''),
						'broker_username'=>$broker_username[$i],
						'real_account'=>$real_account[$i],
					);
			$i++;
		}
		$this->m_member->insert_batch_broker($data);
		$this->m_member->update_member($this->session->userdata('id_member'),array('valid'=>2));
		redirect("member/my_profile");
	}
}
include_once('simpleimage.php');
