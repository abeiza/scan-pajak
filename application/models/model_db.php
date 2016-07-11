<?php if(!defined("BASEPATH"))exit("No Direct Script Access Allowed");
	class Model_Db extends CI_Model{
		public function __construct(){
			parent::__construct();
		}
		
		public function validation_login($email, $password){
			$query_validasi_login = $this->db->query("select * from tbl_ANPKSP_MsUser where email='".$email."' and password='".$password."'");
			return $query_validasi_login;
		}
		
		public function insert_data($table, $data){
			//$this->load->database('default',FALSE,TRUE);
			return $this->db->insert($table,$data);
		}
		
		function update_data($table, $pk, $id, $data){
			//$this->load->database('default',FALSE,TRUE);
			$this->db->where($pk,$id);
			return $this->db->update($table,$data);
		}
		
		public function list_data($offset)
		{
			$perpage = 10;
			if($offset == 1){
				$first = 1;
				$last  = $perpage;
			}else{
				$first = ($offset - 1) * $perpage + 1;
				$last  = $first + ($perpage -1);
			}
			$sql = 'WITH CTE AS (SELECT  a.*,ROW_NUMBER() OVER (ORDER BY a.KdDokter DESC) as RowNumber FROM dokter a)
				SELECT * FROM CTE WHERE RowNumber BETWEEN '.$first.' AND '.$last.'';
			$query = $this->db->query($sql);
			return $query->result_array();
		}

		public function jumlah_data()
		{
			$this->db->select('count(KdDokter) as total');
			$this->db->from('dokter');
			return $this->db->get()->row()->total;
		}
	}
/*End of file model_db.php*/
/*Location:..models/model_db.php*/