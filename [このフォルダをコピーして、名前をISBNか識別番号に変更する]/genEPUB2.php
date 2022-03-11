<?php
//copy and rename temp.epub
copy("temp.epub", $_POST['ISBN'].".epub");


//ここからimage
mkdir("./image", 0777); 
	//手動で入れる、カバーはimg-0000.jpg,内容はimg-0001 (page 1) から


//ここからnavigation-documents.xhtml
$fp = fopen("navigation-documents.xhtml", "w");
$navi_temp = "<?xml version=\"1.0\" encoding=\"UTF-8\"?>
<!DOCTYPE html>
<html
 xmlns=\"http://www.w3.org/1999/xhtml\"
 xmlns:epub=\"http://www.idpf.org/2007/ops\"
 xml:lang=\"ja\"
>
<head>
<meta charset=\"UTF-8\"/>
<title>Navigation</title>
</head>
<body>

<nav epub:type=\"toc\" id=\"toc\">
	<h1>Navigation</h1>
	<ol>
{{section 1 HERE}}
	</ol>
</nav>

<nav epub:type=\"landmarks\" id=\"guide\">
	<h1>Guide</h1>
	<ol>
{{section 2 HERE}}
	</ol>
</nav>

</body>
</html>";
fwrite($fp, $navi_temp);
fclose($fp);
	//section 1, 2は手動で作成　（目次作成xlsxで）


//ここからstyle.css
mkdir("./style", 0777);

$style = "html,
body {
 margin: 0;
 padding: 0;
 font-size: 0;
}
svg {
 margin: 0;
 padding: 0;
}
.fullpage {
 width: 100%;
 height: 100%; 
}";

$fp = fopen("./style/style.css", "w");
fwrite($fp, $style);
fclose($fp);


//ここからstandard_opf
$standard_opf = "<?xml version=\"1.0\" encoding=\"UTF-8\"?>
<package
 xmlns=\"http://www.idpf.org/2007/opf\"
 version=\"3.0\"
 xml:lang=\"ja\"
 unique-identifier=\"unique-id\"
 prefix=\"rendition: http://www.idpf.org/vocab/rendition/#
	ebpaj: http://www.ebpaj.jp/
	fixed-layout-jp: http://www.digital-comic.jp/\"
>

<metadata xmlns:dc=\"http://purl.org/dc/elements/1.1/\">

<!-- 作品名 -->
<dc:title id=\"title\">".$_POST["title"]."</dc:title>
<meta refines=\"#title\" property=\"file-as\">".$_POST["title_kana"]."</meta>

<!-- 著者名 -->";

for($i=1;$i<=$_POST["creatorNo"];$i++){
	$standard_opf .= "\n<dc:creator id=\"creator0".$i."\">".$_POST["creator0".$i]."</dc:creator>\n<meta refines=\"#creator0".$i."\" property=\"role\" scheme=\"marc:relators\">aut</meta>\n<meta refines=\"#creator0".$i."\" property=\"file-as\">".$_POST["creator0".$i."_kana"]."</meta>\n<meta refines=\"#creator0".$i."\" property=\"display-seq\">".$i."</meta>";
}


$standard_opf .= "\n
<!-- 出版社名 -->
<dc:publisher id=\"publisher\">".$_POST["publisher"]."</dc:publisher>
<meta refines=\"#publisher\" property=\"file-as\">".$_POST["publisher_kana"]."</meta>

<!-- 言語 -->
<dc:language>".$_POST["lang"]."</dc:language>

<!-- ファイルid -->
<dc:identifier id=\"unique-id\">".$_POST["ISBN"]."</dc:identifier>

<!-- 更新日 -->
<meta property=\"dcterms:modified\">".date("Y-m-d")."T00:00:00Z</meta>

<!-- etc. -->
<meta property=\"fixed-layout-jp:viewport\">width=".$_POST["width"].", height=".$_POST["height"]."</meta>

<!-- IMGタグ　20201225　-->
<meta property=\"ebpaj:guide-version\">1.0.1</meta>
<meta property=\"rendition:layout\">pre-paginated</meta>
<meta property=\"rendition:orientation\">auto</meta>
<meta property=\"rendition:spread\">landscape</meta>

<!-- Kindle -->
<meta name=\"fixed-layout\" content=\"true\"/>
<meta name=\"cover\" content=\"cover\" />
<meta name=\"original-resolution\" content=\"".$_POST["width"]."x".$_POST["height"]."\"/>
<meta name=\"orientation-lock\" content=\"none\"/>
<!--
<meta name=\"book-type\" content=\"comic\"/>
<meta name=\"RegionMagnification\" content=\"false\"/>
-->

</metadata>

<manifest>

<!-- navigation -->
<item media-type=\"application/xhtml+xml\" id=\"toc\" href=\"navigation-documents.xhtml\" properties=\"nav\"/>

<!-- style -->
<item media-type=\"text/css\" id=\"style\" href=\"style/style.css\"/>

<!-- image -->
<item id=\"cover\" href=\"image/img-0000.jpg\" media-type=\"image/jpeg\" properties=\"cover-image\"/>\n";

for($i=1;$i<=$_POST["page"];$i++){
	$standard_opf .= "<item id=\"img-".sprintf('%04d', $i)."\" href=\"image/img-".sprintf('%04d', $i).".jpg\" media-type=\"image/jpeg\"/>\n";
}

$standard_opf .= "\n<!-- xhtml -->\n";

for($i=1;$i<=$_POST["page"];$i++){
	$standard_opf .= "<item id=\"page-".sprintf('%04d', $i)."\" href=\"xhtml/page-".sprintf('%04d', $i).".xhtml\" media-type=\"application/xhtml+xml\" />\n";
}

$standard_opf .= "</manifest>\n\n";

$leftright = array("error", "error");
if($_POST["direction"]=="ltr"){
	$leftright = array("left", "right");
}else if($_POST["direction"]=="rtl"){
	$leftright = array("right", "left");
}

$standard_opf .= "<spine page-progression-direction=\"".$_POST["direction"]."\">\n";

for($i=1;$i<=$_POST["page"];$i++){
	$standard_opf .= "<itemref idref=\"page-".sprintf('%04d', $i)."\" linear=\"yes\" properties=\"page-spread-".$leftright[$i%2]."\"/>\n";
}

$standard_opf .= "</spine>

</package>";

$fp = fopen("standard.opf", "w");
fwrite($fp, $standard_opf);
fclose($fp);


//ここからxhtml
$pagexhtml_class = "error";
if($_POST["direction"]=="ltr"){
	$pagexhtml_class = "hltr";
}else if($_POST["direction"]=="rtl"){
	$pagexhtml_class = "vrtl";
}

mkdir("./xhtml", 0777);

for($i=1;$i<=$_POST["page"];$i++){
	$pagexhtml = "<?xml version=\"1.0\" encoding=\"UTF-8\"?>
<!DOCTYPE html>
<html
 xmlns=\"http://www.w3.org/1999/xhtml\"
 xmlns:epub=\"http://www.idpf.org/2007/ops\"
 xml:lang=\"ja\"
 class=\"".$pagexhtml_class."\">
<head>
<link rel=\"stylesheet\" type=\"text/css\" href=\"../style/style.css\"/>
<title>page-".sprintf('%04d', $i)."</title>
<meta charset=\"UTF-8\"/>
<meta name=\"viewport\" content=\"width=".$_POST["width"].", height=".$_POST["height"]."\"/>
</head>
<body>
<div class=\"bgpg\">
<img class=\"fullpage\" src=\"../image/img-".sprintf('%04d', $i).".jpg\"/>
</div>
</body>
</html>";

	$fp = fopen("./xhtml/page-".sprintf('%04d', $i).".xhtml", "w");
	fwrite($fp, $pagexhtml);
	fclose($fp);
}

?>

<div id="start" style="top:0;left:0;position:absolute;background-color:#999999;z-index:99999;width:100vw;height:100vh;text-align:center;vertical-align:middle;"><img src="./loading.svg" alt="" style="margin:40vh 0 0 0;"></div>
<script type="text/javascript">
window.onload = setTimeout(function(){
    document.querySelector('#start').remove();
	document.location.href = "./menu.html";
}, 15000);
</script>