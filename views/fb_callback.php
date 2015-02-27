
<?php echo $head; ?>
    <div class="pure-g">
        <h2><?php echo _('Facebook login successful!') ?></h2>
        <div class="pure-u-1 pure-u-md-1-3">
            <p> <?php
                echo _('The last step.');
                echo ' ';
                echo _('You can add a message to your check-in.');
                echo ' ';
            ?>
            </p>
        </div>
        <div class="pure-u-1 pure-u-md-2-3">
            <form class="pure-form" action="<?php echo $post_action; ?>">
                <fieldset>
                    <legend><?php echo _('Check In'); ?></legend>
                    <input type="textarea" placeholder="" name="message" id="text_fb_message">
                        <?php echo $suggested_message; ?>
                    </input>
                    <button class="pure-button" id="button_clear_fb_message">
                        <?php echo _('Clear message'); ?>
                    </button>
                    <button type="submit" class="pure-button pure-button-primary" >
                        <?php echo _('Check in to') . $place_name; ?>
                    </button>
                </fieldset>
            </form>
        </div>
    </div>
    <div class="pure-g">
        <div class="pure-u-1">
            <p> <?php echo _('If you do not want to check in, you can still use the access code!') ; ?> <p>
            <a href="<?php echo $retry_url; ?>" class="pure-button"><?php echo _('Back to login'); ?></a>
        </div>
    </div>

<?php echo $foot; ?>
