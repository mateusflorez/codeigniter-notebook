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
        //Busca os dados dos contatos juntamente aos dados do endereço
        $this->db->join('contact_addresses', 'contact_addresses.id = contacts.address_id');
        $query = $this->db->get('contacts');

        return $query->result();
      } else {
        //Busca os dados de um contato específico juntamente aos dados do endereço
        $this->db->join('contact_addresses', 'contact_addresses.id = contacts.address_id');
        $query = $this->db->get_where('contacts', array('uuid' => $uuid));

        return $query->row();
      }

    }
    public function delete($uuid){
      $contact = $this->get($uuid);

      //Apaga endereço
      $this->db->delete('contact_addresses', array('id' => $contact->address_id));

      return $this->db->delete('contacts', array('uuid' => $uuid));
    }

    public function update($uuid, $data){
      //Busca dados para endereço
      $contact = $this->get($uuid);

      //Atualiza endereço
      $address = array(
        'street' => $data['street'],
        'suite' => $data['suite'],
        'city' => $data['city'],
        'zipcode' => $data['zipcode']
      );
      $this->db->update('contact_addresses', $address, array('id' => $contact->address_id));

      //Atualiza contato
      $new_contact_data = array(
        'name' => $data['name'],
        'username' => $data['username'],
        'email' => $data['email'],
        'phone' => $data['phone'],
        'website' => $data['website']
      );
      return $this->db->update('contacts', $new_contact_data, array('uuid' => $uuid));
    }

  }
