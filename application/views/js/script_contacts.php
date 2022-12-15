<script>
  const CONTACT_FORM_FIELDS = [
    "name",
    "username",
    "email",
    "phone",
    "website",
    "street",
    "suite",
    "city",
    "zipcode"
  ];

  //SweetAlert
  const swalWithBootstrapButtons = Swal.mixin({
    customClass: {
      actions: 'my-actions',
      confirmButton: 'btn btn-success',
      cancelButton: 'btn btn-danger right-gap'
    },
    buttonsStyling: false
  })

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
            <a href="#" data-contact-uuid="${value}" id="edit" class="btn btn-sm btn-secondary">Editar</a>
            <a href="#" data-contact-uuid="${value}" id="del" class="btn btn-sm btn-danger">Deletar</a>
          `
        } },
    ],
  });

  //Adicionar Contato
  $(document).on("click", "#addContact", function(e){
    e.preventDefault();

    const newContactParams = getContactFormValues("newContactModal");

    if (!validateContactParams(newContactParams))
      return;

    $.ajax({
      url: "<?php echo base_url(); ?>contacts/insert",
      type: "post",
      dataType: "json",
      data: newContactParams,
      success: function(data){
        //Atualiza tabela e exibe mensagem
        if(data.success){
          contactsDataTable.ajax.reload(function() {
            const newContactModal = $('#newContactModal');
            newContactModal.find("#form")[0].reset();
            newContactModal.modal('hide');
            toastr["success"](data.message);
          });
        } else {
          toastr["error"](data.message);
        }
      }
    });
  });

  //Apaga contato
  $(document).on("click", "#del", function(e){
    e.preventDefault();

    const contactUUID = $(this).attr("data-contact-uuid");

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
          url: "<?php echo base_url(); ?>contacts/delete",
          type: "post",
          statusCode: {
            404: function() {
              showContactNotFoundAlert((_result) => contactsDataTable.ajax.reload());
            }
          },
          data: {
            contact_uuid: contactUUID
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
      }
    })
  });

  //Popula modal com dados do contato
  $(document).on("click", "#edit", function(e){
    e.preventDefault();

    const contactUUID = $(this).attr("data-contact-uuid");

    $.ajax({
      url: "<?php echo base_url(); ?>contacts/"+contactUUID,
      type: "get",
      dataType: "json",
      statusCode: {
        404: function() {
          showContactNotFoundAlert((_result) => contactsDataTable.ajax.reload());
        }
      },
      success: function(data){
        const editContactModal = $('#editContactModal');
        editContactModal.modal('show');
        editContactModal.find("#contact_uuid").val(data.uuid);

        CONTACT_FORM_FIELDS.forEach(function(field){
          editContactModal.find(`#${field}`).val(data[field]);
        })
      }
    });
  });

  //Atualiza contato
  $(document).on("click", "#updateContact", function(e){
    e.preventDefault();

    const editContactModal = $('#editContactModal');
    const updateContactParams = getContactFormValues("editContactModal");
    const contactUUID = editContactModal.find("#contact_uuid").val();

    if (!validateContactParams(updateContactParams))
      return;

    $.ajax({
      url: "<?php base_url(); ?>contacts/update",
      type: "post",
      dataType: "json",
      data: Object.assign({}, {contact_uuid: contactUUID}, updateContactParams),
      success: function(data){
        //Atualiza tabela e exibe mensagem
        if(data.success){
          contactsDataTable.ajax.reload(function() {
            editContactModal.find("#form")[0].reset();
            editContactModal.modal('hide');
            toastr["success"](data.message);
          });
        } else {
          toastr["error"](data.message);
        }
      }
    });
  });

  // Extrai os valores dos inputs do form
  function getContactFormValues(formId) {
    const contactParams = {};

    //Armazena dados do formulário
    CONTACT_FORM_FIELDS.forEach(function(field){
      contactParams[field] = $(`#${formId} #${field}`).val();
    })

    //Retorna objeto com os dados
    return contactParams;
  }

  // Valida os parametros do formulário
  function validateContactParams(contactParams) {
    let isValid = false;

    //Verifica se todos os campos estão preenchidos
    const hasMissingFields = CONTACT_FORM_FIELDS.some(function(field){
      return contactParams[field] == '';
    })

    if (hasMissingFields)
      toastr["error"]("Favor preencher todos os campos");
    else if (getWordCount(contactParams["name"]) < 2)
      toastr["error"]("Favor preencher nome e sobrenome");
    else
      isValid = true;

    return isValid;
  }

  function showContactNotFoundAlert(successCb) {
    swalWithBootstrapButtons.fire(
      'Contato não encontrado!',
      'O contato selecionado não foi encontrado, a página será recarregada novamente.',
      'info'
    ).then(successCb);
  }
  //Conta quantidade de palavras
  function getWordCount(str) {
    return str.split(' ')
    .filter(function(n) { return n != '' })
    .length;
  }
</script>
