<?php

		$data1 = $this->curl("http://totaldebrid.org/uploaded2/index.php", "secureid=".md5("uerrrs2"), "urllist=".urlencode($url));
		$data2 = $this->curl("http://totaldebrid.org/premiumm/vngfree111111/index.php", "secureid=".md5("tret5r51ez1561ztzzrt1zt163"), "urllist=".urlencode($url));
		$data = array($data1, $data2);
		$data = $data[rand(0, count($data)-1)];
		 if(strpos($data,'kick here to download')){
         $a = explode("<a title='kick here to download' href='", $data);
        }
        elseif(strpos($data,'click here to download')){
        $a = explode("<a title='click here to download' href='", $data);
        }
		$link = explode("'", $a[1]);
        $link = trim($link[0]);
        $fnz = $a[1];
 $filesize = $size_name[0];
 $filename = $size_name[1];
 $fileinfo = explode("<font color=", $fnz); 
 $filename1 = explode('</font>', $fileinfo[1]); 
 $filesize1 = explode('</font>', $fileinfo[2]); 
 $filename = explode("'>", $filename1[0]);
 $filesize = explode("'>", $filesize1[0]);
 $filename = $filename[1];
 $filesize = $filesize[1];
 $filesize = str_replace("(", "", $filesize);
 $filesize = str_replace(")", "", $filesize);

?>