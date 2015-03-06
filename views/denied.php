<?php echo $head; ?>
    <div class="pure-g centered-row">
        <div class="pure-u-1 pure-u-md-1-3">
            <div class="l-box">
                <h2><?php echo _('That didn\'t work!') ?></h2>
            </div>
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
    </div>
    <?php echo $back_to_code_widget ?>

<?php echo $foot; ?>
