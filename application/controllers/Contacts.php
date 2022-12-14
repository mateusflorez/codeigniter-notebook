<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Contacts extends CI_Controller {

	public function index()
	{
		$data['title'] = 'Meus contatos';
		$this->load->view('templates/header',$data);
		$this->load->view('contacts');
		$this->load->view('templates/footer');
		$this->load->view('js/script_contacts');
	}

	public function others()
	{
		$data['title'] = 'Outros contatos';
		$this->load->view('templates/header',$data);
		$this->load->view('others');
		$this->load->view('templates/footer');
		$this->load->view('js/script_others');
	}

	public function insert()
	{
		//Se for ajax
		if ($this->input->is_ajax_request())
		{
			$this->form_validation->set_rules('name', 'Name', 'required');
			$this->form_validation->set_rules('username', 'Username', 'required');
			$this->form_validation->set_rules('email', 'Email', 'required|valid_email');
			$this->form_validation->set_rules('phone', 'Phone', 'required');
			$this->form_validation->set_rules('website', 'Website', 'required');
			$this->form_validation->set_rules('street', 'Street', 'required');
			$this->form_validation->set_rules('suite', 'Suite', 'required');
			$this->form_validation->set_rules('city', 'City', 'required');
			$this->form_validation->set_rules('zipcode', 'Zipcode', 'required');

			//Se validar
			if($this->form_validation->run() === TRUE)
			{
				$ajax_data = $this->input->post();
				//Se inserir
				if($this->contacts_model->insert_data($ajax_data))
				{
					$data = array('success' => TRUE, 'message' => 'Contato adicionado com sucesso');
				} else
				{
					$data = array('success' => FALSE, 'message' => 'Falha ao salvar contato');
				}
			} else
			{
				$data = array('success' => FALSE, 'message' => validation_errors());
			}
			echo json_encode($data);
		} else
		{
			echo "Nenhum acesso direto ao script é permitido";
		}
	}

	public function fetch()
	{
		//Se for ajax
		if ($this->input->is_ajax_request())
		{
			$posts = $this->contacts_model->get_data();
			$data = array('success' => TRUE, 'posts' => $posts);
			echo json_encode($data);
		} else
		{
			echo "Nenhum acesso direto ao script é permitido";
		}
	}

	public function delete()
	{
		//Se for ajax
		if ($this->input->is_ajax_request())
		{
			$del_uuid = $this->input->post('del_uuid');
			$this->contacts_model->delete_data($del_uuid);
			$data = array('success' => TRUE);
			echo json_encode($data);
		} else
		{
			echo "Nenhum acesso direto ao script é permitido";
		}
	}

	public function edit()
	{
		//Se for ajax
		if ($this->input->is_ajax_request())
		{
			$edit_uuid = $this->input->post('edit_uuid');
			$post = $this->contacts_model->edit_data($edit_uuid);
			$data = array('success' => TRUE, 'post' => $post);
			echo json_encode($data);
		} else
		{
			echo "Nenhum acesso direto ao script é permitido";
		}
	}

	public function update(){
		//Se for ajax
		if ($this->input->is_ajax_request())
		{
			$this->form_validation->set_rules('name', 'Name', 'required');
			$this->form_validation->set_rules('username', 'Username', 'required');
			$this->form_validation->set_rules('email', 'Email', 'required|valid_email');
			$this->form_validation->set_rules('phone', 'Phone', 'required');
			$this->form_validation->set_rules('website', 'Website', 'required');
			$this->form_validation->set_rules('street', 'Street', 'required');
			$this->form_validation->set_rules('suite', 'Suite', 'required');
			$this->form_validation->set_rules('city', 'City', 'required');
			$this->form_validation->set_rules('zipcode', 'Zipcode', 'required');

			//Se validar
			if($this->form_validation->run() === TRUE)
			{
				$ajax_data = $this->input->post();
				//Se atualizar
				if($this->contacts_model->update_data($ajax_data))
				{
					$data = array('success' => TRUE, 'message' => 'Contato atualizado com sucesso');
				} else
				{
					$data = array('success' => FALSE, 'message' => 'Falha ao atualizar contato');
				}
			} else
			{
				$data = array('success' => FALSE, 'message' => validation_errors());
			}
			echo json_encode($data);
		} else
		{
			echo "Nenhum acesso direto ao script é permitido";
		}
	}
}
