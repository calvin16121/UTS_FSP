<?php
require_once("classDB.php");

class classVoucher extends classDB {
    public function __construct() {
        parent::__construct();
    }

    public function getVoucher($offset = null, $limit = null) {
        $sql = "SELECT v.nama AS vnama, m.nama AS mnama, mj.nama AS mjnama, v.*
                FROM voucher AS v 
                LEFT JOIN menu AS m ON v.kode_menu = m.kode
                LEFT JOIN menu_jenis AS mj ON v.kode_jenis = mj.kode";

        if (!is_null($offset) && !is_null($limit)) $sql .= " LIMIT ?,?";

        $stmt = $this->mysqli->prepare($sql);
        if (!is_null($offset) && !is_null($limit)) 
            $stmt->bind_param('ii', $offset, $limit);
        
        $stmt->execute();
        $res = $stmt->get_result();
        $stmt->close();
        return $res;
    }

    public function getTotalData() {
        $res = $this->getVoucher();
        return $res->num_rows;
    }

    public function getVoucherKode($kode) {
        $sql = "SELECT * FROM voucher WHERE kode=?";
        $stmt = $this->mysqli->prepare($sql);
        $stmt->bind_param('i', $kode);
        $stmt->execute();
        $res = $stmt->get_result();
        $stmt->close();
        return $res;
    }

    public function claimVoucher($user_id, $voucher_id) {
        // Cek apakah voucher tersedia
        $stmt = $this->mysqli->prepare("SELECT kuota_sisa FROM voucher WHERE kode = ?");
        $stmt->bind_param('i', $voucher_id);
        $stmt->execute();
        $result = $stmt->get_result();
        $voucher = $result->fetch_assoc();
        $stmt->close();

        if (!$voucher) {
            return "Voucher tidak ditemukan.";
        }

        if ($voucher['kuota_sisa'] <= 0) {
            return "Kuota voucher sudah habis.";
        }

        // Cek apakah user sudah klaim voucher ini
        $stmt = $this->mysqli->prepare("SELECT id FROM voucher_user WHERE user_id = ? AND voucher_id = ?");
        $stmt->bind_param('ii', $user_id, $voucher_id);
        $stmt->execute();
        $stmt->store_result();
        if ($stmt->num_rows > 0) {
            $stmt->close();
            return "Kamu sudah klaim voucher ini sebelumnya.";
        }
        $stmt->close();

        // Simpan klaim
        $stmt = $this->mysqli->prepare(
            "INSERT INTO voucher_user (user_id, voucher_id, status, created_at) VALUES (?, ?, 'claimed', NOW())"
        );
        $stmt->bind_param('ii', $user_id, $voucher_id);
        $stmt->execute();
        $stmt->close();

        // Kurangi kuota
        $stmt = $this->mysqli->prepare("UPDATE voucher SET kuota_sisa = kuota_sisa - 1 WHERE kode = ?");
        $stmt->bind_param('i', $voucher_id);
        $stmt->execute();
        $stmt->close();

        return "Voucher berhasil diklaim.";
    }

    public function getVoucherUser($user_id) {
        $query = "SELECT v.* FROM voucher_user vu 
                  JOIN voucher v ON vu.voucher_id = v.kode 
                  WHERE vu.user_id = ?";

        $stmt = $this->mysqli->prepare($query);
        $stmt->bind_param("i", $user_id);
        $stmt->execute();
        $result = $stmt->get_result();

        $vouchers = [];
        while ($row = $result->fetch_assoc()) {
            $vouchers[] = $row;
        }

        $stmt->close();
        return $vouchers;
    }

    public function insertVoucher($menu, $jenis, $nama, $start, $end, $kuota, $diskon) {
        $stmt = $this->mysqli->prepare(
            "INSERT INTO voucher 
            (kode_menu, kode_jenis, nama, mulai_berlaku, akhir_berlaku, kuota_max, kuota_sisa, persen_diskon) 
            VALUES (?, ?, ?, ?, ?, ?, ?, ?)"
        );
        $stmt->bind_param('iisssiii', $menu, $jenis, $nama, $start, $end, $kuota, $kuota, $diskon);
        $stmt->execute();
        $last_id = $stmt->insert_id;
        $stmt->close();
        echo $last_id;
        return $last_id;
    }

    public function deleteVoucher($kode) {
        $stmt = $this->mysqli->prepare("DELETE FROM voucher WHERE kode = ?");
        $stmt->bind_param('i', $kode);
        $stmt->execute();
        $stmt->close();
    }

    public function updateVoucher($menu, $jenis, $nama, $start, $end, $kuota, $diskon, $kode) {
        if ($start > $end) {
            echo "end date can't occur before start date";
        } else {
            if ($menu == "") {
                $stmt = $this->mysqli->prepare(
                    "UPDATE voucher SET 
                        kode_menu = NULL,
                        kode_jenis = ?, 
                        nama = ?, 
                        mulai_berlaku = ?, 
                        akhir_berlaku = ?, 
                        kuota_max = ?,
                        kuota_sisa = ?,
                        persen_diskon = ? 
                    WHERE kode = ?"
                );
                $stmt->bind_param('isssiiii', $jenis, $nama, $start, $end, $kuota, $kuota, $diskon, $kode);
            } else if ($jenis == "") {
                $stmt = $this->mysqli->prepare(
                    "UPDATE voucher SET 
                        kode_menu = ?, 
                        kode_jenis = NULL, 
                        nama = ?, 
                        mulai_berlaku = ?, 
                        akhir_berlaku = ?, 
                        kuota_max = ?,
                        kuota_sisa = ?,
                        persen_diskon = ? 
                    WHERE kode = ?"
                );
                $stmt->bind_param('isssiiii', $menu, $nama, $start, $end, $kuota, $kuota, $diskon, $kode);
            } else {
                $stmt = $this->mysqli->prepare(
                    "UPDATE voucher SET 
                        kode_menu = ?,
                        kode_jenis = ?,
                        nama = ?, 
                        mulai_berlaku = ?, 
                        akhir_berlaku = ?, 
                        kuota_max = ?,
                        kuota_sisa = ?,
                        persen_diskon = ? 
                    WHERE kode = ?"
                );
                $stmt->bind_param('iisssiiii', $menu, $jenis, $nama, $start, $end, $kuota, $kuota, $diskon, $kode);
            }
            $stmt->execute();
            $stmt->close();
        }
    }
}
?>
