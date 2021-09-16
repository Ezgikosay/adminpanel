<?php
    require_once("baglan.php");
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ana Sayfa</title>
</head>
<body style="text-align:center">
    <p><a href="giris.php">Yönetim Paneli</a></p>
    <table border="1" width="100%">
        <tr>
            <td width="5%"><b>ID</b></td>
            <td width="15%"><b>Resim</b></td>
            <td width="20%"><b>Kategori</b></td>
            <td width="20%"><b>Haber</b></td>
            <td width="40%"><b>Özet</b></td>
        </tr>
        <?php
            $sorgu = mysqli_query($baglan,"select * from haber where (durum='aktif') order by id desc");
            $habersay = mysqli_num_rows($sorgu);
            while ($satir = mysqli_fetch_object($sorgu)) {
                $ozet = mb_substr(strip_tags($satir->icerik),0,100);
                $sorgux = mysqli_query($baglan,"select * from kategori where (id='$satir->kat_id')");
                $satirx = mysqli_fetch_object($sorgux);
                echo "<tr>
                <td>$satir->id</td>
                <td><img src='$satir->resim' height='30' width='45'></td>
                <td>$satirx->baslik</td>
                <td>$satir->baslik</td>
                <td>$ozet...</td>
                </tr>";
            }
        ?>
    </table>
    <p>Toplam <?php echo $habersay; ?> Haber Listeleniyor...</p>
</body>
</html>