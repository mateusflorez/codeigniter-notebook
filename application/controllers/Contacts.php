<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Contacts extends CI_Controller
{

	public function index()
	{
		//Se não for uma requisição AJAX redireciona para pagina de acesso proibido
		if ($this->input->is_ajax_request()) {
			$search = $_GET["search"]["value"];
			$per_page = (int)$_GET['length'];
			$page = ceil((int)$_GET['start'] / $per_page);
			$results = $this->contacts_model->get(null, $page + 1, $per_page, $search);
			$count = $this->contacts_model->count();
			if ($search) {
				$filtered_count = $this->contacts_model->count($search);
			}
			echo json_encode(array(
				'data' => $results,
				'recordsTotal' => $count,
				'recordsFiltered' => $search ? $filtered_count : $count
			));
		} else {
			$data['title'] = 'Meus contatos';
			$this->load->view('templates/header', $data);
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

	public function external()
	{
		$data['title'] = 'Outros contatos';
		$this->load->view('templates/header', $data);
		$this->load->view('others');
		$this->load->view('templates/footer');
		$this->load->view('js/script_others');
	}

	public function insert()
	{
		//Se não for uma requisição AJAX redireciona para pagina de acesso proibido
		if (!$this->input->is_ajax_request()) {
			return $this->show_403();
		}

		//Se validação falhar retorna erros de validação
		$valid_form = $this->validate_form();
		if (!$valid_form) {
			return $this->show_validation_errors();
		}

		//Se inserir retorna json com mensagem e status
		$ajax_data = $this->input->post();
		$success = $this->contacts_model->insert($ajax_data);
		$message = $success ? 'Contato adicionado com sucesso' : 'Falha ao salvar contato';

		set_status_header(201);
		echo json_encode(array('success' => $success, 'message' => $message));
	}


	public function delete($uuid)
	{
		//Se não for uma requisição AJAX redireciona para pagina de acesso proibido
		if (!$this->input->is_ajax_request()) {
			return $this->show_403();
		}

		//Se não existir o contato no DB, retornar 404
		if (!$this->contacts_model->get($uuid)) {
			return set_status_header(404);
		}

		//Deleta contato
		$this->contacts_model->delete($uuid);

		set_status_header(204);
	}

	public function get($uuid)
	{
		//Se não for uma requisição AJAX redireciona para pagina de acesso proibido
		if (!$this->input->is_ajax_request()) {
			return $this->show_403();
		}

		$contact = $this->contacts_model->get($uuid);
		//Se não existir o contato no DB, retornar 404
		if (!$contact) {
			return set_status_header(404);
		}

		echo json_encode($contact);
	}

	public function update($uuid)
	{
		//Se não for uma requisição AJAX redireciona para pagina de acesso proibido
		if (!$this->input->is_ajax_request()) {
			return $this->show_403();
		}

		//Se validação falhar retorna erros de validação
		$valid_form = $this->validate_form();
		if (!$valid_form) {
			return $this->show_validation_errors();
		}

		$ajax_data = $this->input->post();

		if (!$this->contacts_model->get($uuid)) {
			return set_status_header(404);
		}

		//Se atualizar retorna json com mensagem e status
		$success = $this->contacts_model->update($uuid, $ajax_data);
		$message = $success ? 'Contato atualizado com sucesso' : 'Falha ao atualizar contato';

		echo json_encode(array('success' => $success, 'message' => $message));
	}

	private function validate_form()
	{
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

	private function show_403()
	{
		show_error("Acesso negado! <a target='_blank' href='" . base_url() . "'>Clique aqui pra ir para pagina principal</a>", 403, "403 Forbidden");
	}

	private function show_validation_errors()
	{
		$data = array('success' => FALSE, 'message' => validation_errors());
		echo json_encode($data);
	}
}
