<?php
class Pay_Jp_Widget extends WP_Widget {
	public function __construct() {
		// widget actual processes
		parent::__construct(
			'pay_jp_widget', // Base ID
			__( 'Pay.jp', 'focus' ), // Name
			array( 'description' => __( 'Displays Pay.jp Form.', 'focus' ), ) // Args
		);
	}

	public function form( $instance ) {
		// outputs the options form on admin
    ?>
    <p>
        <label for="<?php echo $this->get_field_id('public_key'); ?>"><?php _e('Public_key:'); ?></label>
        <input type="text" class="widefat" id="<?php echo $this->get_field_id('public_key'); ?>" name="<?php echo $this->get_field_name('public_key'); ?>" value="<?php echo esc_attr( $instance['public_key'] ); ?>">
    </p>
    <p>
        <label for="<?php echo $this->get_field_id('secret_key'); ?>"><?php _e('Secret_key:'); ?></label>
        <input type="text" class="widefat" id="<?php echo $this->get_field_id('secret_key'); ?>" name="<?php echo $this->get_field_name('secret_key'); ?>" value="<?php echo esc_attr( $instance['secret_key'] ); ?>">
    </p>
    <p>
        <label for="<?php echo $this->get_field_id('thanks_page'); ?>"><?php _e('Thanks_page:'); ?></label>
        <input type="text" class="widefat" id="<?php echo $this->get_field_id('thanks_page'); ?>" name="<?php echo $this->get_field_name('thanks_page'); ?>" value="<?php echo esc_attr( $instance['thanks_page'] ); ?>">
    </p>
    <p>
        <label for="<?php echo $this->get_field_id('register_page'); ?>"><?php _e('Register_page:'); ?></label>
        <input type="text" class="widefat" id="<?php echo $this->get_field_id('register_page'); ?>" name="<?php echo $this->get_field_name('register_page'); ?>" value="<?php echo esc_attr( $instance['register_page'] ); ?>">
    </p>
<?php
	}

	public function update( $new_instance, $old_instance ) {
		// processes widget options to be saved
		return $new_instance;
	}

	public function widget( $args, $instance ) {
    if (is_user_logged_in()) {
		?>
		<aside class="widget widget_search">
      <p><b>動画を購入する</b></p>
      <form action="<?php echo site_url( '/' ) . $instance['thanks_page'] ?>" method="post">
      <input type="hidden" name="post_id" value="<?php echo get_the_ID(); ?>" />
      <table class="product-table product-table--footage"><tbody>
        <tr class=""><td class="product-table__select"><input type="radio" name="amount" value="1620"></td><td class="product-table__name"><span>WEB_(S)</span></td><td class="product-table__size"><!-- react-text: 504 -->320 x 180px<!-- /react-text --></td><td class="product-table__price">¥1,620</td></tr>
        <tr class=""><td class="product-table__select"><input type="radio" name="amount" value="2700"></td><td class="product-table__name"><span>WEB_(L)</span></td><td class="product-table__size"><!-- react-text: 513 -->640 x 360px<!-- /react-text --></td><td class="product-table__price">¥2,700</td></tr>
        <tr class=""><td class="product-table__select"><input type="radio" name="amount" value="4320"></td><td class="product-table__name"><span>NTSC_D1</span></td><td class="product-table__size"><!-- react-text: 522 -->864 x 486px<!-- /react-text --></td><td class="product-table__price">¥4,320</td></tr>
        <tr class=""><td class="product-table__select"><input type="radio" name="amount" value="6480"></td><td class="product-table__name"><span>HD_720</span></td><td class="product-table__size"><!-- react-text: 531 -->1280 x 720px<!-- /react-text --></td><td class="product-table__price">¥6,480</td></tr>
        <tr class="is-active"><td class="product-table__select"><input type="radio" name="amount" value="7560"></td><td class="product-table__name"><span>HD_1080</span></td><td class="product-table__size"><!-- react-text: 540 -->1920 x 1080px<!-- /react-text --></td><td class="product-table__price">¥7,560</td></tr>
        </tbody></table>
        <script src="https://checkout.pay.jp/" class="payjp-button" data-key="<?php echo $instance['public_key']; ?>"></script>
      </form>
    </aside>
		<?php
    } else {
    ?><p>動画を購入するには会員登録が必要です。</p>
     <p>今すぐ<a href="<?php echo site_url( '/' ) . $instance['register_page'] ?>">新規登録</a></p><?php
    }
	}
 
}

function payjp_widgets_init(){
	register_widget( 'Pay_Jp_Widget' );
}

add_action( 'widgets_init', 'payjp_widgets_init' );
