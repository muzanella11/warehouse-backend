<?php

/**
 * @author f1108k
 * @copyright 2015
 *

 */



?>
<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
    class MY_Model extends CI_Model{
        function __construct(){
            parent::__construct();
		}
		
        function checkBotEnem($table_name, $field_name, $key_prefix) {
			$sql    =   "SELECT * FROM ".$table_name." WHERE ".$field_name." LIKE '".$key_prefix."%'";
			$query  =   $this->db->query($sql);
            if($query->num_rows() > 0){
                return $query->result();
            }
        }

        function deleteBotEnem($table_name, $field_name, $key_prefix) {
            $sql    =   "DELETE FROM ".$table_name." WHERE ".$field_name." LIKE '".$key_prefix."%'";
            $this->db->query($sql);
        }
    }
?>
