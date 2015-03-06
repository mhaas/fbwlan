<?php echo $head; ?>
    <div class="pure-g centered-row">
       <div class="pure-u-1 pure-u-md-1-2">
        <div class="l-box">
        <p> <?php
            echo _('Hey!');
            echo ' ';
            echo _('We\'d love you to check in to our location on Facebook.');
            echo ' ';
            echo _('For that, we need you to grant us the permission to publish a check-in message on your wall.');
            ?>
        </p>
        <p> <?php
            echo _('If you want to, you can also add a message to your check-in.');
            echo ' ';
            echo _('We will never check you in or post messages without your consent.');
            ?>
        </p>
        </div>
        </div>
        <div class="pure-u-1 pure-u-md-1-2">
            <div class="l-box">
                <p>
                    <a class="pure-button pure-button-primary" href="<?php echo $fburl; ?>">
                        <i class="fa fa-facebook-official fa-lg"></i>
                        <?php echo _('Grant Permission'); ?>
                    </a>
                </p>
            </div>
        </div>
    </div>
    <?php echo $back_to_code_widget ?>

<?php echo $foot; ?>
