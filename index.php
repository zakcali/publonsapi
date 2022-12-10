<!DOCTYPE html>
<!-- publonsapi V1.1: bu yazılım Dr. Zafer Akçalı tarafından oluşturulmuştur 
programmed by Zafer Akçalı, MD -->
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>WOS numarasından makaleyi bul</title>
</head>

<body>
<?php
// publonsapi
// By Zafer Akçalı, MD
// Zafer Akçalı tarafından programlanmıştır
$wosid=$doi=$ArticleTitle=$dergi=$ISOAbbreviation=$ISSN=$eISSN=$Year=$Volume=$Issue=$StartPage=$EndPage=$yazarlar=$PublicationType=$AbstractText="";
$yazarS=0;
if (isset($_POST['wosid'])) {
$gelenWos=trim($_POST["wosid"]);

if($gelenWos!=""){

if( substr($gelenWos,0,4) !== "WOS:")
	$gelenWos="WOS:".$gelenWos; // sadece rakamları girdiyse başına WOS: ekle 
$preText="https://publons.com/wos-op/api/publication/";
$url = $preText.$gelenWos;
//echo ($url);
$ch = curl_init();
curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
curl_setopt($ch, CURLOPT_FOLLOWLOCATION,1);
curl_setopt($ch, CURLOPT_TIMEOUT, 15);
curl_setopt($ch, CURLOPT_URL, $url);
$data=curl_exec($ch);
curl_close($ch);
$wosBilgi=(json_decode($data, true));
// print_r ($wosBilgi);
if ( !isset ($wosBilgi['detail'])) {// {"detail":"Not found."}
// Makalenin başlığı
$ArticleTitle=$wosBilgi['title'];
// Özet
$AbstractText=$wosBilgi['abstract'];
// doi
$doi= $wosBilgi['doi'];
// WOS numarası
$wosid=$wosBilgi['ut'];
// Dergi ismi
$dergi=$wosBilgi['journal']['name'];
// Dergi kısa ismi
$ISOAbbreviation=$wosBilgi['journal']['abbreviatedTitle'];
// Derginin basıldığı / yayımlandığı yıl
$Year= substr($wosBilgi['datePublished'],0,4);
// yazarlar
$yazarlar="";
// yazar sayısı
$yazarS=0;
foreach( $wosBilgi['authors'] as $name=>$ad) {
	$soyadAd=explode (", ", $ad['name']);
	$soyisim=$soyadAd[0];
	$isim=$soyadAd[1];
	$yazarlar=$yazarlar.$isim." ".$soyisim.", ";
	$yazarS=$yazarS+1;
		}
$yazarlar=substr ($yazarlar,0,-2);
		} // {"detail":"Not found."} hatası gelmedi
	} 
}
?>
<a href="WOSid nerede.png" target="_blank"> WOS numarasına nereden bakılır? </a>
<form method="post" action="">
Web of Science (WOS) makale numarasını giriniz<br/>
<input type="text" name="wosid" id="wosid" value="<?php echo $wosid;?>" >
<input type="submit" value="WOS yayın bilgilerini PHP ile getir">
</form>
<button id="wosGetir" onclick="wosGetir()">WOS yayın bilgilerini JScript ile getir</button>
<button id="wosGoster" onclick="wosGoster()">WOS yayınını göster</button>
<button id="wosAtifGoster" onclick="wosAtifGoster()">WOS yayınının atıflarını göster</button>
<button id="doiGit" onclick="doiGit()">doi ile makaleyi göster</button>
<br/>
WOS: <input type="text" name="wosid" size="19" id="wosid" value="<?php echo $wosid;?>" >  
doi: <input type="text" name="doi" size="55"  id="doi" value="<?php echo $doi;?>"> <br/>
Makalenin başlığı: <input type="text" name="ArticleTitle" size="85"  id="ArticleTitle" value="<?php echo $ArticleTitle;?>"> <br/>
Dergi ismi: <input type="text" name="Title" size="50"  id="Title" value="<?php echo $dergi;?>"> 
Kısa ismi: <input type="text" name="ISOAbbreviation" size="26"  id="ISOAbbreviation" value="<?php echo $ISOAbbreviation;?>"> <br/>
ISSN: <input type="text" name="ISSN" size="8"  id="ISSN" value="<?php echo $ISSN;?>">
eISSN: <input type="text" name="eISSN" size="8"  id="eISSN" value="<?php echo $eISSN;?>"> <br/>
Yıl: <input type="text" name="Year" size="4"  id="Year" value="<?php echo $Year;?>">
Cilt: <input type="text" name="Volume" size="2"  id="Volume" value="<?php echo $Volume;?>">
Sayı: <input type="text" name="Issue" size="2"  id="Issue" value="<?php echo $Issue;?>">
Sayfa/numara: <input type="text" name="StartPage" size="2"  id="StartPage" value="<?php echo $StartPage;?>">
- <input type="text" name="EndPage" size="2"  id="EndPage" value="<?php echo $EndPage;?>">
Yazar sayısı: <input type="text" name="yazarS" size="2"  id="yazarS" value="<?php echo $yazarS;?>"><br/>
Yazarlar: <input type="text" name="yazarlar" size="95"  id="yazarlar" value="<?php echo $yazarlar;?>"><br/>
Yayın türü: <input type="text" name="PublicationType" size="20"  id="PublicationType" value="<?php echo $PublicationType;?>">
<br/>
Özet <br/>
<textarea rows = "20" cols = "90" name = "ozet" id="ozetAlan"><?php echo $AbstractText;?></textarea>  <br/>
Beyan edilecek bilgiler (seçiniz). Makaleyi web of science sitesinde görüp, sağ alt köşeye bakmalısınız.<br/> Bir yayın hem SCIE, hem de SSCI kapsamında; hem SSCI, hem de AHCI kapsamında olabilir. <br/>
Faaliyet-1: SCIE 
<select name="SCIE" size="1" >
<option value = "Hayır"></option>
<option value = "Evet">*</option>
</select> <br/>
Faaliyet-2: SSCI<select name="SSSCI" size="1" >
<option value = "Hayır"></option>
<option value = "Evet">*</option>
</select> 
AHCI<select name="AHCI" size="1" >
<option value = "Hayır"></option>
<option value = "Evet">*</option>
</select> <br/>
Faaliyet-3 ESCI <select name="ESCI" size="1" >
<option value = "Hayır"></option>
<option value = "Evet">*</option>
</select> <br/>
Faaliyet-17: CPCI <select name="CPCI" size="1" >
<option value = "Hayır"></option>
<option value = "Evet">*</option>
</select> <br/>
Faaliyet 8-10 : BKCI <select name="BKCI" size="1" >
<option value = "Hayır"></option>
<option value = "Evet">*</option>
</select> (Eğer WOS'da kayıtlı ise belge koymaya gerek kalmaz)<br/>
En iyi Quartile değeri:<select name="Quartile" size="1" >
<option value = "Yok"></option>
<option value = "Q1">Q1</option>
<option value = "Q2">Q2</option>
<option value = "Q3">Q3</option>
<option value = "Q3">Q4</option>
</select> Quartile değeri için, Journal Citation Reports sitesine bakmalısınız<br/>
<script>
function wosGoster() {
var	w=document.getElementById('wosid').value.replace("WOS:","").replace(" ","");
	urlText = "https://www.webofscience.com/wos/woscc/full-record/"+"WOS:"+w;
	window.open(urlText,"_blank");
}
function wosAtifGoster() {
var	w=document.getElementById('wosid').value.replace("WOS:","").replace(" ","");
	urlText = "https://www.webofscience.com/wos/woscc/citing-summary/"+"WOS:"+w;
	window.open(urlText,"_blank");
}
function doiGit() {
var	w=document.getElementById('doi').value;
	urlText = "https://doi.org/"+w;
	window.open(urlText,"_blank");
}
async function wosGetir() {
var	w=document.getElementById('wosid').value.replace("WOS:","").replace(" ","");
urlText = "https://publons.com/wos-op/api/publication/"+"WOS:"+w;
// https://codetogo.io/how-to-fetch-xml-in-javascript/
fetch(urlText)
  .then(response => response.json())
  .then(data => {
//console.log(data);
if (data['detail']=="Not found.")
	return;
// php ile çağrılmış ve doldurulmuş alanları sil
document.getElementById('wosid').value="";
document.getElementById('doi').value="";
document.getElementById('ArticleTitle').value="";
document.getElementById('Title').value="";
document.getElementById('ISOAbbreviation').value="";
document.getElementById('ISSN').value="";
document.getElementById('eISSN').value="";
document.getElementById('Year').value="";
document.getElementById('Volume').value="";
document.getElementById('Issue').value="";
document.getElementById('StartPage').value="";
document.getElementById('EndPage').value="";
document.getElementById('yazarS').value="";
document.getElementById('yazarlar').value="";
document.getElementById('PublicationType').value="";
document.getElementById('ozetAlan').value="";
// doi
document.getElementById('doi').value=data['doi'];
// WOS id
document.getElementById('wosid').value=data['ut'];
// Makalenin başlığı
document.getElementById('ArticleTitle').value=data['title'];
// Dergi ismi
document.getElementById('Title').value=data['journal']['name'];
// Dergi kısa ismi
document.getElementById('ISOAbbreviation').value=data['journal']['abbreviatedTitle'];
// Derginin basıldığı yıl
document.getElementById('Year').value=data['datePublished'].substring(0,4);
// özet
document.getElementById('ozetAlan').value=data['abstract'];
yazarYaz="";
yazarSay=0;
const yazarlar = data['authors'];
// console.log(yazarlar);
yazarlar.forEach ((element, index, array) => {
	let soyadAd=element.name.split (", ");
	let soyisim=soyadAd[0];
	let isim=soyadAd[1];
	yazarYaz=yazarYaz+isim+" "+soyisim+", ";
	yazarSay=yazarSay+1;
});
// yazar sayısı: sadece Adı ve Soyadı olan gerçek insan isimleri sayıldı, yazar grubu ismi sayılmadı
document.getElementById('yazarS').value=yazarSay;
// yazarların isimleri. metin sonundaki boşluk ve virgül silindi
document.getElementById('yazarlar').value=yazarYaz.slice(0, -2); 
  })
  .catch(console.error);
}
</script>
</body>
</html>