<?php
//ここからsection1
$menu_type = $_POST['menu_type'];
$page = $_POST['page'];
$menu = $_POST['menu'];
$s1 = "";
for($i=1;$i<=count($menu_type);$i++){
	if($menu_type[$i]=="chapter"){
		$s1 .= "		<li><a href=\"xhtml/page-".sprintf('%04d', $page[$i]).".xhtml\">".$menu[$i]."</a>";
		if($i==count($menu_type)||$menu_type[$i+1]!="section"){
			$s1 .= "</li>\n";
		}else if($i!=count($menu_type)&&$menu_type[$i+1]=="section"){
			$s1 .= "\n			<ol>\n";
		}
	}else if($menu_type[$i]=="section"){
		$s1 .= "				<li><a href=\"xhtml/page-".sprintf('%04d', $page[$i]).".xhtml\">".$menu[$i]."</a>";
		$s1 .= "</li>\n";
		if($i==count($menu_type)||$menu_type[$i+1]=="chapter"){
			$s1 .= "			</ol>\n		</li>\n";
		}
	}
}

$file_contents = file_get_contents("./navigation-documents.xhtml");
$file_contents = str_replace("{{section 1 HERE}}", $s1, $file_contents);
//file_put_contents("./navigation-documents.xhtml", $file_contents);

//ここからsection2
$s2="		<li><a epub:type=\"cover\" href=\"xhtml/page-".sprintf('%04d', $_POST['p_tobira']).".xhtml\">本扉</a></li>\n		<li><a epub:type=\"toc\" href=\"xhtml/page-".sprintf('%04d', $_POST['p_mokuji']).".xhtml\">目次</a></li>\n		<li><a epub:type=\"bodymatter\" href=\"xhtml/page-".sprintf('%04d', $_POST['p_honpen']).".xhtml\">本編</a></li>";

//$file_contents = file_get_contents("./navigation-documents.xhtml");
$file_contents = str_replace("{{section 2 HERE}}", $s2, $file_contents);
file_put_contents("./navigation-documents.xhtml", $file_contents);


//fileの場所
mkdir("./item", 0777);
rename("./image", "./item/image"); 
rename("./style", "./item/style"); 
rename("./xhtml", "./item/xhtml"); 
rename("navigation-documents.xhtml", "./item/navigation-documents.xhtml"); 
rename("standard.opf", "./item/standard.opf"); 
?>

<div id="start" style="top:0;left:0;position:absolute;background-color:#999999;z-index:99999;width:100vw;height:100vh;text-align:center;vertical-align:middle;"><img src="./loading.svg" alt="" style="margin:40vh 0 0 0;"></div>
Finished. Please close the window. <br>(DON'T REFRESH this page)<br><br>Remember to put your image file (img-xxxx.jpg) in the image folder <br>before you run <b>item2epub.bat</b>.<br>
<script type="text/javascript">
window.onload = setTimeout(function(){
    document.querySelector('#start').remove();
	document.location.href = "./finish.html";
}, 15000);
</script>
