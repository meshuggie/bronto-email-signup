<?php

/**
 * The admin widget for the plugin.
 *
 * Sets up the widget for embedding the form, along with other copy.
 *
 * @link       https://github.com/meshuggie/bronto-email-signup
 * @since      1.0.0
 *
 * @package    Bronto_Email_Signup
 * @subpackage Bronto_Email_Signup/widget
 * @author     Joshua Harris
 */

class Broes_Widget extends WP_Widget {

  function __construct() {
      parent::__construct(
          'broes',
          'Bronto Email Signup'
      );
  }

  public $args = array(
      'before_title'  => '<h2 class="widget-title">',
      'after_title'   => '</h2>',
      'before_widget' => '<div class="widget-wrap">',
      'after_widget'  => '</div></div>'
  );

  public function widget( $args, $instance ) {

      echo $args['before_widget'];

      if ( ! empty( $instance['title'] ) ) {
          echo $args['before_title'] . apply_filters( 'widget_title', $instance['title'] ) . $args['after_title'];
      }

      echo '<div class="textwidget">';
      echo $instance['text-top'];
      echo do_shortcode('[broes_signup_form prefix-id="' . $args['widget_id'] . '"]');
      echo $instance['text-bottom'];
      echo '</div>';

      echo $args['after_widget'];

  }

  public function form( $instance ) {
      $title = ! empty( $instance['title'] ) ? $instance['title'] : esc_html__( '', 'text_domain' );
      $text_top = ! empty( $instance['text-top'] ) ? $instance['text-top'] : esc_html__( '', 'text_domain' );
      $text_bottom = ! empty( $instance['text-bottom'] ) ? $instance['text-bottom'] : esc_html__( '', 'text_domain' );
      ?>
      <p>
        <label for="<?php echo esc_attr( $this->get_field_id( 'title' ) ); ?>"><?php esc_attr_e( 'Title:', 'text_domain' ); ?></label>
        <input class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'title' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'title' ) ); ?>" type="text" value="<?php echo esc_attr( $title ); ?>">
      </p>
      <p>
        <label for="<?php echo esc_attr( $this->get_field_id( 'text-top' ) ); ?>"><?php esc_attr_e( 'Text Top:', 'text_domain' ); ?></label>
        <textarea class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'text-top' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'text-top' ) ); ?>" type="text" cols="30" rows="5"><?php echo esc_attr( $text_top ); ?></textarea>
      </p>
      <p>
        <label for="<?php echo esc_attr( $this->get_field_id( 'text-bottom' ) ); ?>"><?php esc_attr_e( 'Text Bottom:', 'text_domain' ); ?></label>
        <textarea class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'text-bottom' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'text-bottom' ) ); ?>" type="text" cols="30" rows="5"><?php echo esc_attr( $text_bottom ); ?></textarea>
      </p>
      <?php
  }

  public function update( $new_instance, $old_instance ) {
    $instance = array();
    $instance['title'] = ( !empty( $new_instance['title'] ) ) ? strip_tags( $new_instance['title'] ) : '';
    $instance['text-top'] = ( !empty( $new_instance['text-top'] ) ) ? wp_kses_post($new_instance['text-top']) : '';
    $instance['text-bottom'] = ( !empty( $new_instance['text-bottom'] ) ) ? wp_kses_post($new_instance['text-bottom']) : '';
    return $instance;
  }

}
$broes_widget = new Broes_Widget();
