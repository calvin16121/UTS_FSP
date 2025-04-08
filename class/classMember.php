<?php

use Vtiful\Kernel\Format;
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

    public function insertMember($iduser, $password, $profil,$nama, $tgllahir, $foto, $status){
        $stmt = $this->mysqli->prepare("INSERT INTO `users` 
        (`iduser`, `password`, `profil`) 
        VALUES (?, ?, ?);");
        $stmt->bind_param('sss', $iduser, $password, $profil);
        $stmt->execute();

        $kode = "m".date("dhis");
        $stmt = $this->mysqli->prepare("INSERT INTO `member` 
        (`kode`,`iduser`, `nama`, `tanggal_lahir`, `url_foto`, `isaktif`) 
        VALUES (?,?, ?, ?, ?, ?);");
        $stmt->bind_param('sssssi', $kode, $iduser, $nama, $tgllahir, $foto, $status);
        $stmt->execute();
        $last_id = $stmt->insert_id;
        $stmt->close();
        return $last_id;
    }

    public function deleteMember($kode){}

    public function updateMember(){}

    public function checkIDUser($iduser){
        $stmt = $this->mysqli->prepare("SELECT * FROM users WHERE iduser=?");
        $stmt->bind_param('s',$iduser);
        $stmt->execute();
        $result = $stmt->get_result();
        return $result->num_rows == 0;
    } 
}
?>