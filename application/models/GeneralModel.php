<?php
defined('BASEPATH') or exit('No direct script access allowed');
class GeneralModel extends CI_Model
{
    function connectDB($dbName)
    {
        $dynamicDB = array(
            'hostname' => MAIN_DB_HOST,
            'username' => MAIN_DB_UNAME,
            'password' => MAIN_DB_PASSWORD,
            'database' => $dbName,
            'dbdriver' => 'mysqli',
            'dbprefix' => '',
            'pconnect' => TRUE,
            'db_debug' => TRUE,
            'cache_on' => FALSE,
            'cachedir' => '',
            'char_set' => 'utf8',
            'dbcollat' => 'utf8_general_ci',
            'swap_pre' => '',
            'encrypt' => FALSE,
            'compress' => FALSE,
            'stricton' => FALSE,
            'failover' => array(),
            'save_queries' => TRUE
        );
        $this->db = $this->load->database($dynamicDB, TRUE);
    }

    function doUpdate($dbName, $tablename, $transaction, $where = array())
    {
        $this->connectDB($dbName);
        $this->db->where($where)->update($tablename, $transaction);
        if ($this->db->affected_rows() > 0) return true;
        return false;
    }
}
