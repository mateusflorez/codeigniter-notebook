<script>
  //Preenche DataTable com dados da API
  fetch("https://jsonplaceholder.typicode.com/users")
  //Retorna json com dados da API
  .then(function(response){
    return response.json();
  })
  //Popula DataTable com dados
  .then(function(contatos){
    $('#contacts').DataTable({
      language: {
        url: 'https://cdn.datatables.net/plug-ins/1.13.1/i18n/pt-BR.json'
      },
      data: contatos,
      columns: [
          { data: 'id' },
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
                <button type="button"  class="btn btn-sm btn-outline-success" id="addToContacts" data-atr1="${value}">Adicionar</button>
            `
          } },
      ],
    });
  })

  //Adiciona contato aos contatos do banco
  $(document).on("click", "#addToContacts", function(e){
    e.preventDefault();

    let atr1 = event.target.getAttribute('data-atr1');

    //Busca contatos da API
    fetch("https://jsonplaceholder.typicode.com/users")
    //Retorna json com dados da API
    .then(function(response){
      return response.json();
    })
    //Busca contato no resultado
    .then(function(contatos){
      let contato = contatos.find(function(contato){
        return contato.id == atr1;
      });
      var name = contato.name;
      var username = contato.username;
      var email = contato.email;
      var phone = contato.phone;
      var website = contato.website;
      var street = contato.address.street;
      var suite = contato.address.suite;
      var city = contato.address.city;
      var zipcode = contato.address.zipcode;

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
