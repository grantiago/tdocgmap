# tdocgmap
Useful  plugin that embeds kml files on a google map in joomla articles or categories.

_usage_  `{tdocgmap width=700 height=700 kml='https://adomain.com/kml/my.kml'}`

source: [tdocplus](http://tdocplus.co.uk/0a_Empty400/kml-map)

will render multiple maps on one page. 

- Added en-GB language files for ease of formating text strings on admin page. 
- Translated  to spanish -- for Chiris. 
- Added a second tab group of options I would like to intigrage. 
- I can get the off on variable in the initmap script but it is in the wrong order. and has no effect.
- Removed the google maps api key in the leftover debugging. 

additions:
- added poi params in xml
- basemap in the xml

php

    $poi  = $this->params->get('poi' ,'');
    $basemap  = $this->params->get('basemap' ,'');
	
I can add the poi styler value to 
    
    $mydata[$counter] = getmydata($counter, $gotdata[0], $poi) ; // kml
	
    result:
	
    Array ( [0] => var map0;var vis0=off;var src0='https://lrio.com/kml/hunt.kml'; 
    [1] => var map1;var vis1=off;var src1='https://lrio.com/kml/lax_denpasar.kml'; )