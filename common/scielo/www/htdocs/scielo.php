<?php	    
	require_once("classScielo.php");
	require_once('sso/header.php');

	// Create new Scielo object
	$host = $HTTP_HOST;    
    $scielo = new Scielo ($host);
	$CACHE_STATUS = $scielo->_def->getKeyValue("CACHE_STATUS");
	$MAX_DAYS = $scielo->_def->getKeyValue("MAX_DAYS");
	$MAX_SIZE = $scielo->_def->getKeyValue("MAX_SIZE");
	
	if (($CACHE_STATUS == 'on') && ($MAX_DAYS>0)){
		$filenamePage = $scielo->GetPageFile();
	}

	$filenamePage = "";
	$pageContent = "";
	$GRAVA = false;

	if ($filenamePage){
        if (file_exists($filenamePage)){
			echo "<!-- EXISTE $filenamePage -->";

			$lastChange = date("F j Y g:i:s", filemtime($filenamePage));
			$diff = dateDiff($interval="d",$lastChange,date("F j Y g:i:s"));
			if ($diff<=$MAX_DAYS){
				echo "<!-- dentro do prazo $time -->";
				$fp = fopen($filenamePage, "r");
                if ($fp){
                	$pageContent = fread($fp, filesize($filenamePage));
					$pageContent .= "\n".'<!-- Cache File name: '.$filenamePage.'-->';
                	fclose($fp);
				}
			} else {
				echo "<!-- fora do prazo $time -->";
                $GRAVA = true;
            }
        } else {
			/*
				tratar quando n�o existe espa�o:
				apagar
				gravar novo
				MAX_SIZE
			*/
		}
	}
	if (!$pageContent){

    // Generate wxis url and set xml url
        $xml = $scielo->GenerateXmlUrl();
        $scielo->SetXMLUrl ($xml);

    // Generate xsl url and set xsl url
        $xsl = $scielo->GenerateXslUrl();
        $scielo->SetXSLUrl ($xsl);
		//$scielo->GetLoginStatus();
        $pageContent = $scielo->getPage();

		$pageContent .= "\n".'<!-- REQUEST URI: '.$REQUEST_URI.'-->';

        if ($GRAVA && $filenamePage){
			if (!file_exists($filenamePage)){
				include ("mkdir.php");
				$path = substr($filenamePage, 0, strrpos($filenamePage, '/'));
	           	createDirStructure($path, $s_root, $s_err_msg, $i_err_code, 0777);
			}
           	$fp = fopen($filenamePage, "rw");
           	if ($fp){
           		fwrite($fp, $pageContent);
				echo "<!-- gravou -->";
			} else {
				echo "<!-- nao gravou $filenamePage -->";
			}
	        fclose($fp);
        	chmod($filenamePage, 0774);
        }
	}
	if(isset($_GET['download']))
	{
		require_once(dirname(__FILE__)."/export.php");
		exit;
	}	

	echo $pageContent;
	

 function showDivulgacao($lang, $script){
		 $pageContent = "";
		 $filenamePage = getDivulgacao($lang, $script);
                 $fp = fopen($filenamePage, "r");
                 if ($fp){
                     $pageContent = fread($fp, 9999);
	                 fclose($fp);
                 }
         return $pageContent;
 }
 function getDivulgacao($lang, $script){
		$html = "";
		 if (file_exists("divulgacao.txt")){
                 $divulgacao = parse_ini_file("divulgacao.txt",true);
                 $html = $divulgacao[$script][$lang];
         }
         return $html;
 }

function dir_size($dir, &$older, &$older_accessed)
{
	$handle = opendir($dir);
   	$mas = 0;
   	while ($file = readdir($handle)) {
    	if ($file != '..' && $file != '.'){
	   		if (is_dir($dir.'/'.$file)){
				$mas += dir_size($dir.'/'.$file, $older, $older_accessed);
			} else {
				$mas += filesize($dir.'/'.$file);
				if ($older!=''){
					if (fileatime($dir.'/'.$file)<fileatime($older)){
						$older = $dir.'/'.$file;
					}
				} else {
					$older = $dir.'/'.$file;
				}
				if ($older_accessed!=''){
					if (fileatime($dir.'/'.$file)<fileatime($older_accessed)){
						$older_accessed = $dir.'/'.$file;
					}
				} else {
					$older_accessed = $dir.'/'.$file;
				}
			}
	   	}
   }
   return $mas; // bytes
}
      function dateDiff($interval="d",$dateTimeBegin,$dateTimeEnd) {
         //Parse about any English textual datetime
         //$dateTimeBegin, $dateTimeEnd

         $dateTimeBegin=strtotime($dateTimeBegin);
         if($dateTimeBegin === -1) {
           return("..begin date Invalid");
         }

         $dateTimeEnd=strtotime($dateTimeEnd);
         if($dateTimeEnd === -1) {
           return("..end date Invalid");
         }

         $dif=$dateTimeEnd - $dateTimeBegin;

         switch($interval) {
           case "s"://seconds
               return($dif);

           case "n"://minutes
               return(floor($dif/60)); //60s=1m

           case "h"://hours
               return(floor($dif/3600)); //3600s=1h

           case "d"://days
               return(floor($dif/86400)); //86400s=1d

           case "ww"://Week
               return(floor($dif/604800)); //604800s=1week=1semana

           case "m": //similar result "m" dateDiff Microsoft
               $monthBegin=(date("Y",$dateTimeBegin)*12)+
                 date("n",$dateTimeBegin);
               $monthEnd=(date("Y",$dateTimeEnd)*12)+
                 date("n",$dateTimeEnd);
               $monthDiff=$monthEnd-$monthBegin;
               return($monthDiff);

           case "yyyy": //similar result "yyyy" dateDiff Microsoft
               return(date("Y",$dateTimeEnd) - date("Y",$dateTimeBegin));

           default:
               return(floor($dif/86400)); //86400s=1d
         }

       }

function wxis_exe ( $url )
{
	global $wxisServer;
	global $scielo;

	$useCache = $scielo->_def->getKeyValue("ENABLED_CACHE");
        $restrito = false;

        if($_SERVER['SCRIPT_NAME']=='/scielolog.php'){
                $chave = $chaveNula;
	        $restrito = true;
        }

	if(($useCache == '1') && (!$restrito)){
		require_once('cache.php');

		if(strpos($_SERVER['REQUEST_URI'],'deletefromcache')){
			$key = substr($_SERVER['REQUEST_URI'],strpos($_SERVER['REQUEST_URI'],'deletefromcache')+16,40);
			echo 'apagando chave '.$key.'XML resultado :'.deleteFromCache($key.'XML');
			echo '<hr>';
			echo 'apagando chave '.$key.'HTML resultado :'.deleteFromCache($key.'HTML');
			die();
		}

                if(strpos($_SERVER['REQUEST_URI'],'cachestats')){
			echo getStatsFromCache($_GET['type'], $_GET['slabs'], 10);
                        die();
                }

		$result = "";
		$chave = sha1($_SERVER['REQUEST_URI']).'XML';
		$chaveNula = '42099b4af021e53fd8fd4e056c2568d7c2e3ffa8XML';
		$result = false;
		//a chave pode ver como XML por exemplo na home, quanto n�o h� parametros na
		//URL, para evitar problemas, n�o colocamos essa chave em cache posis n�o podemos
		//prever quando essa situa��o poder� ocorrer novamente
		if($chave != $chaveNula){
			//pesquisa no cache a chave
			$result = getFromCache($chave);

			if($result == false){
				//se n�o achou, transforma, coloca no cache e retorna
				$result = wxis_exe_($url);
				addToCache($chave,$result);
			}
		}else{
			//se chave == XML ent�o retorna o XML, sem passar pelo cache
			$result = wxis_exe_($url);
		}
	}else{
		//se cache desligado ent�o retorna a transforma��o, sem passar pelo cache
		$result = wxis_exe_($url);
	}
	
	return $result;
}





//wxis-line-command
function wxis_exe_ ( $url )
{
	// Criar um novo Objeto Scielo
	$host = $HTTP_HOST;    
    $scielo = new Scielo ($host);
	/************************************************************************************	
	*	Pegamos o path do htdocs, isso � importante porque deixamos mais configuravel	*
	*	os diferentes scielos n�o precisando mexer na scielo.php, somente no scielo.def	*
	************************************************************************************/
	$PATH_HTDOCS = $scielo->_def->getKeyValue("PATH_HTDOCS");

	$request = "/home/scielo/www/cgi-bin/wxis.exe " ;
	$param = substr($url, strpos($url, "?")+1);
	$param = str_replace("&", " ", $param);
	$request = $request.$param." PATH_TRANSLATED=".$PATH_HTDOCS;

	if (strpos($url,'debug=')==0){
		$r = strstr(shell_exec($request), '<');
	}else{
		$r = $url;
	}
	return $r;
}

function wxis_exe_httpd ( $url )
{

	global $wxisServer;
	global $scielo;
 	if (strpos($url,'debug=')==false && strpos($url, 'script=sci_verify')==false){

		$fp = fopen($url,"rb");

		$conteudo = "";
		do {
			$data = fread($fp, 8192);
			if (strlen($data) == 0) {
				break;
			}
			$conteudo .= $data;
		} while(true);
		fclose ($fp);

		$url = $conteudo;

	}
    return $url;
}

?>
