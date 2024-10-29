<?php
if ( ! defined( 'ABSPATH' ) || ! $Helper->admin_view( $data ) ) {
	return '';
}

$label   = $Helper->get_data( $data, 'label', esc_html__( 'Radio', 'adirectory' ) );
$fieldid = $Helper->get_data( $data, 'fieldid' );

$name        = "_radio_{$fieldid}";
$placeholder = $Helper->get_data( $data, 'placeholder' );
$value       = $Helper->meta_val( $post_id, $name );
$options     = $data['options'] ?? array();

?>

<div class="qsd-form-group qsd-input-radio-fields">
<h4 class="qsd-form-label">
		<?php echo esc_html( $label ); ?>:
		<?php $Helper->required_html( $data ); ?>
	</h4>
								<div class="qsd-form-wrap qsd-field-inline">
									<?php
									foreach ( $options as $option ) :

										$optId  = $option['id'] ? trim( $option['id'] ) : '';
										$optVal = $option['value'] ? trim( $option['value'] ) : '';
										?>
									<div class="qsd-form-check-control">
										<input type="radio" id="<?php echo esc_attr( $optId ); ?>" name="<?php echo esc_attr( $name ); ?>" value="<?php echo esc_attr( $optVal ); ?>" <?php checked( $value, $optVal ); ?> <?php $Helper->required( $data ); ?>>
										<label for="<?php echo esc_attr( $optId ); ?>"><?php echo esc_html( $optVal ); ?></label>
									</div>
									<?php endforeach; ?>
								</div>
							</div><!-- end \\-->
