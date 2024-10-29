<?php
if ( ! defined( 'ABSPATH' ) || ! $Helper->admin_view( $data ) ) {
	return '';
}
$label       = $Helper->get_data( $data, 'label', esc_html__( 'Address', 'adirectory' ) );
$name        = '_address';
$placeholder = $Helper->get_data( $data, 'placeholder' );
$value       = $Helper->meta_val( $post_id, $name );
?>

<div class="qsd-form-group qsd-address-field">
	<h4 class="qsd-form-label">
		<?php echo esc_html( $label ); ?>:
		<?php $Helper->required_html( $data ); ?>
	</h4>
	<div class="qsd-form-wrap qsd-form-field">
		<input type="text" name="<?php echo esc_attr( $name ); ?>" id="<?php echo esc_attr( $name ); ?>"
			class="qsd-form-control" value="<?php echo esc_attr( $value ); ?>"
			placeholder="<?php echo esc_attr( $placeholder ); ?>" <?php $Helper->required( $data ); ?>>
		<ul id="adqs_address_result"></ul>
	</div>

</div><!-- end \\-->
