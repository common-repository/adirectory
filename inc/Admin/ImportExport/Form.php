<?php
if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}
?>
<div class="wrap">
    <form action="" id="csv-upload-form" class="form-container" enctype="multipart/form-data" method="post">
        <div class="upload-files-container">
            <div class="submit-button">
                <button type="submit" name="import_data" id="import_data" class="upload-button"><span class="qsd-loader-spinner"></span><?php echo esc_html__('Import', 'adirectory'); ?></button>
                <button type="button" id="export_data" class="upload-button export"><span class="qsd-loader-spinner"></span><?php echo esc_html__('Export', 'adirectory'); ?></button>
            </div>
            <div class="drag-file-area">
                <span class="upload-icon">
                    <svg height="80px" width="80px" fill="#27AE60" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" viewBox="0 0 35.317 35.317" xml:space="preserve">
                        <g>
                            <path style="fill:#2b69fa" d="M26.969,12.34c-0.748,0-1.492,0.105-2.221,0.309c-0.898-3.725-4.209-6.366-8.102-6.366
		c-3.716,0-7.015,2.523-8.023,6.064c-0.092-0.002-0.185-0.007-0.277-0.007C3.746,12.34,0,16.088,0,20.685
		c0,4.604,3.746,8.348,8.346,8.348h18.623c4.602,0,8.348-3.744,8.348-8.348C35.316,16.088,31.57,12.34,26.969,12.34z M26.969,27.894
		H8.346c-3.973,0-7.209-3.234-7.209-7.209c0-3.973,3.236-7.203,7.209-7.203c0.223,0,0.443,0.012,0.662,0.033l0.5,0.045l0.107-0.488
		c0.723-3.275,3.68-5.648,7.031-5.648c3.553,0,6.541,2.545,7.105,6.053l0.109,0.68l0.648-0.236c0.803-0.291,1.633-0.438,2.459-0.438
		c3.975,0,7.207,3.23,7.207,7.203C34.176,24.66,30.943,27.894,26.969,27.894z" />
                            <path style="fill:#2b69fa;" d="M22.398,15.363c-0.217-0.217-0.574-0.217-0.787,0l-6.578,6.576l-3.389-3.406
		c-0.217-0.221-0.57-0.221-0.789,0l-1.188,1.182c-0.219,0.215-0.219,0.574,0,0.793l4.967,4.994c0.215,0.217,0.568,0.217,0.789,0
		l8.162-8.162c0.223-0.217,0.223-0.574,0-0.791L22.398,15.363z" />
                        </g>
                    </svg>
                </span>
                <h3 class="dynamic-message"><?php echo esc_html__('Drag & drop any file here', 'adirectory'); ?></h3>
                <label class="label">
                    <span class="browse-files">
                        <span class="or"><?php echo esc_html__('or', 'adirectory'); ?></span>
                        <input type="file" name="import_file" class="default-file-input" accept=".zip" />
                        <span class="browse-files-text"><?php echo esc_html__('Browse File', 'adirectory'); ?></span> <span><?php echo esc_html__('from device', 'adirectory'); ?></span>
                    </span>
                </label>
                <div class="qsd-loader-overly"></div>
                <span class="qsd-loader-spinner"></span>
                <h3 class="qsd-loader-text"><?php echo esc_html__('Please Wait for download and insert attachment...', 'adirectory'); ?></h3>
            </div>
            <span class="cannot-upload-message">
                <?php echo esc_html__('Please select a file first', 'adirectory'); ?>
            </span>

            <div class="file-block">

                <div class="file-info">
                    <span class="file-name"> </span>
                    <span class="file-size">0 MB</span>
                    <span class="remove-file-icon">
                        <svg height="50px" width="50px" version="1.1" fill="#fff" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" viewBox="0 0 290 290" xml:space="preserve">
                            <g id="XMLID_24_">
                                <g id="XMLID_29_">
                                    <path d="M265,60h-30h-15V15c0-8.284-6.716-15-15-15H85c-8.284,0-15,6.716-15,15v45H55H25c-8.284,0-15,6.716-15,15s6.716,15,15,15
			h5.215H40h210h9.166H265c8.284,0,15-6.716,15-15S273.284,60,265,60z M190,60h-15h-60h-15V30h90V60z" />
                                </g>
                                <g id="XMLID_86_">
                                    <path d="M40,275c0,8.284,6.716,15,15,15h180c8.284,0,15-6.716,15-15V120H40V275z" />
                                </g>
                            </g>
                        </svg>
                    </span>

                </div>
            </div>
            <div id="status"></div>
        </div>
    </form>
</div>