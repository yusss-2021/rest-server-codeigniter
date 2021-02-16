<?php
defined('BASEPATH') or exit('No direct script access allowed');

class M_News extends CI_Model
{
    // rest-client
    function get_data()
    {
        return $this->db->get('news');
    }
    function get_carousel()
    {
        return $this->db->get('news', 3);
    }

    function get_id($param)
    {
        return $this->db->get_where('news', $param);
    }

    // rest-server
    function insert($params)
    {
        $this->db->insert("news", $params);
        return $this->db->affected_rows();
    }

    function check_insert($param)
    {
        return $this->db->field_exists($param, "news");
    }

    function delete($params)
    {
        $this->db->delete("news", $params);
        return $this->db->affected_rows();
    }
    function update($id, $data)
    {
        $this->db->where($id);
        $this->db->update("news", $data);
    }
    function where($param)
    {
        $this->db->get_where('news', $param);
        return $this->db->affected_rows();
    }
}
