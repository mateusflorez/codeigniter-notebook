<div class="container">
	<!-- Titulo -->
	<div class="row">
		<div class="col-md-12 mt-5">
			<h1 class="text-center">Outros Contatos</h1>
		</div>
		<hr style="background-color: #000000; color: #000000; height: 1px;">
	</div>
	<!-- Botão -->
	<div class="row">
		<div class="col-md-12 mt-2 text-end">
			<a href="<?php echo base_url(); ?>" class="btn btn-primary btn-sm">Meus contatos</a>
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
