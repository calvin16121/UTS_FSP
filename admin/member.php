<?php
session_start();
require_once("../class/classUser.php");
$user = new classUser();

$message = "";
$show_active = isset($_GET['show']) && $_GET['show'] == 'active';

if(isset($_GET['kode']) && isset($_GET['aksi'])){
    $id = $_GET['kode'];
    if($_GET['aksi']=='terima'){
        $user->acceptMember($id);
    } else {
        $user->declineMember($id);
    }
    header("Location: member.php?show=".($show_active ? 'active' : 'inactive'));
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Koffee StartBug</title>
    <link rel="stylesheet" href="../index.css">
    <style>
        .tab { padding: 10px; cursor: pointer; }
        .active-tab { background-color: #ddd; font-weight: bold; }
        .member-table { display: none; }
        .show { display: block; }
    </style>
</head>
<body>
    <a href="index.php">admin page</a>
    
    <div style="margin: 20px 0;">
        <button class="tab <?= !$show_active ? 'active-tab' : '' ?>" 
                onclick="window.location.href='member.php'">
            Pending Members
        </button>
        <button class="tab <?= $show_active ? 'active-tab' : '' ?>" 
                onclick="window.location.href='member.php?show=active'">
            Active Members
        </button>
    </div>

    <?php if(!$show_active): ?>
    <h2>Member yang belum aktif:</h2>
    <?php
    $jmlh = $user->getTotalDataMemberNonActive();
    $limit = 5;
    $page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
    $max_page = ceil($jmlh/$limit);
    $offset = ($page-1)*$limit;
    $res = $user->getMemberNonActive($offset, $limit);
    ?>
    <table>
        <tr>
            <th>Username</th>
            <th>Nama</th>
            <th>Tanggal Lahir</th>
            <th>Foto</th>
            <th>Terima</th>
            <th>Tolak</th>
        </tr>
        <?php while($row = $res->fetch_assoc()): ?>
        <tr>
            <td><?= htmlspecialchars($row['iduser']) ?></td>
            <td><?= htmlspecialchars($row['nama']) ?></td>
            <td><?= htmlspecialchars($row['tanggal_lahir']) ?></td>
            <td><img src="<?= htmlspecialchars($row["url_foto"]) ?>" style='width:100px;'></td>
            <td><a href="member.php?kode=<?= htmlspecialchars($row['iduser']) ?>&aksi=terima">Terima</a></td>
            <td><a href="member.php?kode=<?= htmlspecialchars($row['iduser']) ?>&aksi=tolak">Tolak</a></td>
        </tr>
        <?php endwhile; ?>
    </table>
    
    <?php else: ?>
    <h2>Member yang sudah aktif:</h2>
    <?php
    $jmlh = $user->getTotalDataMemberActive();
    $limit = 5;
    $page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
    $max_page = ceil($jmlh/$limit);
    $offset = ($page-1)*$limit;
    $res = $user->getMemberActive($offset, $limit);
    ?>
    <table>
        <tr>
            <th>Username</th>
            <th>Nama</th>
            <th>Tanggal Lahir</th>
            <th>Foto</th>
        </tr>
        <?php while($row = $res->fetch_assoc()): ?>
        <tr>
            <td><?= htmlspecialchars($row['iduser']) ?></td>
            <td><?= htmlspecialchars($row['nama']) ?></td>
            <td><?= htmlspecialchars($row['tanggal_lahir']) ?></td>
            <td><img src="<?= htmlspecialchars($row["url_foto"]) ?>" style='width:100px;'></td>
        </tr>
        <?php endwhile; ?>
    </table>
    <?php endif; ?>

    <?php
    
    if($max_page > 1): ?>
    <div>
        <?php if($page != 1): ?>
            <a href="member.php?page=1&show=<?= $show_active ? 'active' : 'inactive' ?>">first</a>
            <a href="member.php?page=<?= $page-1 ?>&show=<?= $show_active ? 'active' : 'inactive' ?>">prev</a>
        <?php endif; ?>
        
        <?php for($i=1; $i<=$max_page; $i++): ?>
            <?php if($i != $page): ?>
                <a href="member.php?page=<?= $i ?>&show=<?= $show_active ? 'active' : 'inactive' ?>"><?= $i ?></a>
            <?php else: ?>
                <span><?= $i ?></span>
            <?php endif; ?>
        <?php endfor; ?>
        
        <?php if($page != $max_page): ?>
            <a href="member.php?page=<?= $page+1 ?>&show=<?= $show_active ? 'active' : 'inactive' ?>">next</a>
            <a href="member.php?page=<?= $max_page ?>&show=<?= $show_active ? 'active' : 'inactive' ?>">last</a>
        <?php endif; ?>
    </div>
    <?php endif; ?>
</body>
</html>