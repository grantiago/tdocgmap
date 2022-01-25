# tDocGmap
This is a fork of the tdocgmap plugin from the JED. Version 4.1.3

Original Project page: [tdocplus](http://tdocplus.co.uk/0a_Empty400/kml-map)

A one script plugin for rendering KML in Joomla content using the google maps API. I was working on, trying to develop one of my own when I stumbled upon this. Trust me I didn't find it with the abysmal JED search engine. 

In addition to rendering a kml based map, I wanted to toggle off, or on, the google POIs. Well at first I simply wanted to toggle them _off._ One for asthetics, google maps is a clutter of POIs. Two, imagine I have a competetor across the street. I don't want to be featuring my competitors on my website. 

I have a solid grasp of the google maps API. I opened the tdocgmaps.php file to add a styler and shut down the POIs. A couple pastes of code and done. But wait a minute. Once I opened the file I discovered the original developer had commented and explained every function and loop. 

It took me most of a day to work through the changes. I'm a one-line cut-and-paste hack. I struggled for a while to get the php params wrapped in quotes once they were transfered into the gmaps javascript. Stackoverflow and json_encode() did the trick. 

I am offering these mods to the original developer and you. I hope my efforts and added features are appreciated. I feel as if I am trespassing a bit. Oh well.

_usage_ `{tdocgmap width=100 height=700 kml='https://adomain.com/kml/my.kml'}`

Specific settings are within the tag:
- Position [left, right, none]
- Height -- now in % for responsive.
- Width
- URL to the KML file [required] within single 'quotes'.
- All settings are in the form key=value pairs separated by a space.

Features
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

additions:
- usage on the plugin admin page. I hate searching around for this and stabbing in the dark. 
- Added en-GB language files.
- Improved the formating of text strings on admin page. 
- Translated to Spanish es-ES -- for Chiris. 
- Added POI and basemap radio fields. 
- Removed the google maps api key in the leftover debugging. 

[Get a free google maps api key.](https://developers.google.com/maps/documentation/maps-static/get-api-key)
# TODO

This is the markdown todo file for project tdoc.

## Content

Tasks related to new content.

- [x] Add version# in the xml to render in the plugin desc. Overlooked by most Joomla Developers. 
  - [ ] add an optional marker and info window?
  - [ ] add a default kml file so the plugin doesn't fail if omitted in the call.
  - [ ] the Spanish translations lag a few lines. 
  - [ ] Some css needs to be added to make it responsive
  - [ ] Probably gonna need top and bottom margin toggles at some point. 
## Release
- [ ] contact the original devloper.
- [x] Init project repository
      https://github.com/grantiago/tdocgmap
- [x] Publish project on GitHub @grantiago
