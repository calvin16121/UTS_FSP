<?php
require_once("classDB.php");
class classJenisMenu extends classDB{
    public function __construct() {
        parent::__construct();
    }

    public function getJenisMenu($offset=null, $limit=null) {
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
		$res = $this->getJenisMenu();
		return $res->num_rows;
	}

    public function getJenisMenuKode($kode) {
		$stmt = $this->mysqli->prepare("SELECT * FROM menu_jenis WHERE kode=?");
        $stmt->bind_param('i',$kode);
		$stmt->execute();
		$res = $stmt->get_result();
        $stmt->close();
        $row = $res->fetch_assoc();
        $nama = $row['nama'];
		return $nama;
    }

    public function insertJenisMenu($nama){
        $stmt = $this->mysqli->prepare("INSERT INTO `menu_jenis` (`nama`) VALUES (?);");
        $stmt->bind_param('s',$nama);
        $stmt->execute();
        $last_id = $stmt->insert_id;
        $stmt->close();
        return $last_id;
    }

    public function deleteJenisMenu($kode){
        $stmt = $this->mysqli->prepare("DELETE FROM `menu_jenis` WHERE (`kode` = ?);");
        $stmt->bind_param('i',$kode);
        $stmt->execute();
        $stmt->close();
    }

    public function updateJenisMenu($nama, $kode){
        $stmt = $this->mysqli->prepare("UPDATE `menu_jenis` SET `nama` = ? WHERE (`kode` = ?);");
        $stmt->bind_param('si',$nama,$kode);
        $stmt->execute();
        $stmt->close();

    }
}
?>