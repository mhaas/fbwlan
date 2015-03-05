<?php echo $head; ?>
    <div class="centered-row">
        <div class='l-box'>
            <h2><?php echo _('That didn\'t work!') ?></h2>
        </div>
    </div>
    <div class="pure-g centered-row">
        <div class="pure-u-1 pure-u-md-2-3">
            <div class="l-box">
                <p> <?php
                    echo _('There was a problem:');
                    echo ' ';
                    echo $msg;
                    ?>
                </p>
            </div>
        </div>
        <div class="pure-u-1 pure-u-md-1-3">
            <div class="l-box">
                <p>
                    <a class="pure-button pure-button-primary" href="<?php echo $retry_url; ?>">
                    <i class="fa fa-rotate-left fa-lg"></i>
                    <?php echo _('Try again'); ?></a>
                </p>
            </div>
        </div>
    </div>


<?php echo $foot; ?>
