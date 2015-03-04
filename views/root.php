<?php echo $head; ?>
    <div class="pure-g centered-row">
        <div class="pure-u-1 pure-u-md-1-2">
            <div class="l-box">
                <p> <?php
                    echo _('Hey!');
                    echo ' ';
                    ?>
                </p>
                <p> <?php
                    echo _('There is nothing to see here.');
                    echo ' ';
                    echo _('Would you like to visit us on Facebook?');
                    ?>
                </p>
                <p>
                    <a class="pure-button pure-button-primary" href="<?php echo $page_url; ?>"><?php echo $page_name . _(' on Facebook'); ?></a>
                </p>
            </div>
        </div>
    </div>


<?php echo $foot; ?>
