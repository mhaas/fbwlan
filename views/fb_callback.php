
<?php echo $head; ?>
    <div class="centered-row">
        <div class="l-box">
            <h2><?php echo _('Facebook login successful!') ?></h2>
        </div>
    </div>
    <div class="pure-g centered-row">
        <div class="pure-u-1 pure-u-md-1-2">
            <div class="l-box">
                <p> <?php
                    echo _('The last step.');
                    echo ' ';
                    echo _('You can add a message to your check-in.');
                    echo ' ';
                    ?>
                </p>
            </div>
        </div>
        <div class="pure-u-1 pure-u-md-1-2">
            <div class="l-box">
                <form class="pure-form" action="<?php echo $post_action; ?>">
                    <fieldset>
                        <legend><?php echo _('Check In'); ?></legend>
                        <input type="textarea" placeholder="" name="message" id="text_fb_message" value="<?php echo $suggested_message; ?>">
                        <button class="pure-button" id="button_clear_fb_message">
                            <?php echo _('Clear message'); ?>
                        </button>
                        <button type="submit" class="pure-button pure-button-primary" >
                        <?php echo _('Check in to') . ' ' .$place_name; ?>
                    </button>
                    </fieldset>
                </form>
            </div>
        </div>
    </div>
    <div class="pure-g centered-row">
        <div class="pure-u-1-2">
            <div class="l-box">
                <p> <?php echo _('If you do not want to check in, you can still use the access code!') ; ?> <p>
            </div>
        </div>
        <div class="pure-u-1-2">
            <div class="l-box">
                <a href="<?php echo $retry_url; ?>" class="pure-button"><?php echo _('Back to login'); ?></a>
            </div>
        </div>
    </div>

<?php echo $foot; ?>
