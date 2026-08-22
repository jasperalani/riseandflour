<?php
/**
 * Menu import — update the Menu block from a PDF.
 *
 * Site Details → Menu import. Upload the print menu as a PDF; Claude reads
 * it and returns the dishes as structured data (the same shape as the Menu
 * block's fields). The page then shows what would change against the menu
 * that's live on the site, and nothing is written until "Apply" is clicked.
 *
 * Why an API call rather than parsing the PDF here: the print menu is a
 * designed layout (two columns, prices floated right, "ADD" lines, italic
 * asides). PHP text extraction returns those runs in unpredictable order and
 * every re-layout would break the heuristics. A model reads the page the way
 * a person does and the result is checked by a person before it's applied.
 *
 * The API key is read from the RF_ANTHROPIC_API_KEY constant (wp-config.php)
 * if defined, otherwise from the setting on the same page.
 *
 * @package Rise_&_Flour
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

const RF_MENU_IMPORT_SLUG          = 'rf-menu-import';
const RF_MENU_IMPORT_DEFAULT_MODEL = 'claude-sonnet-5';
const RF_MENU_IMPORT_MAX_BYTES     = 20 * MB_IN_BYTES;

/* ------------------------------------------------------------------
 * Settings
 * ---------------------------------------------------------------- */

/**
 * The Anthropic API key: constant first, then the saved option.
 *
 * @return string
 */
function rise_and_flour_menu_import_api_key() {
	if ( defined( 'RF_ANTHROPIC_API_KEY' ) && RF_ANTHROPIC_API_KEY ) {
		return (string) RF_ANTHROPIC_API_KEY;
	}
	return (string) get_option( 'rf_anthropic_api_key', '' );
}

/**
 * The model to ask.
 *
 * @return string
 */
function rise_and_flour_menu_import_model() {
	$model = trim( (string) get_option( 'rf_anthropic_model', '' ) );
	return $model ? $model : RF_MENU_IMPORT_DEFAULT_MODEL;
}

/**
 * Register the two settings. An empty key submission keeps the existing one,
 * so the (never pre-filled) field can be left blank on save.
 */
function rise_and_flour_menu_import_settings() {
	register_setting(
		'rf_menu_import',
		'rf_anthropic_api_key',
		array(
			'type'              => 'string',
			'sanitize_callback' => function ( $value ) {
				$value = trim( (string) $value );
				return '' === $value ? get_option( 'rf_anthropic_api_key', '' ) : $value;
			},
		)
	);
	register_setting(
		'rf_menu_import',
		'rf_anthropic_model',
		array(
			'type'              => 'string',
			'sanitize_callback' => 'sanitize_text_field',
		)
	);
}
add_action( 'admin_init', 'rise_and_flour_menu_import_settings' );

/* ------------------------------------------------------------------
 * Admin page
 * ---------------------------------------------------------------- */

/**
 * Add the page beneath Site Details.
 */
function rise_and_flour_menu_import_menu() {
	add_submenu_page(
		'rf-site-details',
		__( 'Menu import', 'rise-and-flour' ),
		__( 'Menu import', 'rise-and-flour' ),
		'edit_pages',
		RF_MENU_IMPORT_SLUG,
		'rise_and_flour_menu_import_page'
	);
}
// After ACF has added the parent page (it registers options pages at 99).
add_action( 'admin_menu', 'rise_and_flour_menu_import_menu', 100 );

/**
 * URL of the import page.
 *
 * @param array $args Extra query args.
 * @return string
 */
function rise_and_flour_menu_import_url( $args = array() ) {
	return add_query_arg( array_merge( array( 'page' => RF_MENU_IMPORT_SLUG ), $args ), admin_url( 'admin.php' ) );
}

/**
 * Pages that contain a Menu block.
 *
 * @return WP_Post[]
 */
function rise_and_flour_menu_import_pages() {
	$pages = get_posts(
		array(
			'post_type'      => 'page',
			'post_status'    => array( 'publish', 'draft', 'private', 'pending' ),
			'posts_per_page' => 50,
			's'              => 'wp:acf/rf-menu',
			'orderby'        => 'title',
			'order'          => 'ASC',
		)
	);

	return array_values(
		array_filter(
			$pages,
			function ( $page ) {
				return has_block( 'acf/rf-menu', $page );
			}
		)
	);
}

/**
 * Per-user transient keys.
 *
 * @param string $what 'job' or 'notice'.
 * @return string
 */
function rise_and_flour_menu_import_key( $what ) {
	return 'rf_menu_import_' . $what . '_' . get_current_user_id();
}

/**
 * Queue a notice for the next page load.
 *
 * @param string $message Text.
 * @param string $type    'success' | 'error' | 'info'.
 */
function rise_and_flour_menu_import_notice( $message, $type = 'error' ) {
	set_transient(
		rise_and_flour_menu_import_key( 'notice' ),
		array(
			'message' => $message,
			'type'    => $type,
		),
		5 * MINUTE_IN_SECONDS
	);
}

/**
 * Render the page: settings, upload form, or the review of a pending import.
 */
function rise_and_flour_menu_import_page() {
	if ( ! current_user_can( 'edit_pages' ) ) {
		wp_die( esc_html__( 'You do not have permission to do this.', 'rise-and-flour' ) );
	}

	$notice = get_transient( rise_and_flour_menu_import_key( 'notice' ) );
	if ( $notice ) {
		delete_transient( rise_and_flour_menu_import_key( 'notice' ) );
	}

	$job = get_transient( rise_and_flour_menu_import_key( 'job' ) );
	?>
	<div class="wrap rf-menu-import">
		<h1><?php esc_html_e( 'Menu import', 'rise-and-flour' ); ?></h1>

		<?php if ( $notice ) : ?>
			<div class="notice notice-<?php echo esc_attr( $notice['type'] ); ?> is-dismissible"><p><?php echo esc_html( $notice['message'] ); ?></p></div>
		<?php endif; ?>

		<?php if ( isset( $_GET['settings-updated'] ) ) : // phpcs:ignore WordPress.Security.NonceVerification.Recommended ?>
			<div class="notice notice-success is-dismissible"><p><?php esc_html_e( 'Settings saved.', 'rise-and-flour' ); ?></p></div>
		<?php endif; ?>

		<?php
		if ( $job ) {
			rise_and_flour_menu_import_render_review( $job );
		} else {
			rise_and_flour_menu_import_render_upload();
		}

		if ( current_user_can( 'manage_options' ) ) {
			rise_and_flour_menu_import_render_settings();
		}
		?>
	</div>

	<style>
		.rf-menu-import .card { max-width: 900px; }
		.rf-menu-import table.widefat td { vertical-align: top; }
		.rf-menu-import .rf-old { color: #8a2d2d; text-decoration: line-through; }
		.rf-menu-import .rf-new { color: #1d6b2b; }
		.rf-menu-import .rf-muted { color: #666; }
		.rf-menu-import .rf-field { font-size: 11px; text-transform: uppercase; letter-spacing: .06em; color: #666; display: block; margin-top: 6px; }
		.rf-menu-import .rf-field:first-child { margin-top: 0; }
		.rf-menu-import .rf-counts span { display: inline-block; margin-right: 18px; }
		.rf-menu-import pre { white-space: pre-wrap; margin: 0; font: inherit; }
	</style>
	<?php
}

/**
 * Step 1: the upload form.
 */
function rise_and_flour_menu_import_render_upload() {
	$pages   = rise_and_flour_menu_import_pages();
	$has_key = (bool) rise_and_flour_menu_import_api_key();
	?>
	<div class="card">
		<h2><?php esc_html_e( 'Update the menu from a PDF', 'rise-and-flour' ); ?></h2>
		<p>
			<?php esc_html_e( 'Upload the print menu. Claude reads it and the next screen shows every dish that would be added, removed or changed compared with the menu on the site. Nothing is saved until you apply it.', 'rise-and-flour' ); ?>
		</p>

		<?php if ( ! $has_key ) : ?>
			<div class="notice notice-warning inline"><p><?php esc_html_e( 'Add an Anthropic API key below before importing.', 'rise-and-flour' ); ?></p></div>
		<?php elseif ( ! $pages ) : ?>
			<div class="notice notice-warning inline"><p><?php esc_html_e( 'No page contains a Menu block yet. Add the Menu block to a page first.', 'rise-and-flour' ); ?></p></div>
		<?php else : ?>
			<form method="post" action="<?php echo esc_url( admin_url( 'admin-post.php' ) ); ?>" enctype="multipart/form-data">
				<?php wp_nonce_field( 'rf_menu_import_analyse' ); ?>
				<input type="hidden" name="action" value="rf_menu_import_analyse">
				<table class="form-table" role="presentation">
					<tr>
						<th scope="row"><label for="rf-menu-pdf"><?php esc_html_e( 'Menu PDF', 'rise-and-flour' ); ?></label></th>
						<td>
							<input type="file" id="rf-menu-pdf" name="menu_pdf" accept="application/pdf,.pdf" required>
							<p class="description"><?php esc_html_e( 'Up to 20 MB.', 'rise-and-flour' ); ?></p>
						</td>
					</tr>
					<tr>
						<th scope="row"><label for="rf-menu-page"><?php esc_html_e( 'Page to update', 'rise-and-flour' ); ?></label></th>
						<td>
							<select id="rf-menu-page" name="page_id">
								<?php foreach ( $pages as $page ) : ?>
									<option value="<?php echo (int) $page->ID; ?>"><?php echo esc_html( $page->post_title ); ?></option>
								<?php endforeach; ?>
							</select>
						</td>
					</tr>
				</table>
				<?php submit_button( __( 'Read the PDF', 'rise-and-flour' ), 'primary', 'submit', true, array( 'id' => 'rf-menu-analyse' ) ); ?>
				<p class="description"><?php esc_html_e( 'Reading takes 20–60 seconds.', 'rise-and-flour' ); ?></p>
			</form>
		<?php endif; ?>
	</div>
	<?php
}

/**
 * Step 2: the review of what would change.
 *
 * @param array $job The pending import.
 */
function rise_and_flour_menu_import_render_review( $job ) {
	$diff = $job['diff'];
	$page = get_post( $job['page_id'] );

	$inline = array(
		'em'     => array(),
		'strong' => array(),
	);

	$item_line = function ( $item ) use ( $inline ) {
		$out = '';
		if ( '' !== $item['price'] ) {
			$out .= '<span class="rf-field">' . esc_html__( 'Price', 'rise-and-flour' ) . '</span>' . esc_html( $item['price'] );
		}
		if ( $item['vegetarian'] ) {
			$out .= '<span class="rf-field">' . esc_html__( 'Vegetarian', 'rise-and-flour' ) . '</span>' . esc_html__( 'Yes', 'rise-and-flour' );
		}
		if ( '' !== $item['description'] ) {
			$out .= '<span class="rf-field">' . esc_html__( 'Description', 'rise-and-flour' ) . '</span>' . wp_kses( $item['description'], $inline );
		}
		if ( '' !== $item['extras'] ) {
			$out .= '<span class="rf-field">' . esc_html__( 'Add', 'rise-and-flour' ) . '</span>' . esc_html( $item['extras'] );
		}
		if ( '' !== $item['note'] ) {
			$out .= '<span class="rf-field">' . esc_html__( 'Note', 'rise-and-flour' ) . '</span>' . esc_html( $item['note'] );
		}
		return $out;
	};

	$nothing = empty( $diff['added'] ) && empty( $diff['removed'] ) && empty( $diff['changed'] ) && ! $diff['hours_changed'] && ! $diff['notes_changed'] && empty( $diff['sections_added'] ) && empty( $diff['sections_removed'] );
	?>
	<div class="card">
		<h2>
			<?php
			printf(
				/* translators: 1: PDF file name, 2: page title. */
				esc_html__( 'Changes from %1$s for the page “%2$s”', 'rise-and-flour' ),
				esc_html( $job['pdf_name'] ),
				esc_html( $page ? $page->post_title : '#' . $job['page_id'] )
			);
			?>
		</h2>

		<p class="rf-counts">
			<span><strong><?php echo (int) count( $diff['added'] ); ?></strong> <?php esc_html_e( 'added', 'rise-and-flour' ); ?></span>
			<span><strong><?php echo (int) count( $diff['removed'] ); ?></strong> <?php esc_html_e( 'removed', 'rise-and-flour' ); ?></span>
			<span><strong><?php echo (int) count( $diff['changed'] ); ?></strong> <?php esc_html_e( 'changed', 'rise-and-flour' ); ?></span>
			<span><strong><?php echo (int) $diff['unchanged']; ?></strong> <?php esc_html_e( 'unchanged', 'rise-and-flour' ); ?></span>
		</p>

		<?php if ( $nothing ) : ?>
			<div class="notice notice-info inline"><p><?php esc_html_e( 'The PDF matches the menu already on the site — nothing to apply.', 'rise-and-flour' ); ?></p></div>
		<?php endif; ?>

		<?php if ( ! empty( $diff['sections_added'] ) || ! empty( $diff['sections_removed'] ) ) : ?>
			<h3><?php esc_html_e( 'Sections', 'rise-and-flour' ); ?></h3>
			<ul>
				<?php foreach ( $diff['sections_added'] as $title ) : ?>
					<li class="rf-new">+ <?php echo esc_html( $title ); ?></li>
				<?php endforeach; ?>
				<?php foreach ( $diff['sections_removed'] as $title ) : ?>
					<li class="rf-old"><?php echo esc_html( $title ); ?></li>
				<?php endforeach; ?>
			</ul>
		<?php endif; ?>

		<?php if ( ! empty( $diff['added'] ) ) : ?>
			<h3><?php esc_html_e( 'Added', 'rise-and-flour' ); ?></h3>
			<table class="widefat striped">
				<thead><tr><th><?php esc_html_e( 'Dish', 'rise-and-flour' ); ?></th><th><?php esc_html_e( 'Section', 'rise-and-flour' ); ?></th><th><?php esc_html_e( 'Details', 'rise-and-flour' ); ?></th></tr></thead>
				<tbody>
					<?php foreach ( $diff['added'] as $row ) : ?>
						<tr>
							<td class="rf-new"><strong><?php echo esc_html( $row['item']['name'] ); ?></strong></td>
							<td><?php echo esc_html( $row['section'] ); ?></td>
							<td><?php echo $item_line( $row['item'] ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></td>
						</tr>
					<?php endforeach; ?>
				</tbody>
			</table>
		<?php endif; ?>

		<?php if ( ! empty( $diff['removed'] ) ) : ?>
			<h3><?php esc_html_e( 'Removed', 'rise-and-flour' ); ?></h3>
			<table class="widefat striped">
				<thead><tr><th><?php esc_html_e( 'Dish', 'rise-and-flour' ); ?></th><th><?php esc_html_e( 'Section', 'rise-and-flour' ); ?></th><th><?php esc_html_e( 'Details', 'rise-and-flour' ); ?></th></tr></thead>
				<tbody>
					<?php foreach ( $diff['removed'] as $row ) : ?>
						<tr>
							<td class="rf-old"><strong><?php echo esc_html( $row['item']['name'] ); ?></strong></td>
							<td><?php echo esc_html( $row['section'] ); ?></td>
							<td class="rf-muted"><?php echo $item_line( $row['item'] ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></td>
						</tr>
					<?php endforeach; ?>
				</tbody>
			</table>
		<?php endif; ?>

		<?php if ( ! empty( $diff['changed'] ) ) : ?>
			<h3><?php esc_html_e( 'Changed', 'rise-and-flour' ); ?></h3>
			<table class="widefat striped">
				<thead><tr><th><?php esc_html_e( 'Dish', 'rise-and-flour' ); ?></th><th><?php esc_html_e( 'Field', 'rise-and-flour' ); ?></th><th><?php esc_html_e( 'On the site now', 'rise-and-flour' ); ?></th><th><?php esc_html_e( 'In the PDF', 'rise-and-flour' ); ?></th></tr></thead>
				<tbody>
					<?php foreach ( $diff['changed'] as $row ) : ?>
						<?php foreach ( $row['fields'] as $field => $pair ) : ?>
							<tr>
								<td><strong><?php echo esc_html( $row['item']['name'] ); ?></strong></td>
								<td><?php echo esc_html( $field ); ?></td>
								<td class="rf-old"><?php echo wp_kses( (string) $pair[0], $inline ); ?></td>
								<td class="rf-new"><?php echo wp_kses( (string) $pair[1], $inline ); ?></td>
							</tr>
						<?php endforeach; ?>
					<?php endforeach; ?>
				</tbody>
			</table>
		<?php endif; ?>

		<?php if ( $diff['hours_changed'] || $diff['notes_changed'] ) : ?>
			<h3><?php esc_html_e( 'Hours and notes', 'rise-and-flour' ); ?></h3>
			<table class="widefat striped">
				<thead><tr><th></th><th><?php esc_html_e( 'On the site now', 'rise-and-flour' ); ?></th><th><?php esc_html_e( 'In the PDF', 'rise-and-flour' ); ?></th></tr></thead>
				<tbody>
					<?php if ( $diff['hours_changed'] ) : ?>
						<tr>
							<td><strong><?php esc_html_e( 'Hours', 'rise-and-flour' ); ?></strong></td>
							<td class="rf-old"><pre><?php echo wp_kses( $job['current']['hours'], $inline ); ?></pre></td>
							<td class="rf-new"><pre><?php echo wp_kses( $job['proposed']['hours'], $inline ); ?></pre></td>
						</tr>
					<?php endif; ?>
					<?php if ( $diff['notes_changed'] ) : ?>
						<tr>
							<td><strong><?php esc_html_e( 'Notes', 'rise-and-flour' ); ?></strong></td>
							<td class="rf-old"><pre><?php echo esc_html( $job['current']['notes'] ); ?></pre></td>
							<td class="rf-new"><pre><?php echo esc_html( $job['proposed']['notes'] ); ?></pre></td>
						</tr>
					<?php endif; ?>
				</tbody>
			</table>
		<?php endif; ?>

		<form method="post" action="<?php echo esc_url( admin_url( 'admin-post.php' ) ); ?>" style="margin-top:24px">
			<?php wp_nonce_field( 'rf_menu_import_apply' ); ?>
			<input type="hidden" name="action" value="rf_menu_import_apply">
			<p>
				<label>
					<input type="checkbox" name="attach_pdf" value="1" <?php checked( file_exists( $job['pdf_path'] ) ); ?> <?php disabled( ! file_exists( $job['pdf_path'] ) ); ?>>
					<?php esc_html_e( 'Also add this PDF to the media library and use it as the “Download the menu” link.', 'rise-and-flour' ); ?>
				</label>
			</p>
			<p>
				<?php if ( ! $nothing ) : ?>
					<?php submit_button( __( 'Apply to the site', 'rise-and-flour' ), 'primary', 'submit', false ); ?>
				<?php endif; ?>
				<a class="button" href="<?php echo esc_url( wp_nonce_url( admin_url( 'admin-post.php?action=rf_menu_import_discard' ), 'rf_menu_import_discard' ) ); ?>"><?php esc_html_e( 'Discard', 'rise-and-flour' ); ?></a>
			</p>
			<p class="description"><?php esc_html_e( 'Applying replaces the sections and dishes on the page with what the PDF says. The heading, intro and spacing are kept. You can always edit the page afterwards.', 'rise-and-flour' ); ?></p>
		</form>
	</div>
	<?php
}

/**
 * The settings box (administrators only).
 */
function rise_and_flour_menu_import_render_settings() {
	$constant = defined( 'RF_ANTHROPIC_API_KEY' ) && RF_ANTHROPIC_API_KEY;
	$saved    = (string) get_option( 'rf_anthropic_api_key', '' );
	?>
	<div class="card">
		<h2><?php esc_html_e( 'Settings', 'rise-and-flour' ); ?></h2>
		<form method="post" action="<?php echo esc_url( admin_url( 'options.php' ) ); ?>">
			<?php settings_fields( 'rf_menu_import' ); ?>
			<table class="form-table" role="presentation">
				<tr>
					<th scope="row"><label for="rf-api-key"><?php esc_html_e( 'Anthropic API key', 'rise-and-flour' ); ?></label></th>
					<td>
						<?php if ( $constant ) : ?>
							<p><code>RF_ANTHROPIC_API_KEY</code> <?php esc_html_e( 'is set in wp-config.php and takes precedence.', 'rise-and-flour' ); ?></p>
						<?php else : ?>
							<input type="password" id="rf-api-key" name="rf_anthropic_api_key" class="regular-text" autocomplete="off" placeholder="<?php echo $saved ? esc_attr( '••••••••' . substr( $saved, -4 ) ) : 'sk-ant-…'; ?>">
							<p class="description">
								<?php esc_html_e( 'From console.anthropic.com. Leave blank to keep the saved key. Prefer defining RF_ANTHROPIC_API_KEY in wp-config.php so the key stays out of the database.', 'rise-and-flour' ); ?>
							</p>
						<?php endif; ?>
					</td>
				</tr>
				<tr>
					<th scope="row"><label for="rf-model"><?php esc_html_e( 'Model', 'rise-and-flour' ); ?></label></th>
					<td>
						<input type="text" id="rf-model" name="rf_anthropic_model" class="regular-text" value="<?php echo esc_attr( get_option( 'rf_anthropic_model', '' ) ); ?>" placeholder="<?php echo esc_attr( RF_MENU_IMPORT_DEFAULT_MODEL ); ?>">
						<p class="description"><?php esc_html_e( 'Leave blank for the default.', 'rise-and-flour' ); ?></p>
					</td>
				</tr>
			</table>
			<?php submit_button( __( 'Save settings', 'rise-and-flour' ), 'secondary' ); ?>
		</form>
	</div>
	<?php
}

/* ------------------------------------------------------------------
 * Handlers
 * ---------------------------------------------------------------- */

/**
 * Step 1 handler: validate the upload, ask Claude, store the proposal.
 */
function rise_and_flour_menu_import_analyse() {
	if ( ! current_user_can( 'edit_pages' ) ) {
		wp_die( esc_html__( 'You do not have permission to do this.', 'rise-and-flour' ) );
	}
	check_admin_referer( 'rf_menu_import_analyse' );

	$back = function ( $message ) {
		rise_and_flour_menu_import_notice( $message, 'error' );
		wp_safe_redirect( rise_and_flour_menu_import_url() );
		exit;
	};

	$page_id = isset( $_POST['page_id'] ) ? absint( $_POST['page_id'] ) : 0;
	$page    = $page_id ? get_post( $page_id ) : null;
	if ( ! $page || ! has_block( 'acf/rf-menu', $page ) || ! current_user_can( 'edit_post', $page_id ) ) {
		$back( __( 'That page has no Menu block.', 'rise-and-flour' ) );
	}

	// phpcs:ignore WordPress.Security.ValidatedSanitizedInput.InputNotSanitized -- file array, validated below.
	$file = isset( $_FILES['menu_pdf'] ) ? $_FILES['menu_pdf'] : null;
	if ( ! $file || ! isset( $file['error'] ) || UPLOAD_ERR_OK !== (int) $file['error'] || empty( $file['tmp_name'] ) ) {
		$back( __( 'No PDF was uploaded — check the file is under the server upload limit.', 'rise-and-flour' ) );
	}
	if ( (int) $file['size'] > RF_MENU_IMPORT_MAX_BYTES ) {
		$back( __( 'That PDF is over 20 MB.', 'rise-and-flour' ) );
	}

	$finfo = wp_check_filetype_and_ext( $file['tmp_name'], $file['name'], array( 'pdf' => 'application/pdf' ) );
	if ( 'application/pdf' !== $finfo['type'] ) {
		$back( __( 'That file is not a PDF.', 'rise-and-flour' ) );
	}

	$pdf = file_get_contents( $file['tmp_name'] ); // phpcs:ignore WordPress.WP.AlternativeFunctions.file_get_contents_file_get_contents
	if ( ! $pdf ) {
		$back( __( 'Could not read the uploaded file.', 'rise-and-flour' ) );
	}

	$current = rise_and_flour_menu_import_read_page( $page_id );
	if ( is_wp_error( $current ) ) {
		$back( $current->get_error_message() );
	}

	$proposed = rise_and_flour_menu_import_ask_claude( $pdf, $current['menu'] );
	if ( is_wp_error( $proposed ) ) {
		$back( $proposed->get_error_message() );
	}

	// Keep the PDF until the apply step so it can be attached as the download.
	$pdf_path = wp_tempnam( 'rf-menu-import.pdf' );
	if ( $pdf_path ) {
		file_put_contents( $pdf_path, $pdf ); // phpcs:ignore WordPress.WP.AlternativeFunctions.file_system_operations_file_put_contents
	}

	set_transient(
		rise_and_flour_menu_import_key( 'job' ),
		array(
			'page_id'  => $page_id,
			'pdf_name' => sanitize_file_name( $file['name'] ),
			'pdf_path' => $pdf_path ? $pdf_path : '',
			'current'  => $current['menu'],
			'proposed' => $proposed,
			'diff'     => rise_and_flour_menu_import_diff( $current['menu'], $proposed ),
		),
		HOUR_IN_SECONDS
	);

	wp_safe_redirect( rise_and_flour_menu_import_url() );
	exit;
}
add_action( 'admin_post_rf_menu_import_analyse', 'rise_and_flour_menu_import_analyse' );

/**
 * Step 2 handler: write the proposal into the page's Menu block.
 */
function rise_and_flour_menu_import_apply() {
	if ( ! current_user_can( 'edit_pages' ) ) {
		wp_die( esc_html__( 'You do not have permission to do this.', 'rise-and-flour' ) );
	}
	check_admin_referer( 'rf_menu_import_apply' );

	$job = get_transient( rise_and_flour_menu_import_key( 'job' ) );
	if ( ! $job ) {
		rise_and_flour_menu_import_notice( __( 'That import has expired — upload the PDF again.', 'rise-and-flour' ) );
		wp_safe_redirect( rise_and_flour_menu_import_url() );
		exit;
	}

	if ( ! current_user_can( 'edit_post', $job['page_id'] ) ) {
		wp_die( esc_html__( 'You do not have permission to edit that page.', 'rise-and-flour' ) );
	}

	$attachment_id = 0;
	if ( ! empty( $_POST['attach_pdf'] ) && $job['pdf_path'] && file_exists( $job['pdf_path'] ) ) {
		$attachment_id = rise_and_flour_menu_import_attach_pdf( $job['pdf_path'], $job['pdf_name'], $job['page_id'] );
	}

	$result = rise_and_flour_menu_import_write_page( $job['page_id'], $job['proposed'], $attachment_id );

	if ( $job['pdf_path'] && file_exists( $job['pdf_path'] ) ) {
		wp_delete_file( $job['pdf_path'] );
	}
	delete_transient( rise_and_flour_menu_import_key( 'job' ) );

	if ( is_wp_error( $result ) ) {
		rise_and_flour_menu_import_notice( $result->get_error_message() );
	} else {
		$diff = $job['diff'];
		rise_and_flour_menu_import_notice(
			sprintf(
				/* translators: 1: added, 2: removed, 3: changed, 4: page title. */
				__( 'Menu updated: %1$d added, %2$d removed, %3$d changed on “%4$s”.', 'rise-and-flour' ),
				count( $diff['added'] ),
				count( $diff['removed'] ),
				count( $diff['changed'] ),
				get_the_title( $job['page_id'] )
			),
			'success'
		);
	}

	wp_safe_redirect( rise_and_flour_menu_import_url() );
	exit;
}
add_action( 'admin_post_rf_menu_import_apply', 'rise_and_flour_menu_import_apply' );

/**
 * Throw the pending proposal away.
 */
function rise_and_flour_menu_import_discard() {
	if ( ! current_user_can( 'edit_pages' ) ) {
		wp_die( esc_html__( 'You do not have permission to do this.', 'rise-and-flour' ) );
	}
	check_admin_referer( 'rf_menu_import_discard' );

	$job = get_transient( rise_and_flour_menu_import_key( 'job' ) );
	if ( $job && ! empty( $job['pdf_path'] ) && file_exists( $job['pdf_path'] ) ) {
		wp_delete_file( $job['pdf_path'] );
	}
	delete_transient( rise_and_flour_menu_import_key( 'job' ) );

	rise_and_flour_menu_import_notice( __( 'Import discarded — nothing was changed.', 'rise-and-flour' ), 'info' );
	wp_safe_redirect( rise_and_flour_menu_import_url() );
	exit;
}
add_action( 'admin_post_rf_menu_import_discard', 'rise_and_flour_menu_import_discard' );

/* ------------------------------------------------------------------
 * Block data <-> menu structure
 * ---------------------------------------------------------------- */

/**
 * An empty dish with every key present.
 *
 * @param array $item Partial item.
 * @return array
 */
function rise_and_flour_menu_import_item( $item = array() ) {
	$item = wp_parse_args(
		$item,
		array(
			'name'        => '',
			'price'       => '',
			'vegetarian'  => false,
			'description' => '',
			'extras'      => '',
			'note'        => '',
		)
	);
	foreach ( array( 'name', 'price', 'description', 'extras', 'note' ) as $key ) {
		$item[ $key ] = trim( (string) $item[ $key ] );
	}
	$item['vegetarian'] = (bool) $item['vegetarian'];
	return $item;
}

/**
 * Turn the block's flat ACF data into a nested menu array.
 *
 * @param array $data The block's `data` attribute.
 * @return array {sections: [{title, note, items: [...]}], hours_heading, hours, notes}
 */
function rise_and_flour_menu_import_data_to_menu( $data ) {
	$menu = array(
		'sections'      => array(),
		'hours_heading' => isset( $data['hours_heading'] ) ? (string) $data['hours_heading'] : '',
		'hours'         => isset( $data['hours'] ) ? (string) $data['hours'] : '',
		'notes'         => isset( $data['notes'] ) ? (string) $data['notes'] : '',
	);

	$sections = isset( $data['sections'] ) ? (int) $data['sections'] : 0;
	for ( $s = 0; $s < $sections; $s++ ) {
		$p     = "sections_{$s}_";
		$items = array();
		$count = isset( $data[ $p . 'items' ] ) ? (int) $data[ $p . 'items' ] : 0;
		for ( $i = 0; $i < $count; $i++ ) {
			$q       = $p . "items_{$i}_";
			$items[] = rise_and_flour_menu_import_item(
				array(
					'name'        => $data[ $q . 'name' ] ?? '',
					'price'       => $data[ $q . 'price' ] ?? '',
					'vegetarian'  => ! empty( $data[ $q . 'vegetarian' ] ),
					'description' => $data[ $q . 'description' ] ?? '',
					'extras'      => $data[ $q . 'extras' ] ?? '',
					'note'        => $data[ $q . 'note' ] ?? '',
				)
			);
		}
		$menu['sections'][] = array(
			'title' => trim( (string) ( $data[ $p . 'title' ] ?? '' ) ),
			'note'  => trim( (string) ( $data[ $p . 'note' ] ?? '' ) ),
			'items' => $items,
		);
	}

	return $menu;
}

/**
 * Write a nested menu back over the block's flat ACF data.
 *
 * Every sections_* key is replaced; heading, intro, spacing and the rest
 * are left alone.
 *
 * @param array $data          Existing block data.
 * @param array $menu          Menu structure.
 * @param int   $attachment_id Optional PDF attachment to set as the download.
 * @return array
 */
function rise_and_flour_menu_import_menu_to_data( $data, $menu, $attachment_id = 0 ) {
	foreach ( array_keys( $data ) as $key ) {
		if ( preg_match( '/^_?sections(_|$)/', $key ) ) {
			unset( $data[ $key ] );
		}
	}

	$field = function ( $name, $value, $key ) use ( &$data ) {
		$data[ $name ]       = $value;
		$data[ '_' . $name ] = $key;
	};

	foreach ( array_values( $menu['sections'] ) as $s => $section ) {
		$p = "sections_{$s}_";
		$field( $p . 'title', $section['title'], 'field_rf_menu_section_title' );
		$field( $p . 'note', $section['note'], 'field_rf_menu_section_note' );

		foreach ( array_values( $section['items'] ) as $i => $item ) {
			$q = $p . "items_{$i}_";
			$field( $q . 'name', $item['name'], 'field_rf_menu_item_name' );
			$field( $q . 'price', $item['price'], 'field_rf_menu_item_price' );
			$field( $q . 'vegetarian', $item['vegetarian'] ? '1' : '0', 'field_rf_menu_item_veg' );
			$field( $q . 'description', $item['description'], 'field_rf_menu_item_description' );
			$field( $q . 'extras', $item['extras'], 'field_rf_menu_item_extras' );
			$field( $q . 'note', $item['note'], 'field_rf_menu_item_note' );
		}
		$field( $p . 'items', count( $section['items'] ), 'field_rf_menu_items' );
	}
	$field( 'sections', count( $menu['sections'] ), 'field_rf_menu_sections' );

	if ( '' !== $menu['hours'] ) {
		$field( 'hours', $menu['hours'], 'field_rf_menu_hours' );
	}
	if ( '' !== $menu['hours_heading'] ) {
		$field( 'hours_heading', $menu['hours_heading'], 'field_rf_menu_hours_heading' );
	}
	if ( '' !== $menu['notes'] ) {
		$field( 'notes', $menu['notes'], 'field_rf_menu_notes' );
	}
	if ( $attachment_id ) {
		$field( 'file', (int) $attachment_id, 'field_rf_menu_file' );
	}

	return $data;
}

/**
 * Locate the first Menu block in a page.
 *
 * @param int $page_id Page ID.
 * @return array|WP_Error {blocks, index, menu}
 */
function rise_and_flour_menu_import_read_page( $page_id ) {
	$page = get_post( $page_id );
	if ( ! $page ) {
		return new WP_Error( 'rf_menu_no_page', __( 'Page not found.', 'rise-and-flour' ) );
	}

	$blocks = parse_blocks( $page->post_content );
	foreach ( $blocks as $index => $block ) {
		if ( 'acf/rf-menu' === $block['blockName'] ) {
			$data = isset( $block['attrs']['data'] ) && is_array( $block['attrs']['data'] ) ? $block['attrs']['data'] : array();
			return array(
				'blocks' => $blocks,
				'index'  => $index,
				'menu'   => rise_and_flour_menu_import_data_to_menu( $data ),
			);
		}
	}

	return new WP_Error( 'rf_menu_no_block', __( 'That page has no Menu block at the top level.', 'rise-and-flour' ) );
}

/**
 * Replace the page's Menu block data and save.
 *
 * @param int   $page_id       Page ID.
 * @param array $menu          Menu structure.
 * @param int   $attachment_id Optional PDF attachment.
 * @return true|WP_Error
 */
function rise_and_flour_menu_import_write_page( $page_id, $menu, $attachment_id = 0 ) {
	$found = rise_and_flour_menu_import_read_page( $page_id );
	if ( is_wp_error( $found ) ) {
		return $found;
	}

	$blocks = $found['blocks'];
	$block  =& $blocks[ $found['index'] ];

	$data = isset( $block['attrs']['data'] ) && is_array( $block['attrs']['data'] ) ? $block['attrs']['data'] : array();
	if ( empty( $block['attrs']['name'] ) ) {
		$block['attrs']['name'] = 'acf/rf-menu';
	}
	if ( empty( $block['attrs']['mode'] ) ) {
		$block['attrs']['mode'] = 'preview';
	}
	$block['attrs']['data'] = rise_and_flour_menu_import_menu_to_data( $data, $menu, $attachment_id );

	// wp_update_post() unslashes, so the block markup has to go in slashed.
	$result = wp_update_post(
		wp_slash(
			array(
				'ID'           => $page_id,
				'post_content' => serialize_blocks( $blocks ),
			)
		),
		true
	);

	return is_wp_error( $result ) ? $result : true;
}

/**
 * Add the uploaded PDF to the media library.
 *
 * @param string $path    Temp file path.
 * @param string $name    Original file name.
 * @param int    $page_id Page to attach it to.
 * @return int Attachment ID, 0 on failure.
 */
function rise_and_flour_menu_import_attach_pdf( $path, $name, $page_id ) {
	require_once ABSPATH . 'wp-admin/includes/file.php';
	require_once ABSPATH . 'wp-admin/includes/media.php';
	require_once ABSPATH . 'wp-admin/includes/image.php';

	$copy = wp_tempnam( $name );
	if ( ! $copy || ! copy( $path, $copy ) ) {
		return 0;
	}

	$id = media_handle_sideload(
		array(
			'name'     => $name ? $name : 'menu.pdf',
			'tmp_name' => $copy,
			'type'     => 'application/pdf',
			'error'    => 0,
			'size'     => filesize( $copy ),
		),
		$page_id,
		__( 'Menu', 'rise-and-flour' )
	);

	if ( is_wp_error( $id ) ) {
		if ( file_exists( $copy ) ) {
			wp_delete_file( $copy );
		}
		return 0;
	}

	return (int) $id;
}

/* ------------------------------------------------------------------
 * Diff
 * ---------------------------------------------------------------- */

/**
 * A loose key for matching dishes between versions.
 *
 * @param string $name Dish name.
 * @return string
 */
function rise_and_flour_menu_import_slug( $name ) {
	$name = remove_accents( wp_strip_all_tags( (string) $name ) );
	$name = strtolower( $name );
	$name = str_replace( array( '&', ' and ' ), ' and ', $name );
	$name = preg_replace( '/[^a-z0-9]+/', ' ', $name );
	return trim( preg_replace( '/\s+/', ' ', $name ) );
}

/**
 * Compare two menus dish by dish.
 *
 * @param array $old Current menu.
 * @param array $new Proposed menu.
 * @return array
 */
function rise_and_flour_menu_import_diff( $old, $new ) {
	$flatten = function ( $menu ) {
		$out = array();
		foreach ( $menu['sections'] as $section ) {
			foreach ( $section['items'] as $item ) {
				$key = rise_and_flour_menu_import_slug( $item['name'] );
				if ( '' === $key ) {
					continue;
				}
				$out[ $key ] = array(
					'section' => $section['title'],
					'item'    => $item,
				);
			}
		}
		return $out;
	};

	$a = $flatten( $old );
	$b = $flatten( $new );

	$labels = array(
		'price'       => __( 'Price', 'rise-and-flour' ),
		'vegetarian'  => __( 'Vegetarian', 'rise-and-flour' ),
		'description' => __( 'Description', 'rise-and-flour' ),
		'extras'      => __( 'Add', 'rise-and-flour' ),
		'note'        => __( 'Note', 'rise-and-flour' ),
		'section'     => __( 'Section', 'rise-and-flour' ),
	);

	$diff = array(
		'added'            => array(),
		'removed'          => array(),
		'changed'          => array(),
		'unchanged'        => 0,
		'sections_added'   => array(),
		'sections_removed' => array(),
		'hours_changed'    => trim( (string) $old['hours'] ) !== trim( (string) $new['hours'] ) && '' !== trim( (string) $new['hours'] ),
		'notes_changed'    => trim( (string) $old['notes'] ) !== trim( (string) $new['notes'] ) && '' !== trim( (string) $new['notes'] ),
	);

	foreach ( $b as $key => $row ) {
		if ( ! isset( $a[ $key ] ) ) {
			$diff['added'][] = $row;
			continue;
		}

		$fields = array();
		foreach ( array( 'price', 'description', 'extras', 'note' ) as $f ) {
			if ( trim( $a[ $key ]['item'][ $f ] ) !== trim( $row['item'][ $f ] ) ) {
				$fields[ $labels[ $f ] ] = array( $a[ $key ]['item'][ $f ], $row['item'][ $f ] );
			}
		}
		if ( $a[ $key ]['item']['vegetarian'] !== $row['item']['vegetarian'] ) {
			$fields[ $labels['vegetarian'] ] = array(
				$a[ $key ]['item']['vegetarian'] ? __( 'Yes', 'rise-and-flour' ) : __( 'No', 'rise-and-flour' ),
				$row['item']['vegetarian'] ? __( 'Yes', 'rise-and-flour' ) : __( 'No', 'rise-and-flour' ),
			);
		}
		if ( $a[ $key ]['section'] !== $row['section'] ) {
			$fields[ $labels['section'] ] = array( $a[ $key ]['section'], $row['section'] );
		}

		if ( $fields ) {
			$diff['changed'][] = array(
				'item'   => $row['item'],
				'fields' => $fields,
			);
		} else {
			$diff['unchanged']++;
		}
	}

	foreach ( $a as $key => $row ) {
		if ( ! isset( $b[ $key ] ) ) {
			$diff['removed'][] = $row;
		}
	}

	$old_titles = array_map( 'strval', wp_list_pluck( $old['sections'], 'title' ) );
	$new_titles = array_map( 'strval', wp_list_pluck( $new['sections'], 'title' ) );

	$diff['sections_added']   = array_values( array_diff( $new_titles, $old_titles ) );
	$diff['sections_removed'] = array_values( array_diff( $old_titles, $new_titles ) );

	return $diff;
}

/* ------------------------------------------------------------------
 * Claude
 * ---------------------------------------------------------------- */

/**
 * Ask Claude to read the PDF into the Menu block's structure.
 *
 * Uses a forced tool call so the answer comes back as validated JSON in
 * exactly the shape the block needs — no free-text parsing.
 *
 * @param string $pdf     Raw PDF bytes.
 * @param array  $current The menu currently on the site, for reference.
 * @return array|WP_Error Menu structure.
 */
function rise_and_flour_menu_import_ask_claude( $pdf, $current ) {
	$key = rise_and_flour_menu_import_api_key();
	if ( ! $key ) {
		return new WP_Error( 'rf_menu_no_key', __( 'No Anthropic API key is set.', 'rise-and-flour' ) );
	}

	$item_schema = array(
		'type'       => 'object',
		'required'   => array( 'name' ),
		'properties' => array(
			'name'        => array(
				'type'        => 'string',
				'description' => 'Dish name exactly as printed, without any (V) marker.',
			),
			'price'       => array(
				'type'        => 'string',
				'description' => 'Plain number as printed, e.g. "9.00" or "14.50". No currency sign. Empty if no price.',
			),
			'vegetarian'  => array(
				'type'        => 'boolean',
				'description' => 'True when the dish is marked (V).',
			),
			'description' => array(
				'type'        => 'string',
				'description' => 'The ingredients line as printed. <em>…</em> is allowed only for short italic asides such as "with crisps" or "(contains cashew nuts)". No other markup.',
			),
			'extras'      => array(
				'type'        => 'string',
				'description' => 'Optional add-ons from an ADD line, joined with " · ", keeping their prices, e.g. "Smoked bacon 4 · Halloumi 3.5". Empty if none.',
			),
			'note'        => array(
				'type'        => 'string',
				'description' => 'A short extra line such as a veg option or "Gluten free bread 50p". Empty if none.',
			),
		),
	);

	$tool = array(
		'name'         => 'save_menu',
		'description'  => 'Record the café menu read from the PDF.',
		'input_schema' => array(
			'type'       => 'object',
			'required'   => array( 'sections' ),
			'properties' => array(
				'sections'      => array(
					'type'  => 'array',
					'items' => array(
						'type'       => 'object',
						'required'   => array( 'title', 'items' ),
						'properties' => array(
							'title' => array(
								'type'        => 'string',
								'description' => 'Section heading as printed, in sentence case, e.g. "Served all day".',
							),
							'note'  => array(
								'type'        => 'string',
								'description' => 'Optional line under the section heading.',
							),
							'items' => array(
								'type'  => 'array',
								'items' => $item_schema,
							),
						),
					),
				),
				'hours_heading' => array(
					'type'        => 'string',
					'description' => 'Heading of the opening-hours panel if there is one, e.g. "Hours".',
				),
				'hours_groups'  => array(
					'type'        => 'array',
					'description' => 'Opening hours, grouped as printed, e.g. one group "Café open" and one "Cooked food served". Empty if the PDF has no hours.',
					'items'       => array(
						'type'       => 'object',
						'required'   => array( 'lines' ),
						'properties' => array(
							'label' => array( 'type' => 'string' ),
							'lines' => array(
								'type'        => 'array',
								'items'       => array( 'type' => 'string' ),
								'description' => 'One line per range, tidied to the form "Mon – Fri, 8:30am – 3pm".',
							),
						),
					),
				),
				'notes'         => array(
					'type'        => 'array',
					'items'       => array( 'type' => 'string' ),
					'description' => 'Closing lines such as "Eat in or takeaway" or "Please ask for today\'s specials". Not the address or phone number.',
				),
			),
		),
	);

	$instructions = implode(
		"\n",
		array(
			'Read this café menu PDF and record it with the save_menu tool.',
			'Rules:',
			'- Transcribe what is printed. Do not invent dishes, prices or wording, and do not reword descriptions.',
			'- Keep the printed order of sections and dishes (read columns top to bottom, left column before right).',
			'- Dish names: as printed, but in title case and without the (V) marker — set vegetarian instead.',
			'- Prices: plain numbers without a currency sign. Something like "25p" stays as text.',
			'- ADD lines become the extras field; veg-option and gluten-free lines become the note field.',
			'- Ignore the café name, address, phone number and logo.',
			'- Below is the menu currently on the website. Where the PDF shows the same dish, match its name spelling so it is recognised as the same dish; where the PDF differs, the PDF wins.',
			'',
			'Current website menu (JSON):',
			wp_json_encode( $current, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES ),
		)
	);

	$body = array(
		'model'       => rise_and_flour_menu_import_model(),
		'max_tokens'  => 8000,
		'system'      => 'You transcribe café menus from PDFs into structured data for the Rise & Flour website. Be exact and complete; every dish on the PDF must appear once.',
		'tools'       => array( $tool ),
		'tool_choice' => array(
			'type' => 'tool',
			'name' => 'save_menu',
		),
		'messages'    => array(
			array(
				'role'    => 'user',
				'content' => array(
					array(
						'type'   => 'document',
						'source' => array(
							'type'       => 'base64',
							'media_type' => 'application/pdf',
							'data'       => base64_encode( $pdf ), // phpcs:ignore WordPress.PHP.DiscouragedPHPFunctions.obfuscation_base64_encode
						),
					),
					array(
						'type' => 'text',
						'text' => $instructions,
					),
				),
			),
		),
	);

	$response = wp_remote_post(
		'https://api.anthropic.com/v1/messages',
		array(
			'timeout' => 180,
			'headers' => array(
				'content-type'      => 'application/json',
				'x-api-key'         => $key,
				'anthropic-version' => '2023-06-01',
			),
			'body'    => wp_json_encode( $body ),
		)
	);

	if ( is_wp_error( $response ) ) {
		return new WP_Error( 'rf_menu_http', sprintf( /* translators: %s: error. */ __( 'Could not reach the Anthropic API: %s', 'rise-and-flour' ), $response->get_error_message() ) );
	}

	$code = (int) wp_remote_retrieve_response_code( $response );
	$json = json_decode( wp_remote_retrieve_body( $response ), true );

	if ( 200 !== $code ) {
		$detail = isset( $json['error']['message'] ) ? $json['error']['message'] : wp_remote_retrieve_response_message( $response );
		return new WP_Error( 'rf_menu_api', sprintf( /* translators: 1: HTTP code, 2: message. */ __( 'The Anthropic API returned %1$d: %2$s', 'rise-and-flour' ), $code, $detail ) );
	}

	$input = null;
	foreach ( (array) ( $json['content'] ?? array() ) as $part ) {
		if ( isset( $part['type'] ) && 'tool_use' === $part['type'] && isset( $part['input'] ) ) {
			$input = $part['input'];
			break;
		}
	}

	if ( ! is_array( $input ) || empty( $input['sections'] ) || ! is_array( $input['sections'] ) ) {
		return new WP_Error( 'rf_menu_empty', __( 'Claude could not find a menu in that PDF.', 'rise-and-flour' ) );
	}

	return rise_and_flour_menu_import_normalise( $input );
}

/**
 * Coerce Claude's answer into the menu structure the block uses.
 *
 * @param array $input Tool input.
 * @return array
 */
function rise_and_flour_menu_import_normalise( $input ) {
	$allowed = array(
		'em'     => array(),
		'strong' => array(),
	);

	$menu = array(
		'sections'      => array(),
		'hours_heading' => sanitize_text_field( (string) ( $input['hours_heading'] ?? '' ) ),
		'hours'         => '',
		'notes'         => '',
	);

	foreach ( (array) $input['sections'] as $section ) {
		if ( ! is_array( $section ) ) {
			continue;
		}
		$items = array();
		foreach ( (array) ( $section['items'] ?? array() ) as $item ) {
			if ( ! is_array( $item ) || '' === trim( (string) ( $item['name'] ?? '' ) ) ) {
				continue;
			}
			$items[] = rise_and_flour_menu_import_item(
				array(
					'name'        => sanitize_text_field( (string) $item['name'] ),
					'price'       => sanitize_text_field( (string) ( $item['price'] ?? '' ) ),
					'vegetarian'  => ! empty( $item['vegetarian'] ),
					'description' => wp_kses( (string) ( $item['description'] ?? '' ), $allowed ),
					'extras'      => sanitize_text_field( (string) ( $item['extras'] ?? '' ) ),
					'note'        => sanitize_text_field( (string) ( $item['note'] ?? '' ) ),
				)
			);
		}
		if ( ! $items ) {
			continue;
		}
		$menu['sections'][] = array(
			'title' => sanitize_text_field( (string) ( $section['title'] ?? '' ) ),
			'note'  => sanitize_text_field( (string) ( $section['note'] ?? '' ) ),
			'items' => $items,
		);
	}

	// Hours groups → the block's textarea convention: a <strong> label line,
	// one range per line, blank line between groups.
	$groups = array();
	foreach ( (array) ( $input['hours_groups'] ?? array() ) as $group ) {
		if ( ! is_array( $group ) ) {
			continue;
		}
		$lines = array_values( array_filter( array_map( 'sanitize_text_field', (array) ( $group['lines'] ?? array() ) ) ) );
		if ( ! $lines ) {
			continue;
		}
		$label = sanitize_text_field( (string) ( $group['label'] ?? '' ) );
		if ( $label ) {
			array_unshift( $lines, '<strong>' . $label . '</strong>' );
		}
		$groups[] = implode( "\n", $lines );
	}
	$menu['hours'] = implode( "\n\n", $groups );

	$notes         = array_values( array_filter( array_map( 'sanitize_text_field', (array) ( $input['notes'] ?? array() ) ) ) );
	$menu['notes'] = implode( "\n\n", $notes );

	return $menu;
}
