<?php echo $head; ?>
    <div class="centered-row">
        <div class='l-box'>
            <h2><?php echo _('Privacy Policy') ?></h2>
        </div>
    </div>
    <div class="pure-g centered-row">
        <div class="pure-u-1 pure-u-md-1">
            <div class="l-box">
                <p> <?php
                    echo _('We respect your privacy by storing only a minimal amount of data about you.');
                    echo ' ';
                    echo _('Facebook provides us with an access token which allows us to post a check-in to your profile');
                    echo ' ';
                    echo _('This access token is used once and then discarded');
                    echo ' ';
                    echo _('Beyond Facebook, we assign and store a wifi access token for your computer which expires after');
                    echo ' ' . $session_duration . _(' minutes');
                    echo ' ';
                    echo _('To provide access to the internet, we store the MAC address of your computer.')
                    echo ' ';
                    echo _('The MAC address is used to whitelist your computer. It is deleted once your session expires.');
                    echo ' ';
                    echo _('Please note that Facebook itself stores additional information about you.');
                    echo ' ';
                    echo _('For more information, please see the <a href="https://www.facebook.com/policy.php">Facebook privacy policy</a>');
                    echo _('Additionally, note that this webserver may process and store information provided by your browser in accordance with local laws');

                    ?>
                </p>
            </div>
        </div>
    </div>


<?php echo $foot; ?>
