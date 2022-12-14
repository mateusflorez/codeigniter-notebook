<script>
  //Preenche DataTable com dados da API
  fetch("https://jsonplaceholder.typicode.com/users")
  //Retorna json com dados da API
  .then(function(response){
    return response.json();
  })
  //Popula DataTable com dados
  .then(function(data){
    $('#contacts').DataTable({
      language: {
        url: 'https://cdn.datatables.net/plug-ins/1.13.1/i18n/pt-BR.json'
      },
      data: data,
      columns: [
          { data: 'name', render: function(value, type){
            return value.split(/\s(.+)/)[0];
          } },
          { data: 'name', render: function(value, type){
            return value.split(/\s(.+)/)[1];
          } },
          { data: 'email' },
          { data: 'address.street' },
          { data: 'address.city' },
          { data: 'phone' },
          { data: 'id', render: function(value, type){
            return `
                <button type="button"  class="btn btn-sm btn-success" id="addToContacts" data-user-id="${value}">Adicionar</button>
            `
          } },
      ],
    });
  })

  //Adiciona contato aos contatos do banco
  $(document).on("click", "#addToContacts", function(e){
    e.preventDefault();

    const data_user_id = event.target.getAttribute('data-user-id');

    //Busca contatos da API
    fetch("https://jsonplaceholder.typicode.com/users")
    //Retorna json com dados da API
    .then(function(response){
      return response.json();
    })
    //Busca contato no resultado
    .then(function(data){
      const contato = data.find(function(contato){
        return contato.id == data_user_id;
      });
      const name = contato.name;
      const username = contato.username;
      const email = contato.email;
      const phone = contato.phone;
      const website = contato.website;
      const street = contato.address.street;
      const suite = contato.address.suite;
      const city = contato.address.city;
      const zipcode = contato.address.zipcode;

      //Insere contato no banco de dados
      $.ajax({
        url: "<?php echo base_url(); ?>insert",
        type: "post",
        dataType: "json",
        data: {
          name: name,
          username: username,
          email: email,
          phone: phone,
          website: website,
          street: street,
          suite: suite,
          city: city,
          zipcode: zipcode
        },
        success: function(data){
          //Exibe mensagem
          if(data.success)
          {
            toastr["success"](data.message);
          }else
          {
            toastr["error"](data.message);
          }
        }
      });
    })


  })
</script>
