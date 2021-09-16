<?php 
require_once ("baglan.php");
$kullaniciadi = $_POST["kullaniciadi"];
$sifre = $_POST["sifre"];
$eposta = $_POST["eposta"];


if ($kullaniciadi=="" ||$sifre=="" ||$eposta==""){
    echo "<script> alert('Alanları Boş Geçmeyiniz!'); window.location.href='uyeol.php' </script>";


}
else {
    $sorgu = $baglan->prepare("insert into uyeler set id=? , kullaniciadi=? , sifre=?, eposta=?");
    $ekle = $sorgu->execute (array(NULL,"$kullaniciadi" , "$sifre","$eposta"));
    if ($ekle) {
        //Eklenen Kayıt Numarasını Verir.
        echo "<script>
        alert('$kullaniciadi İle Kayıt Altına Alındı.');
        window.location.href='uye.php';
        </script>";
    } else {
        echo "<script>
        alert('Hata Oluştu.');
        window.location.href='uyeol.php';
        </script>";
    }
}

?>