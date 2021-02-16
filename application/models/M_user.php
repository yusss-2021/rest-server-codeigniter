<?php
defined('BASEPATH') or exit('No direct script access allowed');

class M_user extends CI_Model
{

    function get_user()
    {
        return $this->db->get('user');
    }
}
