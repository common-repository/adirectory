<?php

/**
 * The template for displaying listing descrption content in the single-listing.php template
 *
 * This template can be overridden by copying it to yourtheme/adirectory/single-listing/desciption.php.
 *
 * @package     QS Directories\Templates
 * @version     1.0.0
 */

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}
?>
<form name="signup-form" method="post">
    <div class="adqs-form-fields">
        <label for="susername"><?php echo esc_html__('First Name', 'adirectory');  ?></label>
        <div class="adqs-input-wrapper">
            <input type="text" name="fname" placeholder="Type your username">
            <div class="icon">
                <svg width="15" height="19" viewBox="0 0 15 19" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <path fill-rule="evenodd" clip-rule="evenodd"
                        d="M7.5 8.10684C9.70914 8.10684 11.5 6.2986 11.5 4.06801C11.5 1.83742 9.70914 0.0291748 7.5 0.0291748C5.29086 0.0291748 3.5 1.83742 3.5 4.06801C3.5 6.2986 5.29086 8.10684 7.5 8.10684ZM7.5 18.2039C11.366 18.2039 14.5 16.3957 14.5 14.1651C14.5 11.9345 11.366 10.1263 7.5 10.1263C3.63401 10.1263 0.5 11.9345 0.5 14.1651C0.5 16.3957 3.63401 18.2039 7.5 18.2039Z"
                        fill="#DBEAFF" />
                    <path fill-rule="evenodd" clip-rule="evenodd"
                        d="M7.5 8.10684C9.70914 8.10684 11.5 6.2986 11.5 4.06801C11.5 1.83742 9.70914 0.0291748 7.5 0.0291748C5.29086 0.0291748 3.5 1.83742 3.5 4.06801C3.5 6.2986 5.29086 8.10684 7.5 8.10684ZM7.5 18.2039C11.366 18.2039 14.5 16.3957 14.5 14.1651C14.5 11.9345 11.366 10.1263 7.5 10.1263C3.63401 10.1263 0.5 11.9345 0.5 14.1651C0.5 16.3957 3.63401 18.2039 7.5 18.2039Z"
                        fill="black" fill-opacity="0.2" />
                </svg>
            </div>
        </div>
    </div>
    <div class="adqs-form-fields">
        <label for="susername"><?php echo esc_html__('Last Name', 'adirectory');  ?></label>
        <div class="adqs-input-wrapper">
            <input type="text" name="lname" placeholder="Type your username">
            <div class="icon">
                <svg width="15" height="19" viewBox="0 0 15 19" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <path fill-rule="evenodd" clip-rule="evenodd"
                        d="M7.5 8.10684C9.70914 8.10684 11.5 6.2986 11.5 4.06801C11.5 1.83742 9.70914 0.0291748 7.5 0.0291748C5.29086 0.0291748 3.5 1.83742 3.5 4.06801C3.5 6.2986 5.29086 8.10684 7.5 8.10684ZM7.5 18.2039C11.366 18.2039 14.5 16.3957 14.5 14.1651C14.5 11.9345 11.366 10.1263 7.5 10.1263C3.63401 10.1263 0.5 11.9345 0.5 14.1651C0.5 16.3957 3.63401 18.2039 7.5 18.2039Z"
                        fill="#DBEAFF" />
                    <path fill-rule="evenodd" clip-rule="evenodd"
                        d="M7.5 8.10684C9.70914 8.10684 11.5 6.2986 11.5 4.06801C11.5 1.83742 9.70914 0.0291748 7.5 0.0291748C5.29086 0.0291748 3.5 1.83742 3.5 4.06801C3.5 6.2986 5.29086 8.10684 7.5 8.10684ZM7.5 18.2039C11.366 18.2039 14.5 16.3957 14.5 14.1651C14.5 11.9345 11.366 10.1263 7.5 10.1263C3.63401 10.1263 0.5 11.9345 0.5 14.1651C0.5 16.3957 3.63401 18.2039 7.5 18.2039Z"
                        fill="black" fill-opacity="0.2" />
                </svg>
            </div>
        </div>
    </div>

    <div class="adqs-form-fields">
        <label for="susername"><?php echo esc_html__('Email', 'adirectory');  ?></label>
        <div class="adqs-input-wrapper">
            <input type="text" name="email" placeholder="Type your email">
            <div class="icon">
                <svg width="19" height="17" viewBox="0 0 19 17" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <path fill-rule="evenodd" clip-rule="evenodd"
                        d="M4.16688 0.271851C2.2032 0.271851 0.611328 1.87918 0.611328 3.86193V12.8371C0.611328 14.8199 2.2032 16.4272 4.16688 16.4272H14.8336C16.7972 16.4272 18.3891 14.8199 18.3891 12.8371V3.86193C18.3891 1.87918 16.7972 0.271851 14.8336 0.271851H4.16688ZM4.53669 4.19932C4.23033 3.9931 3.81642 4.07669 3.61219 4.38602C3.40795 4.69534 3.49073 5.11327 3.79709 5.31949L7.15815 7.58196C8.5764 8.53663 10.424 8.53663 11.8423 7.58196L15.2034 5.31949C15.5097 5.11327 15.5925 4.69534 15.3883 4.38602C15.184 4.07669 14.7701 3.9931 14.4638 4.19932L11.1027 6.46179C10.1323 7.11498 8.86813 7.11498 7.89775 6.46179L4.53669 4.19932Z"
                        fill="#DBEAFF" />
                    <path fill-rule="evenodd" clip-rule="evenodd"
                        d="M4.16688 0.271851C2.2032 0.271851 0.611328 1.87918 0.611328 3.86193V12.8371C0.611328 14.8199 2.2032 16.4272 4.16688 16.4272H14.8336C16.7972 16.4272 18.3891 14.8199 18.3891 12.8371V3.86193C18.3891 1.87918 16.7972 0.271851 14.8336 0.271851H4.16688ZM4.53669 4.19932C4.23033 3.9931 3.81642 4.07669 3.61219 4.38602C3.40795 4.69534 3.49073 5.11327 3.79709 5.31949L7.15815 7.58196C8.5764 8.53663 10.424 8.53663 11.8423 7.58196L15.2034 5.31949C15.5097 5.11327 15.5925 4.69534 15.3883 4.38602C15.184 4.07669 14.7701 3.9931 14.4638 4.19932L11.1027 6.46179C10.1323 7.11498 8.86813 7.11498 7.89775 6.46179L4.53669 4.19932Z"
                        fill="black" fill-opacity="0.2" />
                </svg>
            </div>
        </div>



    </div>
    <div class="adqs-form-fields">
        <label for="spassword"><?php echo esc_html__('Password', 'adirectory');  ?></label>

        <div class="adqs-input-wrapper">
            <input type="password" name="password" placeholder="Type password">
            <div class="icon">
                <i class="fa fa-eye" aria-hidden="true"></i>
                <i class="fa fa-eye-slash" aria-hidden="true"></i>
            </div>
        </div>



    </div>
    <div class="adqs-form-fields">
        <label for="spassword2"><?php echo esc_html__('Confirm Password', 'adirectory');  ?></label>
        <div class="adqs-input-wrapper">
            <input type="password" name="confirmpass" placeholder="Type confirm password">
            <div class="icon">
                <i class="fa fa-eye" aria-hidden="true"></i>
                <i class="fa fa-eye-slash" aria-hidden="true"></i>
            </div>
        </div>
    </div>

    <div class="adqs-form-fields rememeber-check">
        <input type="checkbox" name="termscondition" value="1" id="terms-check">
        <label for="terms-check"><?php echo esc_html__('Terms & condition', 'adirectory'); ?></label>
    </div>

    <?php

    do_action("adqs_news_letter_mailchimp");

    ?>


    <span class="error"></span>
    <input name="adqs_login_regi_submit" type="submit" value="Sign Up" class="adqs-log-regi-btn" />
    <?php
    wp_nonce_field('adqs-regi', 'adqs-regi-nonce');
    if (!empty($_POST['redirect_to'])) {
        $redirect_to = $_REQUEST['redirect_to'] ?? '';
    } else {
        $redirect_to = (is_ssl() ? 'https://' : 'http://') . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];
    }
    echo sprintf('<input type="hidden" name="redirect_to" value="%s" />', esc_url($redirect_to));

    ?>
</form>