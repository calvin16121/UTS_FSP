<?php
require_once("classDB.php");
class classUser extends classDB{
    public function __construct() {
        parent::__construct();
    }

    public function getMemberNonActive($offset=null, $limit=null) {
        $sql = "SELECT * FROM member WHERE isaktif=0";
		if(!is_null($offset) && !is_null($limit)) $sql .= " LIMIT ?,?";

		$stmt = $this->mysqli->prepare($sql);
		if(!is_null($offset) && !is_null($limit)) 
			$stmt->bind_param('ii', $offset, $limit);

		$stmt->execute();
		$res = $stmt->get_result();
        $stmt->close();
		return $res;
    }
    public function getTotalDataMemberNonActive() {
		$res = $this->getMemberNonActive();
		return $res->num_rows;
	}

    public function getMemberActive($offset=null, $limit=null) {
        $sql = "SELECT * FROM member WHERE isaktif=1";
		if(!is_null($offset) && !is_null($limit)) $sql .= " LIMIT ?,?";

		$stmt = $this->mysqli->prepare($sql);
		if(!is_null($offset) && !is_null($limit)) 
			$stmt->bind_param('ii', $offset, $limit);

		$stmt->execute();
		$res = $stmt->get_result();
        $stmt->close();
		return $res;
    }
    public function getTotalDataMemberActive() {
		$res = $this->getMemberActive();
		return (int)$res->num_rows;
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

    public function declineMember($id){
        $stmt = $this->mysqli->prepare("DELETE FROM `member` WHERE (`iduser` = ?);");
        $stmt->bind_param("s",$id);
        $stmt->execute();
        $stmt = $this->mysqli->prepare("DELETE FROM `users` WHERE (`iduser` = ?);");
        $stmt->bind_param("s",$id);
        $stmt->execute();
        $stmt->close();
    }
    public function acceptMember($id){
        $stmt = $this->mysqli->prepare("UPDATE `member` SET `isaktif` = '1' WHERE (`iduser` = ?);");
        $stmt->bind_param("s",$id);
        $stmt->execute();
        $stmt->close();
    }

    public function checkIDUser($iduser){
        $stmt = $this->mysqli->prepare("SELECT * FROM users WHERE iduser=?");
        $stmt->bind_param('s',$iduser);
        $stmt->execute();
        $result = $stmt->get_result();
        return $result->num_rows == 0;
    }

    public function login($iduser){
        $stmt = $this->mysqli->prepare(
            "SELECT u.*,m.url_foto,m.isaktif 
            FROM users AS u LEFT JOIN member AS m ON u.iduser=m.iduser 
            WHERE u.iduser=?");
        $stmt->bind_param('s', $iduser);
        $stmt->execute();
        $result = $stmt->get_result()->fetch_assoc();
        return $result;
    }
}
?>