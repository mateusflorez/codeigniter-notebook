<div class="container">
	<!-- Titulo -->
	<div class="row">
		<div class="col-md-12 mt-5">
			<h1 class="text-center">Meus Contatos</h1>
		</div>
		<hr style="background-color: #000000; color: #000000; height: 1px;">
	</div>
	<!-- Botões -->
	<div class="row">
		<!-- Botão Modal -->
		<div class="col-md-6 mt-2">
			<button type="button" class="btn btn-success btn-sm" data-bs-toggle="modal" data-bs-target="#exampleModal">
				Novo contato
			</button>
		</div>
		<!-- Botão -->
		<div class="col-md-6 mt-2 text-end">
			<a href="<?php echo base_url(); ?>others" class="btn btn-primary btn-sm">Outros contatos</a>
		</div>
	</div>
	<!-- Data Table -->
	<div class="row">
		<div class="col-md-12 mt-4">
			<table class="table" id="contacts">
				<thead>
					<tr>
						<th>Nome</th>
						<th>Sobrenome</th>
						<th>Email</th>
						<th>Rua</th>
						<th>Cidade</th>
						<th>Telefone</th>
						<th>Ações</th>
					</tr>
					</thead>
					<tbody id="data-output">
						<!-- Populado por JavaScript -->
					</tbody>
			</table>
		</div>
	</div>
</div>

<!-- Modal novo contato -->
<div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<h1 class="modal-title fs-5" id="exampleModalLabel">Novo contato</h1>
				<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
			</div>
			<div class="modal-body">
				<!-- Formulário para contato -->
				<form method="post" id="form">
					<div class="row">
						<div class="col-md-6" style="padding-right:20px; border-right: 1px solid #ccc;">
							<span style="font-weight: 500;font-size: 18px;">Dados do contato</span>
							<div class="form-group">
								<label>Nome</label>
								<input type="text" id="name" class="form-control">
							</div>
							<div class="form-group">
								<label>Usuário</label>
								<input type="text" id="username" class="form-control">
							</div>
							<div class="form-group">
								<label>Email</label>
								<input type="email" id="email" class="form-control">
							</div>
							<div class="form-group">
								<label>Telefone</label>
								<input type="text" id="phone" class="form-control">
							</div>
							<div class="form-group">
								<label>Website</label>
								<input type="text" id="website" class="form-control">
							</div>
						</div>
						<div class="col-md-6">
							<span style="font-weight: 500;font-size: 18px;">Endereço</span>
							<div class="form-group">
								<label>Rua</label>
								<input type="text" id="street" class="form-control">
							</div>
							<div class="form-group">
								<label>Número/Apartamento</label>
								<input type="text" id="suite" class="form-control">
							</div>
							<div class="form-group">
								<label>Cidade</label>
								<input type="text" id="city" class="form-control">
							</div>
							<div class="form-group">
								<label>CEP</label>
								<input type="text" id="zipcode" class="form-control">
							</div>
						</div>
					</div>
				</form>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Fechar</button>
				<button type="button" class="btn btn-primary" id="addContact">Salvar contato</button>
			</div>
		</div>
	</div>
</div>

<!-- Modal editar contato -->
<div class="modal fade" id="edit_modal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<h1 class="modal-title fs-5" id="exampleModalLabel">Editar contato</h1>
				<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
			</div>
			<div class="modal-body">
				<!-- Formulário para contato -->
				<form method="post" id="edit-form">
					<div class="row">
						<div class="col-md-6" style="padding-right:20px; border-right: 1px solid #ccc;">
							<span style="font-weight: 500;font-size: 18px;">Dados do contato</span>
							<input type="hidden" id="edit_contact_id" name="edit_contact_id" value="">
							<div class="form-group">
								<label>Nome</label>
								<input type="text" id="edit_name" class="form-control">
							</div>
							<div class="form-group">
								<label>Usuário</label>
								<input type="text" id="edit_username" class="form-control">
							</div>
							<div class="form-group">
								<label>Email</label>
								<input type="email" id="edit_email" class="form-control">
							</div>
							<div class="form-group">
								<label>Telefone</label>
								<input type="text" id="edit_phone" class="form-control">
							</div>
							<div class="form-group">
								<label>Website</label>
								<input type="text" id="edit_website" class="form-control">
							</div>
						</div>
						<div class="col-md-6">
							<span style="font-weight: 500;font-size: 18px;">Endereço</span>
							<div class="form-group">
								<label>Rua</label>
								<input type="text" id="edit_street" class="form-control">
							</div>
							<div class="form-group">
								<label>Número/Apartamento</label>
								<input type="text" id="edit_suite" class="form-control">
							</div>
							<div class="form-group">
								<label>Cidade</label>
								<input type="text" id="edit_city" class="form-control">
							</div>
							<div class="form-group">
								<label>CEP</label>
								<input type="text" id="edit_zipcode" class="form-control">
							</div>
						</div>
					</div>
				</form>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Fechar</button>
				<button type="button" class="btn btn-primary" id="updateContact">Atualizar contato</button>
			</div>
		</div>
	</div>
</div>
