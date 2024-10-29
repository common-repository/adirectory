<?php
if ( ! defined( 'ABSPATH' ) || ! $Helper->admin_view( $data ) ) {
	return '';
}

$label   = $Helper->get_data( $data, 'label', esc_html__( 'Select', 'adirectory' ) );
$fieldid = $Helper->get_data( $data, 'fieldid' );

$name        = "_select_{$fieldid}";
$placeholder = $Helper->get_data( $data, 'placeholder' );
$value       = $Helper->meta_val( $post_id, $name );
$options     = $data['options'] ?? array();

?>


<div class="qsd-form-group qsd-select-field">
	<h4 class="qsd-form-label">
		<?php echo esc_html( $label ); ?>:
		<?php $Helper->required_html( $data ); ?>
	</h4>
	<div class="qsd-form-wrap qsd-form-select-field">
		<select name="<?php echo esc_attr( $name ); ?>" id="<?php echo esc_attr( $name ); ?>" class="qsd-form-control" <?php $Helper->required( $data ); ?>>
		<option value=""><?php echo esc_html( $placeholder ); ?></option>
		<?php
		foreach ( $options as $option ) :

			$optId  = $option['id'] ? trim( $option['id'] ) : '';
			$optVal = $option['value'] ? trim( $option['value'] ) : '';
			?>
										<option value="<?php echo esc_attr( $optVal ); ?>" <?php selected( $value, $optVal ); ?>><?php echo esc_html( $optVal ); ?></option>
									<?php endforeach; ?>

		</select>
	</div>
</div><!-- end \\-->
