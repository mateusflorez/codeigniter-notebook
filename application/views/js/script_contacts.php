<script>
  //Enviar formulário
  $(document).on("click", "#addContact", function(e){
    e.preventDefault();

    var name = $("#name").val();
    var username = $("#username").val();
    var email = $("#email").val();
    var phone = $("#phone").val();
    var website = $("#website").val();
    var street = $("#street").val();
    var suite = $("#suite").val();
    var city = $("#city").val();
    var zipcode = $("#zipcode").val();

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
            $('#contacts').DataTable().destroy();
            fetch();
            $("#form")[0].reset();
            $('#exampleModal').modal('hide');
            toastr["success"](data.message);
          } else
          {
            toastr["error"](data.message);
          }
        }
      });
    }
  });

  //Preenche DataTable com dados do banco
  function fetch(){
    $.ajax({
      url: "<?php echo base_url(); ?>fetch",
      type: "post",
      dataType: "json",
      success: function(data){
        //DataTable
        $('#contacts').DataTable({
          language: {
            url: 'https://cdn.datatables.net/plug-ins/1.13.1/i18n/pt-BR.json'
          },
          data: data.posts,
          columns: [
              { data: 'id' },
              { data: 'name', render: function(value, type){
                return value.split(/\s(.+)/)[0];
              } },
              { data: 'name', render: function(value, type){
                return value.split(/\s(.+)/)[1];
              } },
              { data: 'email' },
              { data: 'street' },
              { data: 'city' },
              { data: 'phone' },
              { data: 'uuid', render: function(value, type){
                return `
                    <a href="#" value="${value}" id="edit" class="btn btn-sm btn-outline-success">Editar</a>
                    <a href="#" value="${value}" id="del" class="btn btn-sm btn-outline-danger">Deletar</a>
                `
              } },
          ],
        });
      }
    });
  }
  fetch();

  //Apaga contato
  $(document).on("click", "#del", function(e){
    e.preventDefault();

    var del_uuid = $(this).attr("value");

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
            del_uuid: del_uuid
          },
          success: function(data){
            //Atualiza tabela e exibe mensagem
            $('#contacts').DataTable().destroy();
            fetch();
            swalWithBootstrapButtons.fire(
              'Deletado!',
              'Seu contato foi apagado.',
              'success'
            )
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

    var edit_uuid = $(this).attr("value");

    $.ajax({
      url: "<?php echo base_url(); ?>edit",
      type: "post",
      dataType: "json",
      data: {
        edit_uuid: edit_uuid
      },
      success: function(data){
        $('#edit_modal').modal('show');
        $("#edit_contact_id").val(data.post.uuid);
        $("#edit_name").val(data.post.name);
        $("#edit_username").val(data.post.username);
        $("#edit_email").val(data.post.email);
        $("#edit_phone").val(data.post.phone);
        $("#edit_website").val(data.post.website);
        $("#edit_street").val(data.post.street);
        $("#edit_suite").val(data.post.suite);
        $("#edit_city").val(data.post.city);
        $("#edit_zipcode").val(data.post.zipcode);
      }
    });
  });

  //Atualiza contato
  $(document).on("click", "#updateContact", function(e){
    e.preventDefault();

    var contact_uuid = $("#edit_contact_id").val();
    var name = $("#edit_name").val();
    var username = $("#edit_username").val();
    var email = $("#edit_email").val();
    var phone = $("#edit_phone").val();
    var website = $("#edit_website").val();
    var street = $("#edit_street").val();
    var suite = $("#edit_suite").val();
    var city = $("#edit_city").val();
    var zipcode = $("#edit_zipcode").val();

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
            $('#contacts').DataTable().destroy();
            fetch();
            $("#edit-form")[0].reset();
            $('#edit_modal').modal('hide');
            toastr["success"](data.message);
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
