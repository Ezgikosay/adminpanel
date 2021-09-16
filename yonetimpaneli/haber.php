<?php
    session_start();
    require_once("baglan.php");
    if ($_COOKIE["giris"]<>"var" || intval($_SESSION["kontrol"])<=0 || $_SESSION["kullanici"]=="") {
        @header("Location:cikis.php");
        die();
    }
    $sorgu = mysqli_query($baglan,"select yetki from yonetici where (id='$_SESSION[kontrol]' && kullanici ='$_SESSION[kullanici]')");
    $satir = mysqli_fetch_object($sorgu);
    $yetki = $satir->yetki;
    if (mysqli_num_rows($sorgu)<=0) {
        @header("Location:cikis.php");
        die();
    }

    if ($yetki<>"admin" && $yetki<>"editor") {
        @header("Location:anasayfa.php");
        die("Yetkisiz Giriş!");
    }

    if (isset($_GET["ord"])) {
        $sirala = explode(",",@$_GET["ord"]);
    } else {
        $sirala = explode(",","id,asc");
    }
    if ($sirala[1]=="asc") {$yeniyon = "desc";} else {$yeniyon = "asc";}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Haberler</title>
</head>
<body style="text-align:center">
    <p><a href="anasayfa.php">Ana Sayfa</a> | <a href="haber.php">Haberler</a> | <a href="kategori.php">Kategoriler</a> | <a href="yonetici.php">Yöneticiler</a> | <a href="cikis.php" onclick="if (!confirm('Çıkış Yapmak İstediğinize Emin misiniz?')) {return false;}">Çıkış</a></p>

    <hr>

    <p align="right"><button onclick="window.top.location='hbrislem.php?islem=yeni';">Yeni Haber Ekle</button></p>

    <table border="1" width="100%">
        <tr>
            <td width="5%"><b><a href="?ord=id,<?php echo $yeniyon; ?>">ID</a></b></td>
            <td width="15%"><b>Resim</b></td>
            <td width="20%"><b><a href="?ord=kat_id,<?php echo $yeniyon; ?>">Kategori</a></b></td>
            <td width="20%"><b><a href="?ord=baslik,<?php echo $yeniyon; ?>">Haber</a></b></td>
            <td width="20%"><b><a href="?ord=durum,<?php echo $yeniyon; ?>">Durum</a></b></td>
            <td width="20%"><b>İşlem</b></td>
        </tr>
        <?php
            $sorgu = mysqli_query($baglan,"select * from haber order by $sirala[0] $sirala[1]");
            $habersay = mysqli_num_rows($sorgu);
            while ($satir = mysqli_fetch_object($sorgu)) {
                $sorgux = mysqli_query($baglan,"select * from kategori where (id='$satir->kat_id')");
                $satirx = mysqli_fetch_object($sorgux);
                echo "<tr>
                <td>$satir->id</td>
                <td><img src='$satir->resim' height='30' width='45'></td>
                <td>$satirx->baslik</td>
                <td>$satir->baslik</td>
                <td>".ucwords($satir->durum)."</td>
                <td>";
                if ($yetki=="editor") {
                    echo "<a href='hbrislem.php?islem=duzenle&id=$satir->id'>Düzenle</a>";
                }

                if ($yetki=="admin") {
                    echo "<a href='hbrislem.php?islem=sil&id=$satir->id' onclick='if (!confirm(\"Silmek İstediğinize Emin misiniz?\")) {return false;}'>Sil</a>";
                }
                echo "</td>
                </tr>";
            }
        ?>
    </table>
    <p>Toplam <?php echo $habersay; ?> Haber Listeleniyor...</p>
</body>
</html>