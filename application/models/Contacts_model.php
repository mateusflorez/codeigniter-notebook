<?php

  class Contacts_model extends CI_Model {

    public function insert($data)
    {
      //Salva endereço
      $address = array(
        'street' => $data['street'],
        'suite' => $data['suite'],
        'city' => $data['city'],
        'zipcode' => $data['zipcode']
      );
      $this->db->insert('contact_addresses', $address);
      $address_id = $this->db->insert_id();

      //Salva contato
      $contact = array(
        'name' => $data['name'],
        'username' => $data['username'],
        'email' => $data['email'],
        'phone' => $data['phone'],
        'website' => $data['website'],
        'address_id' => $address_id
      );

      return $this->db->insert('contacts', $contact);
    }

    public function get($uuid = NULL)
    {
      if($uuid === NULL){
        //Insere dados da chave estrangeira e busca contatos
        $this->db->join('contact_addresses', 'contact_addresses.id = contacts.address_id');
        $query = $this->db->get('contacts');

        return $query->result();
      } else {
        //Insere dados da chave estrangeira e busca contato
        $this->db->join('contact_addresses', 'contact_addresses.id = contacts.address_id');
        $query = $this->db->get_where('contacts', array('uuid' => $uuid));

        return $query->row();
      }

    }

    public function delete($uuid){
      //Apaga endereço
      $query = $this->db->get_where('contacts', array('uuid' => $uuid))->result_array();
      $data = array_shift($query);
      $this->db->delete('contact_addresses', array('id' => $data['address_id']));

      return $this->db->delete('contacts', array('uuid' => $uuid));
    }

    public function update($data){
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
      $this->db->update('contact_addresses', $address, array('id' => $result['address_id']));

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
