<?php
require_once("baglan.php");
session_start();
if (empty($_POST["kullaniciadi"]) || empty($_POST["sifre"])) 
    {
    echo "Lütfen Tüm Bilgileri Doldurunuz";
    }
    
else { 
    $sorgu = "Select * from uyeler where kullaniciadi = :kullaniciadi  and sifre = :sifre ";
    $durum = $baglan -> prepare($sorgu);
    $durum -> execute(
        array (
            'kullaniciadi' => $_POST["kullaniciadi"],
            'sifre' => $_POST["sifre"],
        )
        );
        $say = $durum -> rowCount();
        if ($say > 0){
            $_SESSION ["kullaniciadi"]= $_POST ["kullaniciadi"];
            header("Location:panel.php");
        }
        else {
            echo "Hatalı Bilgi";
        }

    }

?>