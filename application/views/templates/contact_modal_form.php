<div class="modal fade" id="<?= $modal_id ?>" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<h1 class="modal-title fs-5" id="exampleModalLabel"><?= $title ?></h1>
				<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
			</div>
			<div class="modal-body">
				<!-- Formulário para contato -->
				<form method="post" id="form">
					<div class="row">
						<div class="col-md-6" style="padding-right:20px; border-right: 1px solid #ccc;">
							<span style="font-weight: 500;font-size: 18px;">Dados do contato</span>
              <input type="hidden" id="contact_uuid" name="contact_uuid" value="">
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
				<button type="button" class="btn btn-primary" id="<?= $button_id ?>"><?= $button_content ?></button>
			</div>
		</div>
	</div>
</div>
