<script>
  const contactsDataTable = $('#contacts').DataTable({
    language: {
      url: 'https://cdn.datatables.net/plug-ins/1.13.1/i18n/pt-BR.json'
    },
    ajax: {
      url: "<?php echo base_url(); ?>contacts",
      dataSrc: '',
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
        { data: 'street' },
        { data: 'city' },
        { data: 'phone' },
        { data: 'uuid', render: function(value, type){
          return `
            <a href="#" value="${value}" id="edit" class="btn btn-sm btn-secondary">Editar</a>
            <a href="#" value="${value}" id="del" class="btn btn-sm btn-danger">Deletar</a>
          `
        } },
    ],
  });

  //Adicionar Contato
  $(document).on("click", "#addContact", function(e){
    e.preventDefault();

    const name = $("#name").val();
    const username = $("#username").val();
    const email = $("#email").val();
    const phone = $("#phone").val();
    const website = $("#website").val();
    const street = $("#street").val();
    const suite = $("#suite").val();
    const city = $("#city").val();
    const zipcode = $("#zipcode").val();

    //Verifica se todos os campos estão preenchidos
    if(name == "" || username == "" || email == "" || phone == "" || website == "" || street == "" || suite == "" || city == "" || zipcode == "")
    {
      toastr["error"]("Favor preencher todos os campos");
    } else if(getWordCount(name)<2) {
      toastr["error"]("Favor preencher nome e sobrenome");
    } else
    {
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
          //Atualiza tabela e exibe mensagem
          if(data.success)
          {
            contactsDataTable.ajax.reload(function() {
              $("#form")[0].reset();
              $('#exampleModal').modal('hide');
              toastr["success"](data.message);
            });
          } else
          {
            toastr["error"](data.message);
          }
        }
      });
    }
  });

  //Apaga contato
  $(document).on("click", "#del", function(e){
    e.preventDefault();

    const contact_uuid = $(this).attr("value");

    //SweetAlert
    const swalWithBootstrapButtons = Swal.mixin({
      customClass: {
        actions: 'my-actions',
        confirmButton: 'btn btn-success',
        cancelButton: 'btn btn-danger right-gap'
      },
      buttonsStyling: false
    })

    swalWithBootstrapButtons.fire({
      title: 'Tem certeza?',
      text: "Você não poderá de reverter isso!",
      icon: 'warning',
      showCancelButton: true,
      confirmButtonText: 'Sim, apagar!',
      cancelButtonText: 'Não, cancelar!',
      reverseButtons: true
    }).then((result) => {
      if (result.isConfirmed) {

        //Envia requisição
        $.ajax({
          url: "<?php echo base_url(); ?>delete",
          type: "post",
          dataType: "json",
          data: {
            contact_uuid: contact_uuid
          },
          success: function(data){
            //Atualiza tabela e exibe mensagem
            contactsDataTable.ajax.reload(function() {
              swalWithBootstrapButtons.fire(
                'Deletado!',
                'Seu contato foi apagado.',
                'success'
              );
            });
          }
        });

      } else if (
        result.dismiss === Swal.DismissReason.cancel
      ) {
        swalWithBootstrapButtons.fire(
          'Cancelado',
          'Seu contato está a salvo!',
          'error'
        )
      }
    })
  });

  //Popula modal com dados do contato
  $(document).on("click", "#edit", function(e){
    e.preventDefault();

    const contact_uuid = $(this).attr("value");

    $.ajax({
      url: "<?php echo base_url(); ?>edit",
      type: "post",
      dataType: "json",
      data: {
        contact_uuid: contact_uuid
      },
      success: function(data){
        $('#edit_modal').modal('show');
        $("#edit_contact_id").val(data.result.uuid);
        $("#edit_name").val(data.result.name);
        $("#edit_username").val(data.result.username);
        $("#edit_email").val(data.result.email);
        $("#edit_phone").val(data.result.phone);
        $("#edit_website").val(data.result.website);
        $("#edit_street").val(data.result.street);
        $("#edit_suite").val(data.result.suite);
        $("#edit_city").val(data.result.city);
        $("#edit_zipcode").val(data.result.zipcode);
      }
    });
  });

  //Atualiza contato
  $(document).on("click", "#updateContact", function(e){
    e.preventDefault();

    const contact_uuid = $("#edit_contact_id").val();
    const name = $("#edit_name").val();
    const username = $("#edit_username").val();
    const email = $("#edit_email").val();
    const phone = $("#edit_phone").val();
    const website = $("#edit_website").val();
    const street = $("#edit_street").val();
    const suite = $("#edit_suite").val();
    const city = $("#edit_city").val();
    const zipcode = $("#edit_zipcode").val();

    //Verifica se campos estão preenchidos
    if(contact_uuid == "" || name == "" || username == "" || email == "" || phone == "" || website == "" || street == "" || suite == "" || city == "" || zipcode == "")
    {
      toastr["error"]("Favor preencher todos os campos");
    } else if(getWordCount(name)<2) {
      toastr["error"]("Favor preencher nome e sobrenome");
    } else
    {
      $.ajax({
        url: "<?php base_url(); ?>update",
        type: "post",
        dataType: "json",
        data: {
          contact_uuid: contact_uuid,
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
          //Atualiza tabela e exibe mensagem
          if(data.success)
          {
            contactsDataTable.ajax.reload(function() {
              $("#edit-form")[0].reset();
              $('#edit_modal').modal('hide');
              toastr["success"](data.message);
            });
          } else
          {
            toastr["error"](data.message);
          }
        }
      });
    }
  });

  //Conta quantidade de palavras
  function getWordCount(str) {
    return str.split(' ')
    .filter(function(n) { return n != '' })
    .length;
  }
</script>
