<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Contacts extends CI_Controller {

	public function index()
	{
		//Se for ajax
		if ($this->input->is_ajax_request()){
			$results = $this->contacts_model->get();
			echo json_encode($results);
		} else {
			$data['title'] = 'Meus contatos';
			$this->load->view('templates/header',$data);
			$this->load->view('contacts');
			$this->load->view('templates/contact_modal_form', array(
				'modal_id' => 'newContactModal',
				'title' => 'Novo contato',
				'button_id' => 'addContact',
				'button_content' => 'Salvar contato',
			));
			$this->load->view('templates/contact_modal_form', array(
				'modal_id' => 'editContactModal',
				'title' => 'Editar contato',
				'button_id' => 'updateContact',
				'button_content' => 'Atualizar contato',
			));
			$this->load->view('templates/footer');
			$this->load->view('js/script_contacts');
		}
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
		if ($this->input->is_ajax_request() === FALSE){
			$this->show_403();
			return;
		}

		//Se validar
		$this->validate_form();
		if($this->form_validation->run() === FALSE){
			$data = array('success' => FALSE, 'message' => validation_errors());
			echo json_encode($data);
			return;
		}

		//Se inserir
		$ajax_data = $this->input->post();
		if($this->contacts_model->insert($ajax_data)){
			$data = array('success' => TRUE, 'message' => 'Contato adicionado com sucesso');
		} else {
			$data = array('success' => FALSE, 'message' => 'Falha ao salvar contato');
		}

		echo json_encode($data);
	}


	public function delete()
	{
		//Se for ajax
		if ($this->input->is_ajax_request() == FALSE){
			$this->show_403();
			return;
		}

		//Deleta contato
		$contact_uuid = $this->input->post('contact_uuid');
		$this->contacts_model->delete($contact_uuid);
		$data = array('success' => TRUE);

		echo json_encode($data);
	}

	public function get($uuid)
	{
		//Se for ajax
		if ($this->input->is_ajax_request() === FALSE){
			$this->show_403();
			return;
		}

		//Retorna dados do contato
		// $contact_uuid = $this->input->post('contact_uuid');
		$result = $this->contacts_model->get($uuid);
		$data = array('success' => TRUE, 'result' => $result);

		echo json_encode($data);
	}

	public function update(){
		//Se for ajax
		if ($this->input->is_ajax_request() === FALSE){
			$this->show_403();
			return;
		}

		//Se validar
		$this->validate_form();
		if($this->form_validation->run() === FALSE){
			$data = array('success' => FALSE, 'message' => validation_errors());
			echo json_encode($data);
			return;
		}

		//Se atualizar
		$ajax_data = $this->input->post();
		if($this->contacts_model->update($ajax_data)){
			$data = array('success' => TRUE, 'message' => 'Contato atualizado com sucesso');
		} else {
			$data = array('success' => FALSE, 'message' => 'Falha ao atualizar contato');
		}

		echo json_encode($data);
	}

	private function validate_form(){
		$this->form_validation
				->set_rules('name', 'Nome', 'trim|required')
				->set_rules('username', 'Usuário', 'trim|required')
				->set_rules('email', 'Email', 'trim|required|valid_email')
				->set_rules('phone', 'Telefone', 'trim|required')
				->set_rules('website', 'Site', 'trim|required')
				->set_rules('street', 'Rua', 'trim|required')
				->set_rules('suite', 'Numero/Apartamento', 'trim|required')
				->set_rules('city', 'Cidade', 'trim|required')
				->set_rules('zipcode', 'CEP', 'trim|required|max_length[16]');
	}

  private function show_403() {
    show_error("Acesso negado! <a target='_blank' href='".base_url()."'>Clique aqui pra ir para pagina principal</a>", 403, "403 Forbidden");
  }
}
