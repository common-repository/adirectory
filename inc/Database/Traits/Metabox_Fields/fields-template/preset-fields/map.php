<?php
if ( ! defined( 'ABSPATH' ) || ! $Helper->admin_view( $data ) ) {
	return '';
}

$label          = $Helper->get_data( $data, 'label', esc_html__( 'Map', 'adirectory' ) );
$name_lat       = '_map_lat';
$name_lon       = '_map_lon';
$name_hide_map  = '_hide_map';
$placeholder    = $Helper->get_data( $data, 'placeholder' );
$value_lat      = $Helper->meta_val( $post_id, $name_lat );
$value_lon      = $Helper->meta_val( $post_id, $name_lon );
$value_hide_map = absint( $Helper->meta_val( $post_id, $name_hide_map ) );
?>

<div class="qsd-form-group qsd-map-field">
	<h4 class="qsd-form-label">
		<?php echo esc_html( $label ); ?>:
		<?php $Helper->required_html( $data ); ?>
	</h4>
	<div class="qsd-form-wrap qsd-form-field">

		<div class="adqs_map-latlon-wrap">
			<div class="adqs_map-input adqs_map-lat">
				<input type="text" name="<?php echo esc_attr( $name_lat ); ?>" id="<?php echo esc_attr( $name_lat ); ?>"
					class="qsd-form-control" value="<?php echo esc_attr( $value_lat ); ?>" <?php $Helper->required( $data ); ?>>
			</div>
			<div class="adqs_map-input adqs_map-lon">
				<input type="text" name="<?php echo esc_attr( $name_lon ); ?>" id="<?php echo esc_attr( $name_lon ); ?>"
					class="qsd-form-control" value="<?php echo esc_attr( $value_lon ); ?>" <?php $Helper->required( $data ); ?>>
			</div>
			<div class="adqs_map-input adqs_map-generate">
				<button class="qsd-btn qsd-btn-latlon-generate">
					<?php echo esc_html__( 'Map Generate', 'adirectory' ); ?>
				</button>
			</div>
			<div class="adqs_map-input adqs_map-hide">
				<div class="qsd-form-check-control">
					<input type="checkbox" id="<?php echo esc_attr( $name_hide_map ); ?>"
						name="<?php echo esc_attr( $name_hide_map ); ?>" value="1" <?php checked( $value_hide_map, 1 ); ?>>
					<label for="<?php echo esc_attr( $name_hide_map ); ?>">
						<?php echo esc_html__( 'Hide Map', 'adirectory' ); ?>
					</label>
				</div>
			</div>
		</div>
		<div id="adqs_map"></div>
	</div>

</div><!-- end \\-->
