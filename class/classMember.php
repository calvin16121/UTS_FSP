<?php
require_once("classDB.php");
class classMember extends classDB{
    public function __construct() {
        parent::__construct();
    }

    public function getMember($offset=null, $limit=null) {
        $sql = "SELECT * FROM menu_jenis";
		if(!is_null($offset) && !is_null($limit)) $sql .= " LIMIT ?,?";

		$stmt = $this->mysqli->prepare($sql);
		if(!is_null($offset) && !is_null($limit)) 
			$stmt->bind_param('ii', $offset, $limit);

		$stmt->execute();
		$res = $stmt->get_result();
        $stmt->close();
		return $res;
    }
    public function getTotalData() {
		$res = $this->getMember();
		return $res->num_rows;
	}

    public function insertMember(){}

    public function deleteMember($kode){}

    public function updateMember(){}
}
?>