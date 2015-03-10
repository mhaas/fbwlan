<?php echo $head; ?>
    <div class="pure-g centered-row">
        <div class="pure-u-1">
            <div class="l-box">
                <p> <?php
                    echo _('Hey!');
                    echo ' ';
                    ?>
                </p>
                <p> <?php
                    echo ' ';
                    echo _('Welcome to the captive portal.');
                    ?>
                </p>
                <p> <?php
                    echo ' ';
                    echo _('You need to be connected to our WLAN Hotspot for complete functionality.');
                    ?>
                </p>
                <?php if (FB_REVIEW) {
                    echo '<p>';
                    echo ' ';
                    echo _('However, there is a demo mode!');
                    echo ' ';
                    echo printf(_('You can log in with Facebook and check in to %s on Facebook.'), $page_name);
                    echo ' ';
                    echo _('But you can\'t connect to our Wifi. For that, you need to be in our building.');
                    echo '</p>';
                    echo '<p>';
                    echo '<a class="pure-button pure-button-primary" href="' . $page_url . '">';
                    echo '<i class="fa fa-play lg"></i>';
                    echo 'Demo: Connect to ' .  $page_name . _(' on Facebook');
                    echo '</a>';
                    echo '</p>';
                    }
                ?>
            </div>
        </div>
    </div>


<?php echo $foot; ?>
