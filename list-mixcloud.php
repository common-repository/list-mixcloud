<?php
/*
Plugin Name: List MixCloud
Plugin URI: http://rodolphe-moulin.fr/list-mixcloud
Description: Show list or last podcast MixCloud of your channel
Version: 1.4
Author: Rodolphe MOULIN
Author URI: http://rodolphe-moulin.fr
License: GPL2
License URI:  https://www.gnu.org/licenses/gpl-2.0.html

{List MixCloud} is free software: you can redistribute it and/or modify
it under the terms of the GNU General Public License as published by
the Free Software Foundation, either version 2 of the License, or
any later version.
 
{List MixCloud} is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
GNU General Public License for more details.
 
You should have received a copy of the GNU General Public License
along with {Plugin Name}. If not, see {URI to Plugin License}.


*/
//ShotCode listmixcloud
//Atributs
//	with in Pixel or %, X*X when widget=picture
//	widget : mini|picture|classic widget iframe by MixCloud
//	mode : 0 to list to show all podcast in a page, last to show the last upload on frontpage by example
//	playlist : 1 to show list of audio instead of list of widget. Show only one widget
define('PLUGIN_VERSION', '1.4');
add_shortcode('listmixcloud', 'getListMixCloud');
$dom = "";
function getNumberPlaylist($username) {
	$i_numberPlaylist = 0;
	$response_code = 0;
	$url_img = "";
	$response_get = wp_remote_get( "https://api.mixcloud.com/".$username );
	$response_code       = wp_remote_retrieve_response_code( $response_get );
	if($response_code == 200) {
		$getJSON = wp_remote_retrieve_body($response_get );
		$data = json_decode($getJSON, TRUE);
		$i_numberPlaylist = intval($data["cloudcast_count"]);
		$url_img = $data["pictures"]["medium"];
	}
	$return_array = array('code'=>$response_code,'number'=>$i_numberPlaylist,'picture'=>$url_img);
	return json_encode($return_array);
}
function getListMixCloud($atts) {
	$atts = shortcode_atts(array('channel'=>'EgliseEvangéliqueDeTOURS', 'widget'=>'mini', 'mode'=>'0', 'playlist'=>'', 'autoplay'=>'', 'style'=>'Light', 'width'=>'100%', 'hide_artwork'=>'0'), $atts);
	$nickname = urlencode($atts["channel"]);
	$limit = "?limit=".$atts["mode"];
	if($atts["mode"]=="0") {
		$limit = "";
		$response_json = json_decode(getNumberPlaylist($nickname));
		if($response_json->code = 200) {
			$limit = "?limit=".$response_json->number;
		}
	}
	$textStyle = "";
	if($atts["style"]=="Light") {
		$textStyle = "1";
	}
	$response_get = wp_remote_get( "https://api.mixcloud.com/".$nickname."/cloudcasts/".$limit );
	$response_code       = wp_remote_retrieve_response_code( $response_get );
	$response_message = wp_remote_retrieve_response_message( $response_get );

	if ( 200 != $response_code && ! empty( $response_message ) ) {
		$dom = "Mixcloud Error ". $response_code." ".$response_message;
	} elseif ( 200 != $response_code ) {
		$dom = "Mixcloud Error : ".$response_code." Error is not defined. Maybe checked your internet connection";
	} else {
		$getJSON = wp_remote_retrieve_body($response_get );
		$data = json_decode($getJSON, TRUE);
		$dom = "";
		$track = "";
		foreach ($data["data"] as $dataItem) {
			$keyMixCloud = urlencode($dataItem["key"]);
			$keypure = $dataItem["key"];
			$textWidth = $atts["width"];
			$name = $dataItem['name'];
			if($atts["playlist"]!=1) {
				if($atts["widget"]=="mini"){
					$dom .= '<center><iframe width="'.$textWidth.'" height="60" src="https://www.mixcloud.com/widget/iframe/?hide_cover=1&mini=1&light='.$textStyle.'&autoplay='.$atts["autoplay"].'&hide_artwork='.$atts["hide_artwork"].'&feed='.$keyMixCloud.'" frameborder="0" ></iframe></center>';
				}
				if($atts["widget"]=="picture"){
					$dom .= '<iframe width="'.$textWidth.'" height="'.$textWidth.'" src="https://www.mixcloud.com/widget/iframe/?light='.$textStyle.'&autoplay='.$atts["autoplay"].'&hide_artwork='.$atts["hide_artwork"].'&feed='.$keyMixCloud.'" frameborder="0" ></iframe>';
				}
				if($atts["widget"]=="classic"){
					$dom .= '<iframe width='.$textWidth.' height="120" src="https://www.mixcloud.com/widget/iframe/?hide_cover=1&light='.$textStyle.'&autoplay='.$atts["autoplay"].'&hide_artwork='.$atts["hide_artwork"].'&feed='.$keyMixCloud.'" frameborder="0" style="margin-bottom:20px"></iframe><br/>';
				}
			}
			else {
				if($dom=="") {
					if($atts["widget"]=="mini"){
					$dom .= '<center><iframe id="uniqueWidget" width="'.$textWidth.'" height="60" src="https://www.mixcloud.com/widget/iframe/?hide_cover=1&mini=1&light='.$textStyle.'&autoplay='.$atts["autoplay"].'&hide_artwork='.$atts["hide_artwork"].'&feed='.$keyMixCloud.'" frameborder="0" ></iframe></center>';
					}
					if($atts["widget"]=="picture"){
						$dom .= '<iframe id="uniqueWidget" width="'.$textWidth.'" height="'.$textWidth.'" src="https://www.mixcloud.com/widget/iframe/?light='.$textStyle.'&autoplay='.$atts["autoplay"].'&hide_artwork='.$atts["hide_artwork"].'&feed='.$keyMixCloud.'" frameborder="0" ></iframe>';
					}
					if($atts["widget"]=="classic"){
						$dom .= '<iframe id="uniqueWidget" width='.$textWidth.' height="120" src="https://www.mixcloud.com/widget/iframe/?hide_cover=1&light='.$textStyle.'&autoplay='.$atts["autoplay"].'&hide_artwork='.$atts["hide_artwork"].'&feed='.$keyMixCloud.'" frameborder="0" style="margin-bottom:20px"></iframe><br/>';
					}				
					
					$track .= "<li class='plSel'><div class='plItem'><span class='plTitle' idtrack='$keyMixCloud'>$name</span></div></li>";
				}
				else {
					$track .= "<li><div class='plItem'><span class='plTitle' idtrack='$keyMixCloud'>$name</span></div></li>";
				}
			}
		}
	}
	if($atts["playlist"]=="1") {
		$dom .= '<div id="plwrap"><ul id="plList">'.$track.'</ul></div>';
	}
	echo $dom;
}

class MixCloud_Last_Widget extends WP_Widget
{
    public function __construct()
    {
        parent::__construct('MixCloud', 'MixCloud', array('description' => 'Show last podcast or all podcasts of Mixcloud Channel'));
    }
	
	public function form($instance)

{

    $mode = isset($instance['mode']) ? $instance['mode'] : 'classic';
	$number = isset($instance['mode']) ? $instance['number'] : '0';
	$textNumber = $number;
	if($number==0) {
		$textNumber = "List";
	}
	$width = isset($instance['width']) ? $instance['width'] : '100%';
	$channel = isset($instance['channel']) ? $instance['channel'] : 'EgliseEvangéliqueDeTOURS';
	$autoplay = isset($instance['autoplay']) ? $instance['autoplay'] : '';
	$playlist = isset($instance['playlist']) ? $instance['playlist'] : '';
	$hide_artwork = isset($instance['hide_artwork']) ? $instance['hide_artwork'] : '';
	$textautoplay = '';
	if($autoplay=="1") {
		$textautoplay = "checked";
	}
	$textplaylist = '';
	if($playlist=="1") {
		$textplaylist = "checked";
	}
	$texthide_artwork = '';
	if($hide_artwork=="1") {
		$texthide_artwork = "checked";
	}
	$style = isset($instance['style']) ? $instance['style'] : 'Light';
    ?>

    <p>
		<label><?php _e("Channel") ?></label>
		<input class="dataautocompletion" type="text" id="<?php echo $this->get_field_id( 'channel' ); ?>" name="<?php echo $this->get_field_name( 'channel' ); ?>" value="<?php echo $channel; ?>" />
		<span class="informationChannel"><?php $result_json_info = json_decode(getNumberPlaylist($channel)); echo ('<img width="20px" src="'.$result_json_info->picture.'">'.$result_json_info->number)._(" playlists found"); ?></span>
	</p>
	<p>

        <label>Mode</label>

        <select id="<?php echo $this->get_field_id( 'mode' ); ?>" name="<?php echo $this->get_field_name( 'mode' ); ?>">
		<option value="<?php echo $mode; ?>"><?php echo $mode; ?></option>
		<option value="classic">Classic</option>
		<option value="picture">Picture</option>
		<option value="mini">Mini</option>
		</select>
	</p>
	<p>
		<label><?php _e("Show only one widget and playlist") ?></label>
		<input type="checkbox" id="<?php echo $this->get_field_id( 'playlist' ); ?>" name="<?php echo $this->get_field_name( 'playlist' ); ?>" value="1" <?php echo $textplaylist; ?> />
	</p>
	<p>
		<label>Number*</label>
		<input type="number" id="<?php echo $this->get_field_id( 'number' ); ?>" name="<?php echo $this->get_field_name( 'number' ); ?>" value="<?php echo $number; ?>" />
		<legend><i>*0 to infinite</i></legend>
	</p>
	<p>
		<label><?php _e("Width") ?></label>
		<input type="text" id="<?php echo $this->get_field_id( 'width' ); ?>" name="<?php echo $this->get_field_name( 'width' ); ?>" value="<?php echo $width; ?>" />
	</p>
	<p>
		<label><?php _e("Autoplay") ?></label>
		<input type="checkbox" id="<?php echo $this->get_field_id( 'autoplay' ); ?>" name="<?php echo $this->get_field_name( 'autoplay' ); ?>" value="1" <?php echo $textautoplay; ?> />
	</p>
	<p>
		<label><?php _e("Hide Artwork") ?></label>
		<input type="checkbox" id="<?php echo $this->get_field_id( 'hide_artwork' ); ?>" name="<?php echo $this->get_field_name( 'hide_artwork' ); ?>" value="1" <?php echo $texthide_artwork; ?> />
	</p>
	    <p>

        <label>Style</label>
        <select id="<?php echo $this->get_field_id( 'style' ); ?>" name="<?php echo $this->get_field_name( 'style' ); ?>">
		<option value="<?php echo $style; ?>"><?php echo $style; ?></option>
		<option value="Light"><?php _e("Light") ?></option>
		<option value="Black"><?php _e("Black") ?></option>
		</select>
	</p>
    

    <?php

}

    
	public function widget($args, $instance)
	{
		$mode = isset($instance['mode']) ? $instance['mode'] : 'classic';
		$number = isset($instance['number']) ? $instance['number'] : '1';
		$autoplay = isset($instance['autoplay']) ? $instance['autoplay'] : '0';
		$playlist = isset($instance['playlist']) ? $instance['playlist'] : '0';
		$style = isset($instance['style']) ? $instance['style'] : 'Light';
		$width = isset($instance['width']) ? $instance['width'] : '100%';
		$channel = isset($instance['channel']) ? $instance['channel'] : 'EgliseEvangéliqueDeTOURS';
		$hide_artwork = isset($instance['hide_artwork']) ? $instance['hide_artwork'] : '';
		getListMixCloud(array('channel'=>$channel, 'widget'=>"$mode", 'mode'=>"$number",'playlist'=>"$playlist",'autoplay'=>"$autoplay",'style'=>"$style",'width'=>"$width",'hide_artwork'=>"$hide_artwork"), "EgliseEvangéliqueDeTOURS");
	}
}
class MixCloud_Last
{
    public function __construct()
    {
        add_action('widgets_init', function(){register_widget('MixCloud_Last_Widget');});
    }
}

function wptuts_scripts_important()
{
	// Register the script like this for a plugin:
	wp_register_script('mixcloud_script','//widget.mixcloud.com/media/js/widgetApi.js');
    wp_register_script( 'custom-script', plugins_url( 'playlist.js', __FILE__ ) );
    // Register the style like this for a plugin:
    wp_register_style( 'custom-style', plugins_url( 'playlist.css', __FILE__ ) );
    // For either a plugin or a theme, you can then enqueue the style:
    wp_enqueue_style( 'custom-style' ); 
    // For either a plugin or a theme, you can then enqueue the script:
	wp_enqueue_script('jquery');
	wp_enqueue_script('mixcloud_script');
    wp_enqueue_script( 'custom-script',['jquery','mixcloud_script'] );
	
}

function admin_script() {
	
	wp_register_script( 'vanilla_js', plugins_url('vanilla/auto-complete.min.js',__FILE__) );
	wp_enqueue_style('vanilla_css', plugins_url('vanilla/auto-complete.css',__FILE__),false,PLUGIN_VERSION,false);
	wp_enqueue_script( 'jquery' );
	wp_enqueue_script('vanilla_js',['jquery']);
	wp_register_script( 'autocompletion-script', plugins_url( 'autocompletion.js', __FILE__ ) );
	wp_enqueue_script('autocompletion-script',['jquery','vanilla_js']);
}

add_action( 'admin_enqueue_scripts', 'admin_script',10 );
add_action( 'wp_enqueue_scripts', 'wptuts_scripts_important', 20 );


new MixCloud_Last();
?>