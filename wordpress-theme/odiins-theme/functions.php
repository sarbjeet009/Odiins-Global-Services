<?php
/**
 * Odiins Platform Theme Functions & Definitions
 * Includes:
 * 1. Asset enqueuing (Google Fonts, CSS, JS) with WordPress config localization
 * 2. 1-Click Page Auto-Creator & Menu Setup (via hook or WP Admin notice)
 * 3. Complete WordPress REST API backend for Leads capture, Lead updates, and CSV Export
 * 4. Admin Authentication endpoint for the Dashboard
 * 5. Email notifications to site administrator via wp_mail()
 */

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}

// ----------------------------------------------------------------------
// 1. THEME SETUP & SCRIPTS
// ----------------------------------------------------------------------
function odiins_theme_setup() {
    add_theme_support('title-tag');
    add_theme_support('post-thumbnails');
    add_theme_support('responsive-embeds');

    register_nav_menus(array(
        'primary' => __('Primary Navigation Menu', 'odiins'),
        'footer'  => __('Footer Navigation Menu', 'odiins'),
    ));
}
add_action('after_setup_theme', 'odiins_theme_setup');

function odiins_enqueue_scripts() {
    // Google Fonts - Streamlined for maximum speed (400, 600, 700)
    wp_enqueue_style('odiins-google-fonts', 'https://fonts.googleapis.com/css2?family=Poppins:wght@400;600;700&display=swap', array(), null);

    // Main Style
    wp_enqueue_style('odiins-main-style', get_template_directory_uri() . '/css/style.css', array(), '2.1.0');
    wp_enqueue_style('odiins-theme-style', get_stylesheet_uri(), array('odiins-main-style'), '2.1.0');

    // Main Scripts (deferred in footer)
    wp_enqueue_script('odiins-main-js', get_template_directory_uri() . '/js/main.js', array(), '2.1.0', true);
    wp_enqueue_script('odiins-forms-js', get_template_directory_uri() . '/js/forms.js', array('odiins-main-js'), '2.1.0', true);

    // Pass dynamic configuration to frontend JavaScript
    wp_localize_script('odiins-forms-js', 'odiins_wp', array(
        'rest_url'  => esc_url_raw(rest_url('odiins/v1/')),
        'ajax_url'  => esc_url(admin_url('admin-ajax.php')),
        'theme_url' => esc_url(get_template_directory_uri()),
        'home_url'  => esc_url(home_url('/')),
    ));
}
add_action('wp_enqueue_scripts', 'odiins_enqueue_scripts');

// ----------------------------------------------------------------------
// 2. AUTOMATIC ONE-CLICK PAGE & MENU CREATOR
// ----------------------------------------------------------------------
function odiins_run_auto_setup() {
    $pages = array(
        'home' => array(
            'title'    => 'Home',
            'template' => 'default', // uses index.php
            'is_front' => true,
        ),
        'services-job-seekers' => array(
            'title'    => 'For Job Seekers',
            'template' => 'page-services-job-seekers.php',
        ),
        'services-employers' => array(
            'title'    => 'For Employers & Business',
            'template' => 'page-services-employers.php',
        ),
        'services-customers' => array(
            'title'    => 'For Customers (Home Help)',
            'template' => 'page-services-customers.php',
        ),
        'about-vision-mission' => array(
            'title'    => 'Vision & Mission',
            'template' => 'page-about-vision-mission.php',
        ),
        'about-media' => array(
            'title'    => 'Media & Recognition',
            'template' => 'page-about-media.php',
        ),
        'blogs' => array(
            'title'    => 'Blogs & Insights',
            'template' => 'page-blogs.php',
        ),
        'blog-detail' => array(
            'title'    => 'Hiring in Bhubaneswar Guide',
            'template' => 'page-blog-detail.php',
        ),
        'contact' => array(
            'title'    => 'Contact Us',
            'template' => 'page-contact.php',
        ),
        'dashboard' => array(
            'title'    => 'Admin Command Center',
            'template' => 'page-dashboard.php',
        ),
        'privacy-policy' => array(
            'title'    => 'Privacy Policy',
            'template' => 'page-privacy-policy.php',
        ),
        'terms' => array(
            'title'    => 'Terms & Conditions',
            'template' => 'page-terms.php',
        ),
        'disclaimer' => array(
            'title'    => 'Disclaimer',
            'template' => 'page-disclaimer.php',
        ),
    );

    $created_pages = array();

    foreach ($pages as $slug => $data) {
        $existing = get_page_by_path($slug);
        $page_id  = 0;

        if (!$existing) {
            $page_id = wp_insert_post(array(
                'post_title'   => $data['title'],
                'post_name'    => $slug,
                'post_status'  => 'publish',
                'post_type'    => 'page',
                'post_content' => '',
            ));
        } else {
            $page_id = $existing->ID;
        }

        if ($page_id && !is_wp_error($page_id)) {
            if ($data['template'] !== 'default') {
                update_post_meta($page_id, '_wp_page_template', $data['template']);
            }
            if (!empty($data['is_front'])) {
                update_option('show_on_front', 'page');
                update_option('page_on_front', $page_id);
            }
            $created_pages[$slug] = $page_id;
        }
    }

    // Set standard permalink structure
    if (get_option('permalink_structure') !== '/%postname%/') {
        update_option('permalink_structure', '/%postname%/');
    }
    flush_rewrite_rules();

    // Create Primary Navigation Menu if it doesn't exist
    $menu_name = 'Odiins Main Menu';
    $menu_exists = wp_get_nav_menu_object($menu_name);
    if (!$menu_exists) {
        $menu_id = wp_create_nav_menu($menu_name);

        if ($menu_id && !is_wp_error($menu_id)) {
            // Home
            if (isset($created_pages['home'])) {
                wp_update_nav_menu_item($menu_id, 0, array(
                    'menu-item-title'     => 'Home',
                    'menu-item-object'    => 'page',
                    'menu-item-object-id' => $created_pages['home'],
                    'menu-item-type'      => 'post_type',
                    'menu-item-status'    => 'publish',
                ));
            }
            // Services
            if (isset($created_pages['services-job-seekers'])) {
                wp_update_nav_menu_item($menu_id, 0, array(
                    'menu-item-title'     => 'For Job Seekers',
                    'menu-item-object'    => 'page',
                    'menu-item-object-id' => $created_pages['services-job-seekers'],
                    'menu-item-type'      => 'post_type',
                    'menu-item-status'    => 'publish',
                ));
            }
            if (isset($created_pages['services-employers'])) {
                wp_update_nav_menu_item($menu_id, 0, array(
                    'menu-item-title'     => 'For Employers',
                    'menu-item-object'    => 'page',
                    'menu-item-object-id' => $created_pages['services-employers'],
                    'menu-item-type'      => 'post_type',
                    'menu-item-status'    => 'publish',
                ));
            }
            if (isset($created_pages['services-customers'])) {
                wp_update_nav_menu_item($menu_id, 0, array(
                    'menu-item-title'     => 'For Customers',
                    'menu-item-object'    => 'page',
                    'menu-item-object-id' => $created_pages['services-customers'],
                    'menu-item-type'      => 'post_type',
                    'menu-item-status'    => 'publish',
                ));
            }
            // Blogs
            if (isset($created_pages['blogs'])) {
                wp_update_nav_menu_item($menu_id, 0, array(
                    'menu-item-title'     => 'Blogs',
                    'menu-item-object'    => 'page',
                    'menu-item-object-id' => $created_pages['blogs'],
                    'menu-item-type'      => 'post_type',
                    'menu-item-status'    => 'publish',
                ));
            }
            // Contact
            if (isset($created_pages['contact'])) {
                wp_update_nav_menu_item($menu_id, 0, array(
                    'menu-item-title'     => 'Contact',
                    'menu-item-object'    => 'page',
                    'menu-item-object-id' => $created_pages['contact'],
                    'menu-item-type'      => 'post_type',
                    'menu-item-status'    => 'publish',
                ));
            }

            // Assign menu to location
            $locations = get_theme_mod('nav_menu_locations');
            $locations['primary'] = $menu_id;
            set_theme_mod('nav_menu_locations', $locations);
        }
    }

    update_option('odiins_setup_completed', '1');
}

// Run auto-setup automatically on theme activation
add_action('after_switch_theme', 'odiins_run_auto_setup');

// Admin Notice with 1-Click Setup Button
function odiins_admin_setup_notice() {
    if (isset($_GET['odiins_do_setup']) && current_user_can('manage_options')) {
        odiins_run_auto_setup();
        echo '<div class="notice notice-success is-dismissible" style="padding:12px; border-left-color:#098B38;">
            <p style="font-size:15px; margin:0;"><strong>✅ Odiins Auto-Setup Complete!</strong> All 13 website pages, menus, templates, and permalinks have been created and linked successfully.</p>
        </div>';
        return;
    }

    $setup_done = get_option('odiins_setup_completed');
    $contact_exists = get_page_by_path('contact');

    if (!$setup_done || !$contact_exists) {
        echo '<div class="notice notice-info" style="padding: 16px; border-left-color: #098B38; background: #FFFFFF;">
            <h3 style="margin: 0 0 8px 0; color: #1F2937; font-size:17px;">🚀 Complete Odiins One-Click Website Setup</h3>
            <p style="margin: 0 0 12px 0; color: #4B5563; font-size:14px; line-height:1.5;">
                Click the button below to automatically generate all 13 Odiins pages (Job Seekers, Employers, Home Help, About, Blogs, Contact, Dashboard, Legal), assign their templates, and configure your navigation menus.
            </p>
            <a href="' . esc_url(add_query_arg('odiins_do_setup', '1')) . '" class="button button-primary button-hero" style="background: #098B38; border-color: #076d2c; font-weight: 700; font-size:14px; text-decoration:none;">
                ✨ Auto-Create All Pages &amp; Menus Now
            </a>
        </div>';
    }
}
add_action('admin_notices', 'odiins_admin_setup_notice');


// ----------------------------------------------------------------------
// 3. WORDPRESS BACKEND FOR LEADS & ADMIN DASHBOARD (REST API)
// ----------------------------------------------------------------------

// Helper to get all leads
function odiins_get_all_leads() {
    $leads = get_option('odiins_stored_leads');
    if (!$leads || !is_array($leads)) {
        $leads = array(
            array(
                'id'          => 'OD-M19A01',
                'timestamp'   => date('c', strtotime('-2 hours')),
                'formType'    => 'Job Seeker',
                'status'      => 'New',
                'name'        => 'Subhashree Mohanty',
                'phone'       => '98610 12345',
                'location'    => 'Bhubaneswar, Khordha',
                'requirement' => 'Back Office / Computer Operator',
                'message'     => 'Graduate with 1 year Tally experience looking for day shift.',
                'adSource'    => 'Google Ads',
                'campaign'    => 'bbsr_job_seekers_search',
                'clickId'     => 'gclid_live_9921a',
            ),
            array(
                'id'          => 'OD-M19A02',
                'timestamp'   => date('c', strtotime('-5 hours')),
                'formType'    => 'Employer',
                'status'      => 'Contacted',
                'name'        => 'Kalinga Logistics Pvt Ltd',
                'phone'       => '70081 98765',
                'location'    => 'Cuttack (Choudwar)',
                'requirement' => 'Driver & Warehouse Staff (5 positions)',
                'message'     => 'Urgent requirement for heavy vehicle drivers with valid commercial license.',
                'adSource'    => 'Meta Ads',
                'campaign'    => 'odisha_msme_recruitment',
                'clickId'     => 'fbclid_msme_081',
            ),
            array(
                'id'          => 'OD-M19A03',
                'timestamp'   => date('c', strtotime('-1 day')),
                'formType'    => 'Household Customer',
                'status'      => 'In Progress',
                'name'        => 'Dr. Ashok Patnaik',
                'phone'       => '94370 54321',
                'location'    => 'Puri (VIP Road)',
                'requirement' => 'Cook & Elderly Caretaker',
                'message'     => 'Vegetarian cooking assistance required for elderly parents morning & evening.',
                'adSource'    => 'Direct / Organic',
                'campaign'    => 'Direct',
                'clickId'     => '',
            )
        );
        update_option('odiins_stored_leads', $leads);
    }
    return $leads;
}

// Register REST Routes
add_action('rest_api_init', function () {
    // 1. GET & POST /wp-json/odiins/v1/leads
    register_rest_route('odiins/v1', '/leads', array(
        array(
            'methods'  => WP_REST_Server::READABLE,
            'callback' => 'odiins_rest_get_leads',
            'permission_callback' => '__return_true',
        ),
        array(
            'methods'  => WP_REST_Server::CREATABLE,
            'callback' => 'odiins_rest_create_lead',
            'permission_callback' => '__return_true',
        ),
    ));

    // 2. PUT & DELETE /wp-json/odiins/v1/leads/(?P<id>[w-]+)
    register_rest_route('odiins/v1', '/leads/(?P<id>[w-]+)', array(
        array(
            'methods'  => WP_REST_Server::EDITABLE,
            'callback' => 'odiins_rest_update_lead',
            'permission_callback' => '__return_true',
        ),
        array(
            'methods'  => WP_REST_Server::DELETABLE,
            'callback' => 'odiins_rest_delete_lead',
            'permission_callback' => '__return_true',
        ),
    ));

    // 3. POST /wp-json/odiins/v1/auth
    register_rest_route('odiins/v1', '/auth', array(
        'methods'  => WP_REST_Server::CREATABLE,
        'callback' => 'odiins_rest_auth_login',
        'permission_callback' => '__return_true',
    ));

    // 4. GET /wp-json/odiins/v1/leads-export
    register_rest_route('odiins/v1', '/leads-export', array(
        'methods'  => WP_REST_Server::READABLE,
        'callback' => 'odiins_rest_export_leads_csv',
        'permission_callback' => '__return_true',
    ));
});

// Callback: Get leads
function odiins_rest_get_leads($request) {
    $leads = odiins_get_all_leads();
    return rest_ensure_response($leads);
}

// Callback: Create lead (from any form on the site)
function odiins_rest_create_lead($request) {
    $params = $request->get_json_params();
    if (empty($params)) {
        $params = $request->get_body_params();
    }

    $name        = sanitize_text_field($params['name'] ?? '');
    $phone       = sanitize_text_field($params['phone'] ?? '');
    $location    = sanitize_text_field($params['location'] ?? 'Odisha');
    $requirement = sanitize_text_field($params['requirement'] ?? 'General Manpower');
    $formType    = sanitize_text_field($params['formType'] ?? 'General Lead');
    $message     = sanitize_textarea_field($params['message'] ?? '');
    $adSource    = sanitize_text_field($params['adSource'] ?? 'Direct / Organic');
    $campaign    = sanitize_text_field($params['campaign'] ?? 'Direct');
    $clickId     = sanitize_text_field($params['clickId'] ?? '');

    if (empty($name) || empty($phone)) {
        return new WP_Error('missing_fields', 'Name and Phone are required.', array('status' => 400));
    }

    $lead = array(
        'id'          => 'OD-' . strtoupper(substr(uniqid(), -6)),
        'timestamp'   => date('c'),
        'formType'    => $formType,
        'status'      => 'New',
        'name'        => $name,
        'phone'       => $phone,
        'location'    => $location,
        'requirement' => $requirement,
        'message'     => $message,
        'adSource'    => $adSource,
        'campaign'    => $campaign,
        'clickId'     => $clickId,
    );

    $all_leads = odiins_get_all_leads();
    array_unshift($all_leads, $lead);
    update_option('odiins_stored_leads', $all_leads);

    // Send email alert to WordPress admin
    $admin_email = get_option('admin_email');
    $subject = "🔔 [New Odiins Lead] {$formType}: {$name} ({$location})";
    $body = "New Lead Received on Odiins Platform:

"
          . "Name: {$name}
"
          . "Phone: {$phone}
"
          . "Role / Need: {$requirement}
"
          . "Location: {$location}
"
          . "Category: {$formType}
"
          . "Channel: {$adSource} ({$campaign})
"
          . "Message: {$message}

"
          . "View on Dashboard: " . home_url('/dashboard/');
    @wp_mail($admin_email, $subject, $body);

    return rest_ensure_response(array('success' => true, 'lead' => $lead));
}

// Callback: Update lead status
function odiins_rest_update_lead($request) {
    $id = $request['id'];
    $params = $request->get_json_params();
    $status = sanitize_text_field($params['status'] ?? '');

    $all_leads = odiins_get_all_leads();
    $found = false;
    foreach ($all_leads as &$lead) {
        if ($lead['id'] === $id) {
            if (!empty($status)) $lead['status'] = $status;
            $found = true;
            break;
        }
    }

    if ($found) {
        update_option('odiins_stored_leads', $all_leads);
        return rest_ensure_response(array('success' => true));
    }

    return new WP_Error('not_found', 'Lead not found.', array('status' => 404));
}

// Callback: Delete lead
function odiins_rest_delete_lead($request) {
    $id = $request['id'];
    $all_leads = odiins_get_all_leads();
    $filtered = array();
    $found = false;

    foreach ($all_leads as $lead) {
        if ($lead['id'] === $id) {
            $found = true;
        } else {
            $filtered[] = $lead;
        }
    }

    if ($found) {
        update_option('odiins_stored_leads', $filtered);
        return rest_ensure_response(array('success' => true));
    }

    return new WP_Error('not_found', 'Lead not found.', array('status' => 404));
}

// Callback: Admin Login Authentication
function odiins_rest_auth_login($request) {
    $params = $request->get_json_params();
    $email = strtolower(trim($params['email'] ?? ''));
    $password = trim($params['password'] ?? '');

    // Default executive credentials
    if (($email === 'admin@odiins.com' || $email === 'admin') && $password === 'Odiins@Admin2026') {
        return rest_ensure_response(array('success' => true, 'token' => 'odiins_wp_' . time()));
    }

    // Also check standard WordPress administrator credentials
    $user = wp_authenticate($email, $password);
    if (!is_wp_error($user) && in_array('administrator', (array)$user->roles)) {
        return rest_ensure_response(array('success' => true, 'token' => 'odiins_wp_' . time()));
    }

    return new WP_Error('invalid_credentials', 'Invalid Admin ID or Password.', array('status' => 401));
}

// Callback: Export Leads as CSV
function odiins_rest_export_leads_csv($request) {
    $leads = odiins_get_all_leads();
    header('Content-Type: text/csv; charset=utf-8');
    header('Content-Disposition: attachment; filename=odiins_leads_' . date('Y-m-d') . '.csv');

    $output = fopen('php://output', 'w');
    fputcsv($output, array('Lead ID', 'Timestamp', 'Category', 'Status', 'Name', 'Phone', 'Location', 'Requirement', 'Source', 'Campaign', 'Click ID', 'Message'));

    foreach ($leads as $l) {
        fputcsv($output, array(
            $l['id'] ?? '',
            $l['timestamp'] ?? '',
            $l['formType'] ?? '',
            $l['status'] ?? '',
            $l['name'] ?? '',
            $l['phone'] ?? '',
            $l['location'] ?? '',
            $l['requirement'] ?? '',
            $l['adSource'] ?? '',
            $l['campaign'] ?? '',
            $l['clickId'] ?? '',
            $l['message'] ?? '',
        ));
    }
    fclose($output);
    exit;
}

// ----------------------------------------------------------------------
// 6. CUSTOM SEO TITLES & META DESCRIPTIONS
// ----------------------------------------------------------------------
function odiins_custom_seo_meta() {
    $desc = '';
    if (is_front_page() || is_home()) {
        $desc = "Odiins connects businesses with reliable staff, job seekers with verified local jobs, and Odisha households with trusted maids, cooks, tutors, and drivers.";
    } elseif (is_page('services-customers') || is_page_template('page-services-customers.php')) {
        $desc = "Hire trusted, background-checked domestic help in Bhubaneswar & Cuttack. Full-time maids, home cooks, CBSE tutors, elderly care, and authentic pandits | Odiins";
    } elseif (is_page('services-employers') || is_page_template('page-services-employers.php')) {
        $desc = "Hire pre-screened office staff, accountants, sales executives, security guards, and commercial drivers across Odisha. 24-hour fast matching with local support | Odiins";
    } elseif (is_page('services-job-seekers') || is_page_template('page-services-job-seekers.php')) {
        $desc = "Apply for verified private jobs in Bhubaneswar & Odisha. Openings for Sales Managers, accountants, data entry, office staff, telecallers & delivery fleet.";
    }
    if (!empty($desc)) {
        echo '<meta name="description" content="' . esc_attr($desc) . '">' . "\n";
    }
}
add_action('wp_head', 'odiins_custom_seo_meta', 1);

function odiins_custom_document_title($title) {
    if (is_front_page() || is_home()) {
        return "Odiins | Odisha's Own Platform for Hiring, Jobs & Trusted Home Help";
    } elseif (is_page('services-customers') || is_page_template('page-services-customers.php')) {
        return "Verified House Maid, Cook, Tutor & Driver in Bhubaneswar | Odiins";
    } elseif (is_page('services-employers') || is_page_template('page-services-employers.php')) {
        return "Hire Staff in Odisha | Manpower & Staffing Solutions in Bhubaneswar | Odiins";
    } elseif (is_page('services-job-seekers') || is_page_template('page-services-job-seekers.php')) {
        return "Private Jobs in Bhubaneswar | Sales Manager, Office & Staff Careers | Odiins";
    }
    return $title;
}
add_filter('pre_get_document_title', 'odiins_custom_document_title', 20);

/**
 * 301 Permanent Redirects for Legacy Google-Indexed URLs
 */
function odiins_legacy_redirects() {
    if (!isset($_SERVER['REQUEST_URI'])) return;
    $request_uri = trim(parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH), '/');
    if (
        $request_uri === 'staffing-services-in-bhubaneswar' ||
        $request_uri === 'staffing-services-bhubaneswar' ||
        $request_uri === 'staffing-solutions-bhubaneswar' ||
        $request_uri === 'staffing-solutions-in-bhubaneswar'
    ) {
        wp_redirect(home_url('/services-employers/'), 301);
        exit;
    }
}
add_action('template_redirect', 'odiins_legacy_redirects');
