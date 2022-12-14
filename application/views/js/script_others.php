<script>
  //Preenche DataTable com dados da API
  $('#contacts').DataTable({
      language: {
        url: 'https://cdn.datatables.net/plug-ins/1.13.1/i18n/pt-BR.json'
      },
      ajax: {
        url: "https://jsonplaceholder.typicode.com/users",
        dataSrc: ""
      },
      columns: [
          {
            data: 'name',
            render: function(value, type){
              return value.split(/\s(.+)/)[0];
            }
          },
          {
            data: 'name',
            render: function(value, type){
              return value.split(/\s(.+)/)[1];
            }
          },
          { data: 'email' },
          { data: 'address.street' },
          { data: 'address.city' },
          { data: 'phone' },
          { data: 'id', render: function(value, type){
            return `
              <button type="button"  class="btn btn-sm btn-success" id="addToContacts" data-user-id="${value}">Adicionar</button>
            `
          }},
      ],
    });

  //Adiciona contato aos contatos do banco
  $(document).on("click", "#addToContacts", function(e){
    e.preventDefault();

    const dataUserId = $(this).attr("data-user-id");

    //Busca contato da API
    $.ajax({
        url: "https://jsonplaceholder.typicode.com/users/"+dataUserId,
        type: "get",
        dataType: "json",
        success: function(data){
          const newContactParams = {
            name: data.name,
            username: data.username,
            email: data.email,
            phone: data.phone,
            website: data.website,
            street: data.address.street,
            suite: data.address.suite,
            city: data.address.city,
            zipcode: data.address.zipcode
          };

          //Insere contato no banco de dados
          $.ajax({
            url: "<?php echo base_url(); ?>contacts/insert",
            type: "post",
            dataType: "json",
            data: newContactParams,
            success: function(data){
              //Exibe mensagem
              const message = data.message;

              data.success ? toastr["success"](message) : toastr["error"](message);
            }
          });
        }
      });
    })
</script>
