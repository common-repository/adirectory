<?php
namespace ADQS_Directory\Database\Traits\Taxonomy_Fields;

trait Taxonomy_Icon {


	private $term_icon_name;
	/**
	 * Main In Hook
	 *
	 * @since 1.0.0
	 */
	public function load_taxonomy_icon() {
		$this->term_icon_name = $this->slug . '_icon_id';
		// Icon fields and actions
		add_action( $this->slug . '_add_form_fields', array( $this, 'add_taxonomy_icon' ) );
		add_action( 'created_' . $this->slug, array( $this, 'save_taxonomy_icon' ) );
		add_action( $this->slug . '_edit_form_fields', array( $this, 'update_taxonomy_icon' ) );
		add_action( 'edited_' . $this->slug, array( $this, 'updated_taxonomy_icon' ) );
		add_action( 'admin_enqueue_scripts', array( $this, 'load_icon_scripts' ) );
	}

	/**
	 * Add a form field in the new taxonomy page
	 *
	 * @since 1.0.0
	 */
	public function add_taxonomy_icon() {
		wp_nonce_field( 'tax_nonce_action', 'tax_nonce' );
		?>
		<style>
			.column-adqs_category_icon i {
				font-size: 20px;
			}
		</style>
		<div class="form-field term-icon-picker-wrap">
			<label for="<?php echo esc_attr( $this->term_icon_name ); ?>">
				<?php esc_html_e( 'Category Icon', 'adirectory' ); ?>
			</label>
			<input style="display:none;" type="text" id="<?php echo esc_attr( $this->term_icon_name ); ?>"
				name="<?php echo esc_attr( $this->term_icon_name ); ?>" value="">
			<div class="term-icon-picker">
				<div id="btn-select">
					<i id="icon-view"></i>
					<span id="icon-text">
						<?php echo esc_html__( 'Click to select icon', 'adirectory' ); ?>
					</span>
				</div>
				<div id="btn-reset">
					<?php echo esc_html__( 'Remove', 'adirectory' ); ?>
				</div>
			</div>

		</div>
		<?php
	}

	/**
	 * Save the form field
	 *
	 * @since 1.0.0
	 */
	public function save_taxonomy_icon( $term_id ) {
		if ( ! isset( $_POST['tax_nonce'] ) || ! wp_verify_nonce( $_POST['tax_nonce'], 'tax_nonce_action' ) ) {
			return;
		}
		if ( isset( $_POST[ $this->term_icon_name ] ) && ( '' !== $_POST[ $this->term_icon_name ] ) ) {
			add_term_meta( $term_id, $this->term_icon_name, sanitize_text_field( $_POST[ $this->term_icon_name ] ), true );
		}
	}

	/**
	 * Edit the form field
	 *
	 * @since 1.0.0
	 */
	public function update_taxonomy_icon( $term ) {
		wp_nonce_field( 'tax_nonce_action', 'tax_nonce' );
		?>
		<tr class="form-field term-group-wrap">
			<th scope="row">
				<label for="<?php echo esc_attr( $this->term_icon_name ); ?>">
					<?php esc_html_e( 'Category Icon', 'adirectory' ); ?>
				</label>
			</th>
			<td>
				<?php
				$icon_name = get_term_meta( $term->term_id, $this->term_icon_name, true );

				?>
				<input style="display:none;" type="text" id="<?php echo esc_attr( $this->term_icon_name ); ?>"
					name="<?php echo esc_attr( $this->term_icon_name ); ?>" value="<?php echo esc_attr( $icon_name ); ?>">
				<div class="term-icon-picker">
					<div id="btn-select">
						<i id="icon-view" class="<?php echo esc_attr( $icon_name ); ?>"></i>
						<span id="icon-text">
							<?php
							if ( ! empty( $icon_name ) ) {
								echo esc_html( $icon_name );
							} else {
								echo esc_html__( 'Click to select icon', 'adirectory' );
							}

							?>
						</span>
					</div>
					<div id="btn-reset">
						<?php echo esc_html__( 'Remove', 'adirectory' ); ?>
					</div>
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
	public function updated_taxonomy_icon( $term_id ) {
		if ( ! isset( $_POST['tax_nonce'] ) || ! wp_verify_nonce( $_POST['tax_nonce'], 'tax_nonce_action' ) ) {
			return;
		}
		if ( isset( $_POST[ $this->term_icon_name ] ) && ( '' !== $_POST[ $this->term_icon_name ] ) ) {
			update_term_meta( $term_id, $this->term_icon_name, sanitize_text_field( $_POST[ $this->term_icon_name ] ) );
		} else {
			update_term_meta( $term_id, $this->term_icon_name, '' );
		}
	}

	/**
	 * Add scripts
	 *
	 * @since 1.0.0
	 */
	public function load_icon_scripts() {
		if ( ! isset( $_GET['taxonomy'] ) || $_GET['taxonomy'] != $this->slug ) {
			return;
		}

		// Load Js
		wp_enqueue_script( 'tax-icon-picker', ADQS_DIRECTORY_ASSETS_URL . '/admin/extra/icon-picker/js/universal-icon-picker.js', array(), ADQS_DIRECTORY_VERSION, true );
		wp_add_inline_script( 'tax-icon-picker', $this->add_icon_inline_script() );
		wp_localize_script(
			'tax-icon-picker',
			'iconPickerLibUrl',
			array(
				'url' => ADQS_DIRECTORY_ASSETS_URL . '/admin/extra/icon-picker',
			)
		);

		// Load Css
		wp_enqueue_style( 'tax-icon-picker', ADQS_DIRECTORY_ASSETS_URL . '/admin/extra/icon-picker/stylesheets/icon-picker.custom.css', array(), ADQS_DIRECTORY_VERSION );
	}

	/**
	 * Add inline scripts
	 *
	 * @since 1.0.0
	 */
	public function add_icon_inline_script() {
		ob_start();
		?>

		<script>
			document.addEventListener('DOMContentLoaded', function (event) {

				// icon dom select
				let iconView = document.getElementById('icon-view'),
					iconText = document.getElementById('icon-text'),
					iconVal = document.getElementById('<?php echo esc_attr( $this->term_icon_name ); ?>');

				let removeAfterAction = function () {
					iconView.setAttribute('class', '');
					iconText.innerHTML = '<?php echo esc_html__( 'Click to select icon', 'adirectory' ); ?>';
					iconVal.value = '';
				};

				// icon dom Icon Setup
				const uip = new UniversalIconPicker('#btn-select', {
					allowEmpty: false,
					iconLibraries: [
						'font-awesome.min.json',
						'tabler-icons.min.json',
					],

					iconLibrariesCss: [

						'<?php echo esc_url( ADQS_DIRECTORY_ASSETS_URL . '/admin/css/fontawesome-all.min.css' ); ?>',
						'tabler-icons.min.css',
					],
					resetSelector: '#btn-reset',


					onSelect: function (jsonIconData) {
						if (jsonIconData.iconClass) {

							iconView.setAttribute('class', jsonIconData.iconClass);
							iconText.innerHTML = jsonIconData.iconClass;
							iconVal.value = jsonIconData.iconClass;
						}
					},
					onReset: removeAfterAction
				});

				<?php if ( ! isset( $_GET['tag_ID'] ) ) : ?>
					document.getElementById('submit').addEventListener('click', function () {
						let send = XMLHttpRequest.prototype.send
						XMLHttpRequest.prototype.send = function () {
							this.addEventListener('load', function () {
								let termIdXml = this.responseXML.getElementsByTagName('term_id') ? this.responseXML.getElementsByTagName('term_id') : 0;
								if (termIdXml.length && (termIdXml[0].textContent !== "")) {
									removeAfterAction();
								}

							})
							return send.apply(this, arguments)
						}
					});
				<?php endif; ?>

			});
		</script>
		<?php
		return str_replace( array( '<script>', '</script>' ), array( '', '' ), ob_get_clean() );
	}
}
