<?php
/*
Plugin Name: Guest Post Submission
Description: Allows users to submit guest posts via a form and manages them in a custom table.
Version: 1.0
Author: Your Name
*/

// Create custom table on plugin activation
register_activation_hook(__FILE__, 'gps_create_custom_table');

function gps_create_custom_table() {
    global $wpdb;

    $table_name = $wpdb->prefix . 'guest_post'; // Custom table name
    $charset_collate = $wpdb->get_charset_collate();

    $sql = "CREATE TABLE $table_name (
        id mediumint(9) NOT NULL AUTO_INCREMENT,
        post_title varchar(255) NOT NULL,
        post_content text NOT NULL,
        author_name varchar(255) NOT NULL,
        author_email varchar(100) NOT NULL,
        submission_date datetime DEFAULT CURRENT_TIMESTAMP NOT NULL,
        status varchar(20) DEFAULT 'pending' NOT NULL,
        PRIMARY KEY (id)
    ) $charset_collate;";

    require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
    dbDelta($sql);
}

// Hook to add menu item for admin page
add_action('admin_menu', 'gps_add_admin_menu');

function gps_add_admin_menu() {
    add_menu_page(
        'Guest Post Submission',
        'Guest Post Submission',
        'manage_options',
        'guest_post_submission',
        'gps_admin_dashboard_page'
    );
}

// Display admin dashboard page
function gps_admin_dashboard_page() {
    global $wpdb;
    
    $table_name = $wpdb->prefix . 'guest_post';

    // Handle actions
    $action = isset($_GET['action']) ? sanitize_text_field($_GET['action']) : '';
    $post_id = isset($_GET['post_id']) ? absint($_GET['post_id']) : 0;

    if ($action && $post_id) {
        check_admin_referer('gps_' . $action . '_post_' . $post_id);
        if ($action == 'approve') {
            gps_approve_post($post_id);
        } elseif ($action == 'reject') {
            gps_reject_post($post_id);
        }
        wp_redirect(admin_url('admin.php?page=guest_post_submission'));
        exit;
    }

    // Query to fetch guest posts
    $paged = isset($_GET['paged']) ? absint($_GET['paged']) : 1;
    $posts_per_page = 10;
    $offset = ($paged - 1) * $posts_per_page;

    $results = $wpdb->get_results($wpdb->prepare(
        "SELECT * FROM $table_name ORDER BY submission_date DESC LIMIT %d OFFSET %d",
        $posts_per_page,
        $offset
    ));

    $total_posts = $wpdb->get_var("SELECT COUNT(*) FROM $table_name");

    ?>
    <div class="wrap">
        <h1>Guest Post Submissions</h1>
        <table class="wp-list-table widefat fixed striped">
            <thead>
                <tr>
                    <th scope="col" class="manage-column">Title</th>
                    <th scope="col" class="manage-column">Author</th>
                    <th scope="col" class="manage-column">Submission Date</th>
                    <th scope="col" class="manage-column">Status</th>
                    <th scope="col" class="manage-column">Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php if ($results) : ?>
                    <?php foreach ($results as $row) : ?>
                        <tr>
                            <td><?php echo esc_html($row->post_title); ?></td>
                            <td><?php echo esc_html($row->author_name); ?></td>
                            <td><?php echo esc_html($row->submission_date); ?></td>
                            <td><?php echo ucfirst(esc_html($row->status)); ?></td>
                            <td>
                                <?php if ($row->status == 'pending') : ?>
                                    <a href="<?php echo wp_nonce_url(admin_url('admin.php?page=guest_post_submission&action=approve&post_id=' . $row->id), 'gps_approve_post_' . $row->id); ?>" class="button button-primary">Approve</a>
                                    <a href="<?php echo wp_nonce_url(admin_url('admin.php?page=guest_post_submission&action=reject&post_id=' . $row->id), 'gps_reject_post_' . $row->id); ?>" class="button button-secondary">Reject</a>
                                <?php else : ?>
                                    <span>---</span>
                                <?php endif; ?>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php else : ?>
                    <tr>
                        <td colspan="5">No guest posts found.</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
        <div class="tablenav bottom">
            <div class="tablenav-pages">
                <?php
                echo paginate_links(array(
                    'total' => ceil($total_posts / $posts_per_page),
                ));
                ?>
            </div>
        </div>
    </div>
    <?php
}

// Approve a guest post
function gps_approve_post($post_id) {
    global $wpdb;
    $table_name = $wpdb->prefix . 'guest_post';

    $wpdb->update($table_name, array(
        'status' => 'publish'
    ), array('id' => $post_id));
}

// Reject a guest post
function gps_reject_post($post_id) {
    global $wpdb;
    $table_name = $wpdb->prefix . 'guest_post';

    $wpdb->update($table_name, array(
        'status' => 'trash'
    ), array('id' => $post_id));
}

// Enqueue scripts and styles
add_action('wp_enqueue_scripts', 'gps_enqueue_scripts');

function gps_enqueue_scripts() {
    wp_enqueue_script('gps-ajax-script', plugins_url('/js/gps-ajax.js', __FILE__), array('jquery'), null, true);
    wp_localize_script('gps-ajax-script', 'gps_ajax_object', array('ajax_url' => admin_url('admin-ajax.php')));
}

// Create the form shortcode
add_shortcode('guest_post_form', 'gps_guest_post_form_shortcode');

function gps_guest_post_form_shortcode() {
    ob_start();
    ?>
    <form id="guest-post-form">
        <label for="post_title">Post Title:</label>
        <input type="text" id="post_title" name="post_title" required><br>

        <label for="post_content">Content:</label>
        <textarea id="post_content" name="post_content" required></textarea><br>

        <label for="author_name">Author Name:</label>
        <input type="text" id="author_name" name="author_name" required><br>

        <label for="author_email">Author Email:</label>
        <input type="email" id="author_email" name="author_email" required><br>

        <input type="submit" value="Submit Post">
    </form>
    <div id="response-message"></div>
    <?php
    return ob_get_clean();
}

// Handle the form submission
add_action('wp_ajax_gps_submit_post', 'gps_submit_post');
add_action('wp_ajax_nopriv_gps_submit_post', 'gps_submit_post');

function gps_submit_post() {
    global $wpdb;

    // Check if all necessary POST data is set
    if (!isset($_POST['post_title']) || !isset($_POST['post_content']) || !isset($_POST['author_name']) || !isset($_POST['author_email'])) {
        wp_send_json_error('Missing fields');
    }

    $post_title = sanitize_text_field($_POST['post_title']);
    $post_content = wp_kses_post($_POST['post_content']);
    $author_name = sanitize_text_field($_POST['author_name']);
    $author_email = sanitize_email($_POST['author_email']);

    if (!is_email($author_email)) {
        wp_send_json_error('Invalid email address');
    }

    global $wpdb;
    $table_name = $wpdb->prefix . 'guest_post';
    
    $result = $wpdb->insert($table_name, array(
        'post_title'     => $post_title,
        'post_content'   => $post_content,
        'author_name'    => $author_name,
        'author_email'   => $author_email,
        'status'         => 'pending'
    ));

    if ($result === false) {
        wp_send_json_error('Failed to submit post');
    }

    wp_send_json_success('Post submitted successfully');
}

// Ensure no other output occurs before this
ob_end_flush();
?>
