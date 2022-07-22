<?php
get_header();

?>

<div class="contact-page">
    <div class="container">
        <form method="POST">
            <?php wp_nonce_field('contact_form', '_contact_form_nonce'); ?>
            <ul>
                <li>
                    <label for="name"><span>Name <span class="required-star">*</span></span></label>
                    <input type="text" id="name" name="name">
                </li>
                <li>
                    <label for="mail"><span>Email <span class="required-star">*</span></span></label>
                    <input type="email" id="mail" name="email">
                </li>
                <li>
                    <label for="msg"><span>Age</span></label>
                    <input type="number" name="age">
                </li>
                <li>
                    <input type="submit" name="submit">
                </li>
            </ul>

            <input type="hidden" name="action" value="wpdb_contact_form">
        </form>
    </div>
</div>

<?php
get_footer();
