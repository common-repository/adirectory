<?php

namespace ADQS_Directory;

class EmailSender
{
    public function __construct()
    {
        add_action('phpmailer_init', [$this, 'phpmailer_configuration']);
    }

    public function phpmailer_configuration($phpmailer)
    {
        $from_name = $this->get_email_setting('from_email_name');
        $from_address = $this->get_email_setting('from_email_address');
        $phpmailer->isHTML(true);
        $phpmailer->CharSet = 'UTF-8';
        $phpmailer->setFrom($from_address, $from_name);
    }

    public function get_email_setting($settingname)
    {
        $adqs_settings = get_option('adqs_admin_settings', []);
        return $adqs_settings[$settingname] ?? '';
    }

    public function get_emaile_setting_tempaltes($tab, $subtab)
    {
        $adqs_settings = get_option('adqs_admin_settings', []);
        $email_templates = $adqs_settings['adqs_admin_templates'][$tab][$subtab];
        return $email_templates;
    }

    public function get_email_header()
    {
        $header = adqs_get_template_part("emails/email", "header");
        return $header;
    }

    public function get_email_footer()
    {
        $footer = adqs_get_template_part("emails/email", "footer");
        return $footer;
    }

    public function get_admin_email()
    {
        // Get the admin email from the WordPress settings
        $admin_email = get_option('admin_email');

        return $admin_email;
    }

    // public function send_mail($mail_trigger, $type, $data)
    // {
    //     // Get the appropriate email template based on the type and mail trigger
    //     $template = $this->get_emaile_setting_tempaltes($type, $mail_trigger);
    //     $subject = $template['subject'];
    //     $body = $template['html_body'];
    //     $receipent = array();
    //     // Extract the data array into variables
    //     extract($data);


    //     // Determine the placeholders and their corresponding replacements
    //     $search = [];
    //     $replace = [];

    //     if ($type === "user") {
    //         switch ($mail_trigger) {
    //             case "new_user_reg":
    //                 $search = array("{user_name}");
    //                 $replace = array($user_name);

    //                 $receipent[] = $user_email;
    //                 break;

    //             case "new_listing_sub":
    //                 $search = array("{user_email}", "{user_name}", "{listing_title}");
    //                 $replace = array($user_email, $submitted_by, $title);

    //                 $receipent[] = $user_email;
    //                 break;
    //             case "listing_is_approved":
    //                 $search = array("{user_name}", "{listing_title}");
    //                 $replace = array($user_name, $title);

    //                 $receipent[] = $user_email;
    //                 break;
    //             case "order_created":
    //                 $search = array("{order_id}", "{customer_name}");
    //                 $replace = array($order_id, $customer_name);

    //                 $receipent[] = $user_email;
    //                 break;
    //             case "order_completed":
    //                 $search = array("{order_id}", "{customer_name}");
    //                 $replace = array($order_id, $customer_name);

    //                 $receipent[] = $user_email;
    //                 break;
    //                 // Add more cases as needed
    //         }
    //     } else if ($type === "admin") {
    //         switch ($mail_trigger) {
    //             case "new_user_reg":
    //                 $search = array("{user_email}", "{user_name}");
    //                 $replace = array($user_email, $user_name);

    //                 $receipent[] = $this->get_admin_email();
    //                 break;
    //                 // Add more cases as needed
    //             case "new_listing_sub":
    //                 $search = array("{user_email}", "{user_name}", "{listing_title}");
    //                 $replace = array($user_email, $submitted_by, $title);

    //                 $receipent[] = $this->get_admin_email();
    //                 break;
    //             case "new_listing_up":
    //                 $search = array("{user_name}", "{listing_title}");
    //                 $replace = array($updated_by, $title);

    //                 $receipent[] = $this->get_admin_email();
    //                 break;
    //             case "order_created":
    //                 $search = array("{order_id}", "{customer_name}");
    //                 $replace = array($order_id, $customer_name);

    //                 $receipent[] = $this->get_admin_email();
    //                 break;
    //             case "order_completed":
    //                 $search = array("{order_id}", "{customer_name}");
    //                 $replace = array($order_id, $customer_name);

    //                 $receipent[] = $this->get_admin_email();
    //                 break;
    //         }
    //     }

    //     // Replace placeholders in the body
    //     if (!empty($search) && !empty($replace)) {
    //         $body = str_replace($search, $replace, $body);
    //     }

    //     // Start output buffering to generate the email content
    //     ob_start();
    //     echo $this->get_email_header();
    //     echo "<table width='700' border='0' cellspacing='0' cellpadding='0' style='background-color: #ffffff; padding: 20px;'>
    //             <tr class='email-body'>
    //                 <td align='center'>
    //                     <table width='600' border='0' cellspacing='0' cellpadding='0'>
    //                         <tr>
    //                             <td align='left' style='padding: 20px; font-size: 16px; font-family: Arial, sans-serif; color: #333;'>
    //                                 $body
    //                             </td>
    //                         </tr>
    //                     </table>
    //                 </td>
    //             </tr>
    //           </table>";
    //     echo $this->get_email_footer();
    //     $content = ob_get_clean();

    //     // Define email headers
    //     $headers = array(
    //         'Content-Type: text/html; charset=UTF-8',
    //         'From: ' . $this->get_email_setting('from_email_name') . ' <' . $this->get_email_setting('from_email_address') . '>'
    //     );

    //     if (!function_exists('wp_mail')) {
    //         include_once(ABSPATH . '/wp-includes/pluggable.php');
    //     }

    //     // Send the email and log if it fails
    //     if (!wp_mail($receipent, $subject, $content, $headers)) {
    //         error_log('Email failed to send.');
    //     }
    // }

    public function get_email_trigger($scope, $trigger_name)
    {
        $settings_data = get_option("adqs_admin_settings", []);

        if ($scope === "admin") {
            $triggers = $settings_data['adqs_admin_emails_triggers'] ?? [];
            return in_array($trigger_name, $triggers);
        } else if ($scope === "user") {
            $triggers = $settings_data['adqs_user_emails_triggers'] ?? [];
            return in_array($trigger_name, $triggers);
        }
    }

    public function send_mail($mail_trigger, $type, $data)
    {
        // Get the appropriate email template based on the type and mail trigger
        $template = $this->get_emaile_setting_tempaltes($type, $mail_trigger);
        $subject = $template['subject'];
        $body = $template['html_body'];
        $receipent = array();

        // Extract the data array into variables
        extract($data);

        // Determine the placeholders and their corresponding replacements
        $search = [];
        $replace = [];

        // Handle user emails
        if ($type === "user") {
            switch ($mail_trigger) {
                case "new_user_reg":
                    if (!$this->get_email_trigger('user', 'new_user_reg')) {
                        return;
                    }
                  
                    $search = array("{user_name}");
                    $replace = array($user_name ?? '');

                    $receipent[] = $user_email ?? '';
                    break;

                case "new_listing_sub":
                    if (!$this->get_email_trigger('user', 'new_listing_sub')) {
                        return;
                    }
                    $search = array("{user_email}", "{user_name}", "{listing_title}");
                    $replace = array($user_email ?? '', $submitted_by ?? '', $title ?? '');

                    $receipent[] = $user_email ?? '';
                    break;

                case "listing_is_approved":
                    if (!$this->get_email_trigger('user', 'listing_is_approved')) {
                        return;
                    }
                    $search = array("{user_name}", "{listing_title}");
                    $replace = array($user_name ?? '', $title ?? '');

                    $receipent[] = $user_email ?? '';
                    break;

                case "order_created":
                    if (!$this->get_email_trigger('user', 'order_created')) {
                        return;
                    }
                    $search = array("{order_id}", "{customer_name}");
                    $replace = array($order_id ?? '', $customer_name ?? '');

                    $receipent[] = $user_email ?? '';
                    break;

                case "order_completed":
                    if (!$this->get_email_trigger('user', 'order_completed')) {
                        return;
                    }
                    $search = array("{order_id}", "{customer_name}");
                    $replace = array($order_id ?? '', $customer_name ?? '');

                    $receipent[] = $user_email ?? '';
                    break;

                    // Add more cases as needed
            }
        } else if ($type === "admin") {
            switch ($mail_trigger) {
                case "new_user_reg":
                    if (!$this->get_email_trigger('admin', 'new_user_reg')) {
                        return;
                    }
                    $search = array("{user_email}", "{user_name}");
                    $replace = array($user_email ?? '', $user_name ?? '');

                    $receipent[] = $this->get_admin_email();
                    break;

                case "new_listing_sub":
                    if (!$this->get_email_trigger('admin', 'new_listing_sub')) {
                        return;
                    }
                    $search = array("{user_email}", "{user_name}", "{listing_title}");
                    $replace = array($user_email ?? '', $submitted_by ?? '', $title ?? '');

                    $receipent[] = $this->get_admin_email();
                    break;

                case "new_listing_up":
                    if (!$this->get_email_trigger('admin', 'new_listing_up')) {
                        return;
                    }
                    $search = array("{user_name}", "{listing_title}");
                    $replace = array($updated_by ?? '', $title ?? '');

                    $receipent[] = $this->get_admin_email();
                    break;

                case "order_created":
                    if (!$this->get_email_trigger('admin', 'order_created')) {
                        return;
                    }
                    $search = array("{order_id}", "{customer_name}");
                    $replace = array($order_id ?? '', $customer_name ?? '');

                    $receipent[] = $this->get_admin_email();
                    break;

                case "order_completed":
                    if (!$this->get_email_trigger('admin', 'order_completed')) {
                        return;
                    }
                    $search = array("{order_id}", "{customer_name}");
                    $replace = array($order_id ?? '', $customer_name ?? '');

                    $receipent[] = $this->get_admin_email();
                    break;

                    // Add more cases as needed
            }
        }

        // Replace placeholders in the body
        if (!empty($search) && !empty($replace)) {
            $body = str_replace($search, $replace, $body);
        }

        // Start output buffering to generate the email content
        ob_start();
        echo $this->get_email_header();
        echo "<table width='700' border='0' cellspacing='0' cellpadding='0' style='background-color: #ffffff; padding: 20px;'>
            <tr class='email-body'>
                <td align='center'>
                    <table width='600' border='0' cellspacing='0' cellpadding='0'>
                        <tr>
                            <td align='left' style='padding: 20px; font-size: 16px; font-family: Arial, sans-serif; color: #333;'>
                                $body
                            </td>
                        </tr>
                    </table>
                </td>
            </tr>
          </table>";
        echo $this->get_email_footer();
        $content = ob_get_clean();

        // Define email headers
        $headers = array(
            'Content-Type: text/html; charset=UTF-8',
            'From: ' . $this->get_email_setting('from_email_name') . ' <' . $this->get_email_setting('from_email_address') . '>'
        );

        if (!function_exists('wp_mail')) {
            include_once(ABSPATH . '/wp-includes/pluggable.php');
        }

        // Send the email and log if it fails
        if (!wp_mail($receipent, $subject, $content, $headers)) {
            error_log('Email failed to send.');
        }
    }
}