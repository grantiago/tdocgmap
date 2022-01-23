<?php

/**
* @package   TDOC Google Maps Display
* @copyright Copyright (C) 2021. All rights reserved.
* @license   http://www.gnu.org/licenses/gpl-3.0.html
* @version   4.1.0
* 4.1.0 added "print" as an option to make a full width map w=100%, h=700 suitable for A4 portrait - 2nd May 2021
* 4.1.0 added "center", "left", "right" as alignments
* 4.1.1 changed suppresinfowindows to false - 3rd May 2021
**/

/**  
     Many thanks to Gene from the Google Maps Platform Technical Support team 
     // see https://docs.joomla.org/J3.x:Creating_a_Plugin_for_Joomla
     $this->params: the parameters set for this plugin by the administrator - rgtr note: as AN array - got Ok
     $this->_name: the name of the plugin - got Ok
     $this->_type: the group (type) of the plugin - got Ok
     $this->db:  the db object
     $this->app: the application object
**/

/**	 // see https://docs.joomla.org/J3.x:Creating_a_content_plugin
	 context : The context of the content passed to the plugin.
     article : A reference to the article that is being rendered by the view.
     params  : A  reference to an associative array of relevant parameters. 
	      The view determines what it considers to be relevant and passes that information along.
     limitstart : An integer that determines the "page" of the content that is to be generated. 
	      Note that in the context of views that might not generate HTML output, a page is a 
		  reasonably abstract concept that depends on the context.
**/	

// no direct access
defined( '_JEXEC' ) or die;

class plgcontenttdocgmap extends JPlugin {
// ga jan 22
 protected $autoloadLanguage = true;
    function onContentPrepare($context, &$article, &$params, $limitstart) {

		//step zero - kick everything else out
    	$name=$this->_name; 
        //echo "<br>"."Name: ".$name;
		if ($name !== "tdocgmap"){return true;}

        // required to pass one css, and two js scripts.
        // $document = JFactory::getDocument();

		// step one - read settings - apikey, defaults for width, height
		$apikey = $this->params->get('apikey','');
		$height = $this->params->get('height','');
		$width  = $this->params->get('width' ,'');
		$poi  = $this->params->get('poi' ,'');
		$basemap  = $this->params->get('basemap' ,'');
		// echo $basemap;
//echo $poi;
		// if ($poi == 1){
			// echo $poi = 'on'; // show poi
		// }  
		// else {
			 // echo $poi = 'off';
		// }
		// getmystyle($poi);
        //echo "<br>Step One - Default Settings: ".$apikey .", ". $height .", ". $width ; 
		
		// step two locate number of instances of {tdocgmap}
		// https://docs.joomla.org/J1.5:Creating_a_content_plugin
		$content     = $article->text ;
		$occurrences = array();
        // set counter for replacing etc etc
        $counter = 0;

		$tag='tdocgmap';
		$regex = "/\{".$tag."\s+(.*?)\}/is";
		$found = preg_match_all($regex, $content, $occurrences);
        //echo "<br>Step Two - Occurrences: ". sizeof($occurrences) ;

        // step three get data for each map
        if ($found){
            // array to collect maps
            $mydivs = array() ;   // used to replace occurrences in text
            $mydata = array() ;   // used to construct map script before init function
            $mymaps = array() ;   // used to construct map script within init function

            foreach ($occurrences[0] as $value) {
                // create array for each data, create map and add to mymap
                $gotdata=array('','','','') ;   // 4.1.0
                // replace tag and get local data [kml file, height, width]
                $data = $value ;
                $data = str_replace('{tdocgmap ',  '', $data);    // JED Checker warning "Pattern found#17 - PHP: multiple encoded, most probably obfuscated code found"
                $data = str_replace('}',  '', $data);

                // explode data into an array
                $arr = explode(" ", $data);
                foreach ($arr as $phrase) {
                    if (strstr(strtolower($phrase), 'kml=')) {
                        $tpm = explode('=', $phrase);
                        $gotdata[0]=$tpm[1];                    
                    }
                    if (strstr(strtolower($phrase), 'height=')) {
                        $tpm = explode('=', $phrase);
                        $gotdata[1]=$tpm[1]; 
                    }
                    if (strstr(strtolower($phrase), 'width=')) {
                        $tpm = explode('=', $phrase);
                        $gotdata[2]=$tpm[1];                    
                    }
                    // 4.1.0 
                    if ($phrase =='left') {
                        $gotdata[3]="left" ;
                    } elseif ($phrase =='right') {
                        $gotdata[3]="right" ;
                    } elseif ($phrase =='print') {
                        $gotdata[3]="print" ;
                    }

                }   // end for each data   

                // check if height & width were set, if not use defaults, no kml or lat or long - zappo !
                if (empty($gotdata[0])){return true ; }              // kml
                if (empty($gotdata[1])){$gotdata[1] = $height ;}
                if (empty($gotdata[2])){$gotdata[2] = $width ; }

                // three things for each in the body: div with style, map data, and creator for script 
                // create divs // 4.1.0
                $mydivs[$counter] = getmydiv($counter, $gotdata[1], $gotdata[2], $gotdata[3]) ;   // mapno, height, width, ifprint
                // create data  
                $mydata[$counter] = getmydata($counter, $gotdata[0]) ; // kml 

                // create maps  
                $mymaps[$counter] = getmymap($counter) ;                            
				// poi var here
				$mystyle[$counter] = getmystyle($counter, $poi) ;  
                $counter = $counter + 1 ;             
            }  // end for each occurrence
// var_dump($poi);
//getmystyle($poi);
// print_r($mydata); // Array ( [0] => var map0;var src0='https://lrio.com/kml/hunt.kml'; [1] => var map1;var src1='https://lrio.com/kml/hunt.kml'; )
			// Replace tag occurences with divs. 
            for ($i = 0; $i < count($mydivs); $i++) {
                $article->text = preg_replace($regex, $mydivs[$i], $article->text, 1);
            } // end for replace tags with maps

            // assemble maps script
            $mapscript  ='' ;
            // add header
            $mapscript .='<script>' ;
            $mapscript .='function initMaps() { ';
            //parse mymaps
			echo $mapscript . '};</script>';   
            // data stuff
            for ($i = 0; $i < count($mydata); $i++) {  
                // add data
                $mapscript .= $mydata[$i];         
            }   // end each data
            // map stuff
            for ($i = 0; $i < count($mymaps); $i++) {  
                // add map
                $mapscript .= $mymaps[$i];         
            }   // end each map
			for ($i = 0; $i < count($mystyle); $i++) {  
                // add map
                $mapscript .= $mystyle[$i];         
            }   // end each map
            // script end
            $mapscript .='}</script>' ;

// there are two options - script in head, or script in body - one or the other !
// script in text - FAILS in J3 and in J4
//            $document = JFactory::getDocument();
//            $document->addscript($mapscript); 
// script in body
            $article->text .= $mapscript ;

            // Get authority
            $myauth = getmyauth($apikey) ;
            // see https://joomla.stackexchange.com/questions/4035/add-javascript-with-doc-addscript-with-async-true 
            // $document->addscript($myauth, 'text/javascript', false, true); // script, type, defer, async.
            $article->text .= $myauth ;
        } // end found
  	
    return true;	
    }  // end oncontentprepare

} // end plgcontenttdocgmap class

// these are the functions that are called above.
// below each is the code from https://codebeautify.org/html-to-php-converter 

// set up the divs to hold the maps, used in the tag replacement in the article. 
function getmydiv($i, $h, $w, $xx) {
    // 4.1.0   
//    echo "<br>Opt:".$xx.":" ;
    if($xx=="print") {
        $output = '<div id="Map'.$i.'" style="width:100%;     height:700px;                               border: thin solid #333;">'.$i.'</div>' ;     
    } elseif($xx=="left") {
        $output = '<div id="Map'.$i.'" style="width:'.$w.'px; height:'.$h.'px; float:left;  margin: 15px; border: thin solid #333;">'.$i.'</div>' ;
    } elseif($xx=="right") {
        $output = '<div id="Map'.$i.'" style="width:'.$w.'px; height:'.$h.'px; float:right; margin: 15px; border: thin solid #333;">'.$i.'</div>' ;
    } else {
        $output = '<div id="Map'.$i.'" style="width:'.$w.'px; height:'.$h.'px; margin:auto;               border: thin solid #333;">'.$i.'</div>' ;
    }
/**
margin: auto; centers no wrap
float:left; left with wrap on right, etc
**/    
    
return $output ;
}
/**
echo '<div id="Map1" style="width: 500px; height: 500px;">1</div>';
**/ 

// define the var to hold a map, and the kml file to show. goes in the map script before init function. 
function getmydata($i, $kml) {
     $output  = 'var map'.$i.';';
  // echo($output .= 'var src'.$i.'='.$kml.';');
    echo $output .= 'var src'.$i.'='.$kml.';';
     return $output ;
}
function getmystyle($i, $poi) {
     $output  = 'var map'.$i.';';
  echo $output .= 'var vis'.$i.'='.$poi.';';
  // $output .= 'onOff'.$i.'='.$poi.';';
     return $output ;
 }

/**
echo 'var map1;';
echo 'var src1 = 'http://tdocplus.co.uk/lp_routes/lp_snuff_white_east_2.kml';';
**/

// create the stuff to go within the map scipt''s init function. change ' to " for element id, terrain 
function getmymap($i) {
     $output  = 'map'.$i.' = new google.maps.Map(document.getElementById("Map'.$i.'"), {' ;
     $output .= 'center: new google.maps.LatLng(0, 0),';
     $output .= 'zoom: 12,';
     $output .= 'scaleControl: true,';
     $output .= 'vis'.$i.',';
	 
     $output .= 'mapTypeId: "terrain"';
     $output .= '});';
     $output .= '';
     
     $output .= 'var kmlLayer'.$i.' = new google.maps.KmlLayer(src'.$i.', {';
     $output .= 'suppressInfoWindows: false,';
     $output .= 'preserveViewport: false,';
     $output .= 'map: map'.$i.'';
     $output .= '});';
     return $output ;

}
/**
echo 'map1 = new google.maps.Map(document.getElementById('Map1'), {';
echo 'center: new google.maps.LatLng(0, 0),';
echo 'zoom: 12,';
echo 'mapTypeId: 'terrain'';
echo '});';
echo '';
echo 'var kmlLayer1 = new google.maps.KmlLayer(src1, {';
echo 'suppressInfoWindows: true,';
echo 'preserveViewport: false,';
echo 'map: map1';
echo '});';
**/

// script for authorisation and callback to map script 
function getmyauth($apikey) {                
    $output = '<script src="https://maps.googleapis.com/maps/api/js?key='.$apikey.'&callback=initMaps"></script>' ;
    return $output ;
}
//echo 'var onOff = []';

/**
    echo '<script';
    echo 'src="https://maps.googleapis.com/maps/api/js?key=AIzaSyDWjFdr_C6CBFcvUnhO7btjto1DiRxELMo&amp;callback=initMaps">';
    echo '</script>'; 
**/
?> 
