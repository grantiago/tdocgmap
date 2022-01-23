# tdocgmap
This is a fork of the tdocgmap plugin from the JED
A well documented and simple plugin. I was working on to developing one of my own when I stumbled upon this. 
In addition to rendering a kml based map, I needed to be able to toggle off the google POIs. One asthetically and two, imagine if I have a competetor across the street. I don't want to be featuring him on my website. 

_usage_ `{tdocgmap width=700 height=700 kml='https://adomain.com/kml/my.kml'}`

Specific settings are within the tag:
- Position [left, right, none]
- Height
- Width
- URL to the KML file [required] within single 'quotes'.
- All settings are in the form key=value pairs separated by a space.

- Auto zoom and center the map on the bounds of the kml layer.
- Toggle POIs
- Selectable base map
- Supports multiple maps on one page.
- Supports maps in categories.

The following position options are available when calling the map.
- print - makes the map 100% in width and 700px in height and overrides other settings.
- left - puts the map to the left and wraps text to the right with 15px margin
- right - puts the map to the right and wraps text to the left with 15px margin.
- if none of these options are used, the map is centered with no wrapping of text.

[Get a free google maps api key.](https://developers.google.com/maps/documentation/maps-static/get-api-key)

Project page: [tdocplus](http://tdocplus.co.uk/0a_Empty400/kml-map)


additions:
- Added en-GB language files for ease of formating text strings on admin page. 
- Translated to spanish es-ES -- for Chiris. 
- Added poi and basemap radio buttons . 
- Removed the google maps api key in the leftover debugging. 

