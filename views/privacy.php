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
                    ?>
                </p>
                <p> <?php
                    echo ' ';
                    echo _('Facebook provides us with an access token which allows us to post a check-in to your profile.');
                    echo ' ';
                    echo _('This access token is used once and then discarded.');
                    echo ' ';
                    echo _('We do not use or store your name, email address or friend list.');
                    ?>
                </p>
                <p>
                    <?php
                    echo ' ';
                    echo _('Beyond Facebook, we assign and store a randomly generated WLAN access token for your computer which expires after');
                    echo ' ' . $session_duration . _(' minutes.');
                    echo ' ';
                    echo _('To provide access to the internet, we store the MAC address of your computer and the IP address assigned by us.');
                    echo ' ';
                    echo _('The MAC address and the IP address are used to whitelist your computer.');
                    echo ' ';
                    echo _('They are deleted once your WLAN session expires.');
                    ?>
                </p>
                <p> <?php
                    echo _('We store additional information in a small text '
                        . 'files called "cookie" on your computer.');
                    echo ' ';
                    echo _('This cookie is used to store a session identifier '
                        . 'necessary to provide service to you.');
                    echo ' ';
                    echo _('The cookie is set to expire after');
                    echo ' ' . $cookie_session_duration . _(' minutes.');
                    ?>
                </p>
                <p> <?php
                    echo _('Please note that Facebook itself stores '
                        . 'additional information about you.');
                    echo ' ';
                    echo _('For more information, please see the <a '
                        . 'href="https://www.facebook.com/policy.php">'
                        . 'Facebook privacy policy</a>.');
                    ?>
                </p>
                <p> <?php
                    echo ' ';
                    echo _('Additionally, note that this webserver may '
                        . 'process and store information provided by your '
                        . 'browser or computer in accordance with local '
                        . 'laws.');
                    ?>
                </p>
                <?php if (!empty($extended_privacy_url)) {
                    echo '<p>';
                    echo _('Please see our') . ' ' . '<a href="'
                        . $extended_legal_url . '">'
                        . _('full privacy policy.') . '</a>';
                    echo ' ';
                    echo _('In particular, other parts of our offerings beyond'.
                            ' WLAN access may be subject to different privacy policies.');
                    echo '</p>';
                }
                ?>
                <?php if (!empty($imprint_url)) {
                    echo '<p>';
                    echo '<a href="'
                        . $extended_legal_url . '">'
                        . _('Imprint') . '</a>';
                    echo '</p>';
                }
                ?>
            </div>
        </div>
    </div>


<?php echo $foot; ?>
