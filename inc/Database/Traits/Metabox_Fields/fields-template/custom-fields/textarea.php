<?php
if ( ! defined( 'ABSPATH' ) || ! $Helper->admin_view( $data ) ) {
	return '';
}

$label   = $Helper->get_data( $data, 'label', esc_html__( 'Textarea', 'adirectory' ) );
$fieldid = $Helper->get_data( $data, 'fieldid' );

$name            = "_textarea_{$fieldid}";
$name_list_show  = "_textarea_list_{$fieldid}";
$placeholder     = $Helper->get_data( $data, 'placeholder' );
$value           = $Helper->meta_val( $post_id, $name );
$value_list_show = $Helper->meta_val( $post_id, $name_list_show );
?>


<div class="qsd-form-group qsd-textarea-field">
	<h4 class="qsd-form-label">
		<?php echo esc_html( $label ); ?>:
		<?php $Helper->required_html( $data ); ?>
	</h4>
	<div class="qsd-form-wrap qsd-form-field">
		<textarea name="<?php echo esc_attr( $name ); ?>" rows="8" class="qsd-form-control" placeholder="<?php echo esc_attr( $placeholder ); ?>" <?php $Helper->required( $data ); ?>><?php echo esc_html( $value ); ?></textarea>
		<div class="qsd-form-check-control">
			<input type="checkbox" id="<?php echo esc_attr( $name_list_show ); ?>"
				name="<?php echo esc_attr( $name_list_show ); ?>" value="1" <?php checked( $value_list_show, 1 ); ?>>
			<label for="<?php echo esc_attr( $name_list_show ); ?>">
				<?php echo esc_html__( 'Each new line will be printed with list item', 'adirectory' ); ?>
			</label>
		</div>
	</div>
</div><!-- end \\-->
