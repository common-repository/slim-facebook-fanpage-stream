<?php
/*
Plugin Name: Ideenlounge Slim Facebook Fanpage Stream
Description: Zeigt die letzten Posts einer Facebook Fanpage ohne Page Plugin
Author: Mirjam Schrepler
Version: 0.96
Author URI: http://www.ideenlounge.de/
*/
/* Start Adding Functions Below this Line */





// Creating  widget 
class ideenlounge_facebook_widget1 extends WP_Widget {

function __construct() {

parent::__construct(

'ideenlounge_facebook_widget1', 

__('Slim Facebook Fanpage Stream', 'ideenlounge_slim_fanpage_stream'), 

array( 'description' => __( 'Zeigt die letzten Posts einer Facebook Fanpage', 'ideenlounge_slim_fanpage_stream' ), ) 
);

}



 



// front-end

public function widget( $args, $instance ) {
$title = apply_filters( 'widget_title', $instance['title'] );
$anzahl = apply_filters( 'widget_anzahl', $instance['anzahl'] );
$facebookseitenid = apply_filters( 'widget_facebookseitenid', $instance['facebookseitenid'] );
$facebookappid = apply_filters( 'widget_facebookappid', $instance['facebookappid'] );
$facebooksecret = apply_filters( 'widget_facebooksecret', $instance['facebooksecret'] );
$facebookhashtag = apply_filters( 'widget_facebookhashtag', $instance['facebookhashtag'] );
$facebooklaenge = apply_filters( 'widget_facebooklaenge', $instance['facebooklaenge'] );
$facebookstyle = apply_filters( 'widget_facebookstyle', $instance['facebookstyle'] );

  
  



//CSS laden

if ($instance['facebookstyle'] != "none") {
     wp_enqueue_style('ideenlounge_facebookstream',plugins_url() . '/slim-facebook-fanpage-stream/css/ideenlounge_facebookstream_'.$instance['facebookstyle'].'.css');
  }
 
  
 

echo $args['before_widget'];
if ( ! empty( $title ) )
echo $args['before_title'] . $title . $args['after_title'];


// display the output

echo '<div class="own_colors"><div id="facebook_widget" class="ideenlounge">';

$anzahlneu = $anzahl + 1;
$url = 'https://graph.facebook.com/'.$facebookseitenid.'/feed?access_token='.$facebookappid.'|'.$facebooksecret.'&fields=attachments,message,link,from,type,name,caption,created_time,description,picture&limit='.$anzahlneu;
 
$rCURL = curl_init();

curl_setopt($rCURL, CURLOPT_URL, $url);
curl_setopt($rCURL, CURLOPT_HEADER, 0);
curl_setopt($rCURL, CURLOPT_RETURNTRANSFER, 1);

$json = curl_exec($rCURL);

curl_close($rCURL);
$json_data = json_decode($json, true);

$count_empty = 0;
$count = 0;

for (; ; ) {

		if (empty($json_data['data'][$count]['created_time'])) { 	

				 
				 break;
				 
   				 } 

    		if ($count > $anzahl-1+$count_empty  ) 
				{
				 break;
   				 }
				 


if (isset($json_data['data'][$count]['link'])) {
$postlink = $json_data['data'][$count]['link']; }

if (empty($postlink)) { $count=='5'; }

if (isset($json_data['data'][$count]['type'])) {
$posttype = $json_data['data'][$count]['type']; }





if (isset($json_data['data'][$count]['picture'])) 
{ 
$postimage = $json_data['data'][$count]['picture']; }


if (isset($json_data['data'][$count]['message'])) 
{ 
$postmessage = $json_data['data'][$count]['message'];

if (!empty($facebooklaenge)) {
$postmessagekurz = substr($postmessage,0, $facebooklaenge); 
}
else {
$postmessagekurz = $postmessage;}
}

if (isset($json_data['data'][$count]['name'])) 
{ 
$postname = $json_data['data'][$count]['name']; }


$created_time = $json_data['data'][$count]['created_time'];
$date_source = strtotime($created_time);		
$posttime = date('\V\o\m d.m.Y \u\m H:i\h', $date_source);



if (!empty($facebookhashtag) && strpos($postmessage,$facebookhashtag) !== false || empty($facebookhashtag)) {
 if ($posttype == "status") {
		echo '<a href="https://www.facebook.com/'.$facebookseitenid.'/reviews" target="_blank">';
		}
	if ($posttype != "status") {
		echo '<a href="'.$postlink.'" target="_blank">';
		}
	if (!empty($postimage) && isset($json_data['data'][$count]['picture'])) 
			{ echo '<img src="'.$postimage.'">'; }
	echo '<b>'.$posttime.'</b><br>';
	if (isset($json_data['data'][$count]['message'])) { echo $postmessagekurz ;
	$text1 = strlen($postmessage);
	$text2 = strlen($postmessagekurz);
	if ($text1 != $text2) {	echo ' [...] ';}			
	echo ' | ' ; }
	if (isset($json_data['data'][$count]['name'])) { echo ' <em> '.$postname.'</em>' ;}
	  echo '<div class="clear"></div>';
		  echo '</a>' ;
	
		     $count = $count+1; 

	}
	
	else {
	
	
$count_empty =	$count_empty+1; 	     
$count = $count+1; 
	
	}
		
		
}



?>
</div></div>

<?php
echo $args['after_widget'];
}




// Widget Backend *******************************************************************
public function form( $instance ) {
if ( isset( $instance[ 'title' ] ) ) {
$title = $instance[ 'title' ];
}
else {
$title = __( 'News von Facebook', 'ideenlounge_slim_fanpage_stream' );
}

if ( isset( $instance[ 'anzahl' ] ) ) {
$anzahl = $instance[ 'anzahl' ];
}
else {
$anzahl = __( '5', 'ideenlounge_slim_fanpage_stream' );
}

if ( isset( $instance[ 'facebookseitenid' ] ) ) {
$facebookseitenid = $instance[ 'facebookseitenid' ];
}
else {
$facebookseitenid = __( 'Wichtig - sonst funzt es nicht!!!!!', 'ideenlounge_slim_fanpage_stream' );
}

if ( isset( $instance[ 'facebookappid' ] ) ) {
$facebookappid = $instance[ 'facebookappid' ];
}
else {
$facebookappid = __( 'Wichtig - sonst funzt es nicht!!!!!', 'ideenlounge_slim_fanpage_stream' );
}

if ( isset( $instance[ 'facebooksecret' ] ) ) {
$facebooksecret = $instance[ 'facebooksecret' ];
}
else {
$facebooksecret = __( 'Wichtig - sonst funzt es nicht!!!!!', 'ideenlounge_slim_fanpage_stream' );
}


if ( isset( $instance[ 'facebookhashtag' ] ) ) {
$facebookhashtag = $instance[ 'facebookhashtag' ];
}
else {
$facebookhashtag = __( '', 'ideenlounge_slim_fanpage_stream' );
}


if ( isset( $instance[ 'facebookhashtag' ] ) ) {
$facebooklaenge = $instance[ 'facebooklaenge' ];
}
else {
$facebooklaenge = __( '', 'ideenlounge_slim_fanpage_stream' );
}


if ( isset( $instance[ 'facebookstyle' ] ) ) {
$facebookstyle = $instance[ 'facebookstyle' ];
}
else {
$facebookstyle = __( '', 'ideenlounge_slim_fanpage_stream' );
}


// Widget admin form
?>

<p>
<label for="<?php echo $this->get_field_id( 'title' ); ?>"><?php _e( 'Title:' ); ?></label> 
<input class="widefat" id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" type="text" value="<?php echo esc_attr( $title ); ?>" />
  
</p>
<p>
<label for="<?php echo $this->get_field_id( 'anzahl' ); ?>"><?php _e( 'Anzahl der Posts:' ); ?></label> 
<input class="widefat" id="<?php echo $this->get_field_id( 'anzahl' ); ?>" type="number" name="<?php echo $this->get_field_name( 'anzahl' ); ?>" value="<?php echo esc_attr( $anzahl ); ?>" /> </p>

<p>
<label for="<?php echo $this->get_field_id( 'facebookseitenid' ); ?>"><?php _e( 'Facebook Seiten ID:' ); ?></label> <a href="http://findmyfbid.com/" target="_blank"><small>Seiten-ID herausfinden</small></a>
<input class="widefat" id="<?php echo $this->get_field_id( 'facebookseitenid' ); ?>" name="<?php echo $this->get_field_name( 'facebookseitenid' ); ?>" type="text" value="<?php echo esc_attr( $facebookseitenid ); ?>" />
</p>

<p>
<label for="<?php echo $this->get_field_id( 'facebookappid' ); ?>"><?php _e( 'Facebook App ID:' ); ?></label> <a href="http://www.ideenlounge.de/facebook-fanpage-stream-plugin/" target="_blank"><small>App-ID & Secret anlegen</small></a>
<input class="widefat" id="<?php echo $this->get_field_id( 'facebookappid' ); ?>" name="<?php echo $this->get_field_name( 'facebookappid' ); ?>" type="text" value="<?php echo esc_attr( $facebookappid ); ?>" />
</p>

<p>
<label for="<?php echo $this->get_field_id( 'facebooksecret' ); ?>"><?php _e( 'Facebook Secret:' ); ?></label> 
<input class="widefat" id="<?php echo $this->get_field_id( 'facebooksecret' ); ?>" name="<?php echo $this->get_field_name( 'facebooksecret' ); ?>" type="text" value="<?php echo esc_attr( $facebooksecret ); ?>" />
</p>

<p>
<label for="<?php echo $this->get_field_id( 'facebookhashtag' ); ?>"><?php _e( 'Optional: Nur Beitr&auml;ge mit diesem Hashtag:' ); ?></label> 
<input class="widefat" id="<?php echo $this->get_field_id( 'facebookhashtag' ); ?>" name="<?php echo $this->get_field_name( 'facebookhashtag' ); ?>" type="text" value="<?php echo esc_attr( $facebookhashtag ); ?>" />
</p>

<p>
<label for="<?php echo $this->get_field_id( 'facebooklaenge' ); ?>"><?php _e( 'Optional: L&auml;nge des Textes (Zeichenanzahl):' ); ?></label> 
<input class="widefat" id="<?php echo $this->get_field_id( 'facebooklaenge' ); ?>" name="<?php echo $this->get_field_name( 'facebooklaenge' ); ?>" type="text" value="<?php echo esc_attr( $facebooklaenge ); ?>" />
</p>

<p>
<label for="<?php echo $this->get_field_id( 'facebookstyle' ); ?>"><?php _e( 'Style:' ); ?></label> 
<select class="widefat" id="<?php echo $this->get_field_id( 'facebookstyle' ); ?>" name="<?php echo $this->get_field_name( 'facebookstyle' ); ?>">
	<option value="none"  <?php echo "none" == $facebookstyle ? "selected" : ""; ?>>None</option>
     <option value="blacknwhite" <?php echo "blacknwhite" == $facebookstyle ? "selected" : ""; ?>>Black & White</option>
	  <option value="coffee" <?php echo "coffee" == $facebookstyle ? "selected" : ""; ?>>Coffee</option>
	   <option value="romantic" <?php echo "romantic" == $facebookstyle ? "selected" : ""; ?>>Romantic</option>
	    <option value="icyblue" <?php echo "icyblue" == $facebookstyle ? "selected" : ""; ?>>Icy blue</option>
	 
</select>

   
</p>





<p><a href="http://www.ideenlounge.de/facebook-fanpage-stream-plugin/" target="_blank"><b>Fragen? Hier findet ihr eine kleine Anleitung :-)</b></a></p>
<?php


/*** end editing ***/



}




 
	
	
 

		



// Updating widget replacing old instances with new
public function update( $new_instance, $old_instance ) {
$instance = array();
$instance['title'] = ( ! empty( $new_instance['title'] ) ) ? strip_tags( $new_instance['title'] ) : '';
$instance['anzahl'] = ( ! empty( $new_instance['anzahl'] ) ) ? strip_tags( $new_instance['anzahl'] ) : '';
$instance['facebookseitenid'] = ( ! empty( $new_instance['facebookseitenid'] ) ) ? strip_tags( $new_instance['facebookseitenid'] ) : '';
$instance['facebookappid'] = ( ! empty( $new_instance['facebookappid'] ) ) ? strip_tags( $new_instance['facebookappid'] ) : '';
$instance['facebooksecret'] = ( ! empty( $new_instance['facebooksecret'] ) ) ? strip_tags( $new_instance['facebooksecret'] ) : '';
$instance['facebookhashtag'] = ( ! empty( $new_instance['facebookhashtag'] ) ) ? strip_tags( $new_instance['facebookhashtag'] ) : '';
$instance['facebooklaenge'] = ( ! empty( $new_instance['facebooklaenge'] ) ) ? strip_tags( $new_instance['facebooklaenge'] ) : '';
$instance['facebookstyle'] = $new_instance['facebookstyle'];

return $instance;

}

 

} // Class ideenlounge_facebook_widget1 ends here


 
	

// Register and load the widget
function wpb_load_widget() {
	register_widget( 'ideenlounge_facebook_widget1' );
}
add_action( 'widgets_init', 'wpb_load_widget' );




/* Stop Adding Functions Below this Line */
?>