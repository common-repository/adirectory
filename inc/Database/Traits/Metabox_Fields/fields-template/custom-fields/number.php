<?php
if ( ! defined( 'ABSPATH' ) || ! $Helper->admin_view( $data ) ) {
	return '';
}

$label   = $Helper->get_data( $data, 'label', esc_html__( 'Number', 'adirectory' ) );
$fieldid = $Helper->get_data( $data, 'fieldid' );

$name        = "_number_{$fieldid}";
$placeholder = $Helper->get_data( $data, 'placeholder' );
$value       = $Helper->meta_val( $post_id, $name );
?>

<div class="qsd-form-group qsd-number-field">
	<h4 class="qsd-form-label">
		<?php echo esc_html( $label ); ?>:
		<?php $Helper->required_html( $data ); ?>
	</h4>
	<div class="qsd-form-wrap qsd-form-field">
		<input type="number" name="<?php echo esc_attr( $name ); ?>" id="<?php echo esc_attr( $name ); ?>"
			class="qsd-form-control" value="<?php echo esc_attr( $value ); ?>"
			placeholder="<?php echo esc_attr( $placeholder ); ?>" <?php $Helper->required( $data ); ?>>
	</div>

</div><!-- end \\-->
