<?php
// Send the headers
header('Content-type: text/xml');
header('Pragma: public');
header('Cache-control: private');
header('Expires: -1');
echo "<?xml version=\"1.0\" encoding=\"utf-8\"?>";

echo '<settings>
<!--   GENERAL SETTINGS   -->
  <canvas_width>916</canvas_width>

  <canvas_height>470</canvas_height>

  <coverflow_center_x_offset>0</coverflow_center_x_offset>

  <coverflow_center_y_offset>0</coverflow_center_y_offset>

  <image_width>'.$width.'</image_width>

  <image_height>'.$height.'</image_height>

  <image_x_space>50</image_x_space>

  <image_y_space>0</image_y_space>

  <image_z_space>40</image_z_space>

  <start_position>center</start_position>

  <coverflow_rotation_angle>0</coverflow_rotation_angle>

  <auto_play>no</auto_play>

  <enable_mouse_wheel>yes</enable_mouse_wheel>

  <slideshow_delay>3.5</slideshow_delay>

  <background_color>transparent</background_color>

  <preloader_path>'.base_url('assets/public/banners/HTML5CanvasCoverFlow/load/graphics/preloader.png').'</preloader_path>
  <preloader_size>32</preloader_size>
  <preloader_color>#FFFFFF</preloader_color>

  <!--   THUMBS SETTINGS   -->
  <border_size>5</border_size>
  <border_color>#FFFFFF</border_color>

  <show_tooltip>yes</show_tooltip>
  <tooltip_font>Verdana</tooltip_font>
  <tooltip_text_size>14</tooltip_text_size>
  <tooltip_text_color>#000000</tooltip_text_color>
  <tooltip_background_color>#FFFFFF</tooltip_background_color>

  <open_link_on_image_click>yes</open_link_on_image_click>
  <window_open_location>_blank</window_open_location>

  <show_reflection>yes</show_reflection>
  <reflection_height>50</reflection_height>
  <reflection_distance>1</reflection_distance>
  <reflection_transparency>0.8</reflection_transparency>

  <!--   SLIDE SHOW PRELOADER SETTINGS   -->
  <slide_show_preloader_x_position>20</slide_show_preloader_x_position>
  <slide_show_preloader_y_position>580</slide_show_preloader_y_position>

  <slide_show_preloader_size>24</slide_show_preloader_size>

  <slide_show_preloader_fill_color>#FFFFFF</slide_show_preloader_fill_color>
  <slide_show_preloader_background_color>#000000</slide_show_preloader_background_color>

  <!--   BUTTONS SETTINGS   -->
  <show_next_button>yes</show_next_button>
  <show_prev_button>yes</show_prev_button>
  <show_play_button>yes</show_play_button>

  <buttons_background_path>'.base_url('assets/public/banners/HTML5CanvasCoverFlow/load/graphics/button_background.png').'</buttons_background_path>
  <buttons_next_icon_path>'.base_url('assets/public/banners/HTML5CanvasCoverFlow/load/graphics/button_next_icon.png').'</buttons_next_icon_path>
  <buttons_prev_icon_path>'.base_url('assets/public/banners/HTML5CanvasCoverFlow/load/graphics/button_prev_icon.png').'</buttons_prev_icon_path>
  <buttons_play_icon_path>'.base_url('assets/public/banners/HTML5CanvasCoverFlow/load/graphics/button_play_icon.png').'</buttons_play_icon_path>
  <buttons_pause_icon_path>'.base_url('assets/public/banners/HTML5CanvasCoverFlow/load/graphics/button_pause_icon.png').'</buttons_pause_icon_path>

  <next_button_x_position>770</next_button_x_position>
  <next_button_y_position>570</next_button_y_position>
  <prev_button_x_position>710</prev_button_x_position>
  <prev_button_y_position>570</prev_button_y_position>
  <play_button_x_position>740</play_button_x_position>
  <play_button_y_position>570</play_button_y_position>

  <next_button_icon_color>#000000</next_button_icon_color>
  <next_button_background_color>#FFFFFF</next_button_background_color>
  <prev_button_icon_color>#000000</prev_button_icon_color>
  <prev_button_background_color>#FFFFFF</prev_button_background_color>
  <play_button_icon_color>#000000</play_button_icon_color>
  <play_button_background_color>#FFFFFF</play_button_background_color>

  <!--   SCROLLBAR SETTINGS   -->
  <show_scrollbar>no</show_scrollbar>

  <scrollbar_width>400</scrollbar_width>
  <scrollbar_height>15</scrollbar_height>

  <scrollbar_handler_width>80</scrollbar_handler_width>

  <scrollbar_x_position>200</scrollbar_x_position>
  <scrollbar_y_position>573</scrollbar_y_position>

  <scrollbar_handler_color>#FFFFFF</scrollbar_handler_color>
  <scrollbar_track_bar_color>#000000</scrollbar_track_bar_color>

  <scrollbar_lines_image_path>'.base_url('assets/public/banners/HTML5CanvasCoverFlow/load/graphics/lines.png').'</scrollbar_lines_image_path>

 <images>';

foreach ($images as $image) {
    echo '<image>
		<image_path>'.base_url('assets/public/images/banners/banner_'.$image -> bannerId.'_'.$image -> bannerImagesId.'.'.$image -> bannerImageExtension).'</image_path>
		<url_to_open_on_click>http://www.flashcomponents.net/author/fwdesign.html</url_to_open_on_click>
		<text>This is image 1!</text>
	</image>';
}


echo '</images></settings>';

?>
