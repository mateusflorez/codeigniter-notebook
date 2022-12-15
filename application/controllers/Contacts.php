<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Contacts extends CI_Controller {

	public function index()
	{
		//Se não for uma requisição AJAX redireciona para pagina de acesso proibido
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
		//Se não for uma requisição AJAX redireciona para pagina de acesso proibido
		if (!$this->input->is_ajax_request()){
			return $this->show_403();
		}

		//Se validação falhar retorna erros de validação
		$valid_form = $this->validate_form();
		if(!$valid_form){
			return $this->show_validation_errors();
		}

		//Se inserir retorna json com mensagem e status
		$ajax_data = $this->input->post();
		if($this->contacts_model->insert($ajax_data)){
			$data = array('success' => TRUE, 'message' => 'Contato adicionado com sucesso');
		} else {
			$data = array('success' => FALSE, 'message' => 'Falha ao salvar contato');
		}

		set_status_header(201);
		echo json_encode($data);
	}


	public function delete()
	{
		//Se não for uma requisição AJAX redireciona para pagina de acesso proibido
		if (!$this->input->is_ajax_request()){
			return $this->show_403();
		}

		//Deleta contato
		$contact_uuid = $this->input->post('contact_uuid');
		$this->contacts_model->delete($contact_uuid);

		set_status_header(204);
	}

	public function get($uuid)
	{
		//Se não for uma requisição AJAX redireciona para pagina de acesso proibido
		if (!$this->input->is_ajax_request()){
			return $this->show_403();
		}

		//Retorna dados do contato
		$result = $this->contacts_model->get($uuid);

		echo json_encode($result);
	}

	public function update(){
		//Se não for uma requisição AJAX redireciona para pagina de acesso proibido
		if (!$this->input->is_ajax_request()){
			return $this->show_403();
		}

		//Se validação falhar retorna erros de validação
		$valid_form = $this->validate_form();
		if(!$valid_form){
			return $this->show_validation_errors();
		}

		//Se atualizar retorna json com mensagem e status
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

		return $this->form_validation->run();
	}

  private function show_403() {
    show_error("Acesso negado! <a target='_blank' href='".base_url()."'>Clique aqui pra ir para pagina principal</a>", 403, "403 Forbidden");
  }

	private function show_validation_errors(){
		$data = array('success' => FALSE, 'message' => validation_errors());
		echo json_encode($data);
	}
}
