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
                    <input type="text" id="name" name="name" required>
                </li>
                <li>
                    <label for="email"><span>Email <span class="required-star">*</span></span></label>
                    <input type="email" id="email" name="email" required>
                </li>
                <li>
                    <label for="age"><span>Age</span></label>
                    <input type="number" name="age" required>
                </li>
                <li>
                    <label for="sex"><span>Age</span></label>
                    <select type="number" name="sex" id="sex" required>
                        <option value="" selected>--Select One--</option>
                        <option value="M">Male</option>
                        <option value="F">Female</option>
                        <option value="O">Others</option>
                    </select>
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
