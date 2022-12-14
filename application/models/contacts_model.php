<?php

  class Contacts_model extends CI_Model {

    public function uuid4()
    {
      /* 32 random HEX + space for 4 hyphens */
      $out = bin2hex(random_bytes(18));

      $out[8]  = "-";
      $out[13] = "-";
      $out[18] = "-";
      $out[23] = "-";

      /* UUID v4 */
      $out[14] = "4";

      /* variant 1 - 10xx */
      $out[19] = ["8", "9", "a", "b"][random_int(0, 3)];

      return $out;
    }

    public function insert_data($data)
    {
      //gera UUID
      $uuid = $this->uuid4();

      //Salva endereço
      $address = array(
        'street' => $data['street'],
        'suite' => $data['suite'],
        'city' => $data['city'],
        'zipcode' => $data['zipcode']
      );
      $this->db->insert('address', $address);
      $address_id = $this->db->insert_id();

      //Salva contato
      $contact = array(
        'uuid' => $uuid,
        'name' => $data['name'],
        'username' => $data['username'],
        'email' => $data['email'],
        'phone' => $data['phone'],
        'website' => $data['website'],
        'address_id' => $address_id
      );

      return $this->db->insert('contacts', $contact);
    }

    public function get_data()
    {
      //Insere dados da chave estrangeira e busca contatos
      $this->db->join('address', 'address.id = contacts.address_id');
      $query = $this->db->get('contacts');

      return $query->result();
    }

    public function delete_data($uuid){
      //Apaga endereço
      $query = $this->db->get_where('contacts', array('uuid' => $uuid))->result_array();
      $data = array_shift($query);
      $this->db->delete('address', array('id' => $data['address_id']));

      return $this->db->delete('contacts', array('uuid' => $uuid));
    }

    public function edit_data($uuid){
      //Insere dados da chave estrangeira e busca contato
      $this->db->join('address', 'address.id = contacts.address_id');
      $query = $this->db->get_where('contacts', array('uuid' => $uuid));

      return $query->row();
    }

    public function update_data($data){
      //Busca dados para endereço
      $query = $this->db->get_where('contacts', array('uuid' =>$data['contact_uuid']))->result_array();
      $result = array_shift($query);

      //Atualiza endereço
      $address = array(
        'street' => $data['street'],
        'suite' => $data['suite'],
        'city' => $data['city'],
        'zipcode' => $data['zipcode']
      );
      $this->db->update('address', $address, array('id' => $result['address_id']));

      //Atualiza contato
      $contact = array(
        'name' => $data['name'],
        'username' => $data['username'],
        'email' => $data['email'],
        'phone' => $data['phone'],
        'website' => $data['website']
      );
      return $this->db->update('contacts', $contact, array('uuid' => $data['contact_uuid']));
    }

  }
