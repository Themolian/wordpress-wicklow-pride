<?php
add_filter('acf_osm_marker_html', function($html) {
    return '<div class="parade-map-marker-inner"><img src="' . get_theme_file_uri('dist/images/pride-pin.png') . '"></div>';
});
add_filter('acf_osm_marker_classname', function($html) {
    return 'parade-map-marker';
});