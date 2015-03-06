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
                <p> <?php
                    echo ' ';
                    echo _('However, you can try a demo mode!');
                    echo ' ';
                    echo _('After you log in with Facebook, you will be redirected to our website at');
                    echo ' ';
                    echo $portal_url;
                    echo '.';
                    ?>
                </p>
                <p>
                    <a class="pure-button pure-button-primary" href="<?php echo $page_url; ?>">
                        <i class="fa fa-play lg"></i>
                        <?php echo 'Demo: Connect to ' .  $page_name . _(' on Facebook'); ?>
                    </a>
                </p>
            </div>
        </div>
    </div>


<?php echo $foot; ?>
