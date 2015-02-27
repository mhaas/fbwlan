<?php echo $head; ?>

    <h2><?php echo _('That didn\'t work!') ?></h2>
    <div class="pure-g">
        <div class="pure-u-1 pure-u-md-2-3">
            <p> <?php
                echo _('There was a problem:');
                echo ' ';
                echo $msg;
                ?>
            </p>
        </div>
        <div class="pure-u-1 pure-u-md-1-3">
            <a class="pure-button pure-button-primary" href="<?php echo $retry_url; ?>"><?php echo _('Try again'); ?></a>
        </div>
    </div>


<?php echo $foot; ?>
