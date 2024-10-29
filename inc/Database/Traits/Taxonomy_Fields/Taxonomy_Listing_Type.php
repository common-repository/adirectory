<?php
namespace ADQS_Directory\Database\Traits\Taxonomy_Fields;

trait Taxonomy_Listing_Type {


	private $term_listing_name = 'listing_types';
	/**
	 * Main In Hook
	 *
	 * @since 1.0.0
	 */
	public function load_taxonomy_listing_type() {
		// Icon fields and actions
		add_action( $this->slug . '_add_form_fields', array( $this, 'add_taxonomy_listing_type' ) );
		add_action( 'created_' . $this->slug, array( $this, 'save_taxonomy_listing_type' ) );
		add_action( $this->slug . '_edit_form_fields', array( $this, 'update_taxonomy_listing_type' ) );
		add_action( 'edited_' . $this->slug, array( $this, 'updated_taxonomy_listing_type' ) );
		add_action( 'admin_enqueue_scripts', array( $this, 'load_listing_type_scripts' ) );
	}


	/**
	 * get directory Listing Types
	 *
	 * @since 1.0.0
	 */
	public function get_terms_listing() {
		$terms = adqs_get_directory_types();
		return $terms;
	}

	/**
	 * Add a form field in the new taxonomy page
	 *
	 * @since 1.0.0
	 */
	public function add_taxonomy_listing_type() {
		wp_nonce_field( 'tax_nonce_action', 'tax_nonce' );
		?>
		<style>
			<?php echo wp_kses( $this->add_listing_type_inline_style(), array() ); ?>
			.adqs_listing_type_wrap .adqs_listing_type_input input[type="checkbox"] {
				margin-top: 0;
				margin-right: 5px;
			}
		</style>
		<div class="form-field term-listing_type-wrap">
			<label>
				<?php esc_html_e( 'Directory Type', 'adirectory' ); ?>
			</label>
			<div class="adqs_listing_type_wrap">
				<?php
				$terms = $this->get_terms_listing();
				if ( ! empty( $terms ) ) :
					foreach ( $terms as $term ) :
						?>
						<div class="adqs_listing_type_input">
							<input type="checkbox" name="<?php echo esc_attr( $this->term_listing_name ); ?>[]"
								id="<?php echo esc_attr( $this->term_listing_name . '_' . $term->term_id ); ?>"
								value="<?php echo esc_html( $term->term_id ); ?>">
							<label for="<?php echo esc_attr( $this->term_listing_name . '_' . $term->term_id ); ?>">
								<?php echo esc_html( $term->name ); ?>
							</label>
						</div>
						<?php
					endforeach;
				endif;
				?>
			</div>

		</div>


		<?php
	}

	/**
	 * Save the form field
	 *
	 * @since 1.0.0
	 */
	public function save_taxonomy_listing_type( $term_id ) {
		if ( ! isset( $_POST['tax_nonce'] ) || ! wp_verify_nonce( $_POST['tax_nonce'], 'tax_nonce_action' ) ) {
			return;
		}
		if ( isset( $_POST[ $this->term_listing_name ] ) && ! empty( $_POST[ $this->term_listing_name ] ) ) {
			$term_listing = array_map( 'absint', $_POST[ $this->term_listing_name ] );
			add_term_meta( $term_id, $this->term_listing_name, $term_listing, true );
		}
	}



	/**
	 * Edit the form field
	 *
	 * @since 1.0.0
	 */
	public function update_taxonomy_listing_type( $term ) {
		wp_nonce_field( 'tax_nonce_action', 'tax_nonce' );
		?>
		<style>
			<?php echo wp_kses( $this->add_listing_type_inline_style(), array() ); ?>
			.adqs_listing_type_wrap .adqs_listing_type_input input[type="checkbox"] {
				margin-top: -1px;
				margin-right: 4px;
			}
		</style>
		<tr class="form-field term-group-wrap">
			<th scope="row">
				<label>
					<?php esc_html_e( 'Directory Type', 'adirectory' ); ?>
				</label>
			</th>
			<td>
				<div class="adqs_listing_type_wrap">
					<?php
					$list_ids = get_term_meta( $term->term_id, $this->term_listing_name, true );
					$list_ids = ! empty( $list_ids ) ? $list_ids : array();
					$terms    = $this->get_terms_listing();
					if ( ! empty( $terms ) ) :
						foreach ( $terms as $term ) :
							?>
							<div class="adqs_listing_type_input">
								<input type="checkbox" name="<?php echo esc_attr( $this->term_listing_name ); ?>[]"
									id="<?php echo esc_attr( $this->term_listing_name . '_' . $term->term_id ); ?>"
									value="<?php echo esc_html( $term->term_id ); ?>" <?php echo in_array( $term->term_id, $list_ids ) ? 'checked' : ''; ?>>
								<label for="<?php echo esc_attr( $this->term_listing_name . '_' . $term->term_id ); ?>">
									<?php echo esc_html( $term->name ); ?>
								</label>
							</div>
							<?php
						endforeach;
					endif;
					?>
				</div>
			</td>
		</tr>
		<?php
	}

	/**
	 * Update the form field value
	 *
	 * @since 1.0.0
	 */
	public function updated_taxonomy_listing_type( $term_id ) {
		if ( ! isset( $_POST['tax_nonce'] ) || ! wp_verify_nonce( $_POST['tax_nonce'], 'tax_nonce_action' ) ) {
			return;
		}
		if ( isset( $_POST[ $this->term_listing_name ] ) && ! empty( $_POST[ $this->term_listing_name ] ) ) {
			$term_listing = array_map( 'absint', $_POST[ $this->term_listing_name ] );
			update_term_meta( $term_id, $this->term_listing_name, $term_listing );
		} else {
			update_term_meta( $term_id, $this->term_listing_name, array() );
		}
	}

	/**
	 * Add scripts
	 *
	 * @since 1.0.0
	 */
	public function load_listing_type_scripts() {
		if ( ! isset( $_GET['taxonomy'] ) || isset( $_GET['tag_ID'] ) || ( $_GET['taxonomy'] != $this->slug ) ) {
			return;
		}

		// Load Js

		wp_add_inline_script( 'jquery', $this->add_listing_type_inline_script() );
	}

	/**
	 * Add inline Style
	 *
	 * @since 1.0.0
	 */
	public function add_listing_type_inline_style() {
		ob_start();
		?>

		<style>
			.adqs_listing_type_wrap,
			.adqs_listing_type_wrap .adqs_listing_type_input {
				display: flex;
				align-items: center;
			}

			.adqs_listing_type_wrap .adqs_listing_type_input+.adqs_listing_type_input {
				margin-left: 20px;
			}

			.adqs_listing_type_wrap .adqs_listing_type_input label {
				white-space: nowrap;
			}
		</style>
		<?php
		return str_replace( array( '<style>', '</style>' ), array( '', '' ), ob_get_clean() );
	}
	/**
	 * Add inline scripts
	 *
	 * @since 1.0.0
	 */
	public function add_listing_type_inline_script() {
		ob_start();
		?>

		<script>
			<?php if ( ! isset( $_GET['tag_ID'] ) ) : ?>
				jQuery(document).ajaxComplete(function (event, xhr, settings) {

					var xml = xhr.responseXML,
						$response = jQuery(xml).find('term_id').text();
					if ($response != "") {
						jQuery('.adqs_listing_type_input input[type="checkbox"]').prop('checked', false);
					}
				});
			<?php endif; ?>
		</script>
		<?php
		return str_replace( array( '<script>', '</script>' ), array( '', '' ), ob_get_clean() );
	}
}
