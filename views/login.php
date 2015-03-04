<?php echo $head; ?>
    <div class="pure-g centered-row">
       <div class="pure-u-1 pure-u-md-1-2">
        <div class="l-box">
        <p> <?php
            echo _('We offer free Wifi.');
            echo ' ';
            echo _('We\'d like to ask you to return the favor and check in to our location on Facebook.');
            echo ' ';
            echo _('So, let your friends know you\'re here and start surfing the web!');
            ?>
        </p>
        </div>
        </div>
        <div class="pure-u-1 pure-u-md-1-2">
            <div class="l-box">
                <p>
                    <a class="pure-button pure-button-primary" href="<?php echo $fburl; ?>"><?php echo _('Connect to Facebook'); ?></a>
                </p>
            </div>
        </div>
    </div>
    <div class="pure-g centered-row">
        <div class="pure-u-1 pure-u-md-1-2">
            <div class="l-box">
                <p> <?php
                    echo _('It\'s ok if you do not want to use Facebook.');
                    echo ' ';
                    echo _('Simply ask the staff at our location for the access code and enter it below.');
                    ?>
                </p>
            </div>
        </div>
        <div class="pure-u-1 pure-u-md-1-2">
            <div class="l-box">
                <p>
                    <form class="pure-form pure-form-stacked" action="<?php echo $codeurl; ?>">
                        <fieldset>
                            <legend><?php echo _('Access code'); ?></legend>
                            <input type="text" placeholder="XXXXXX" name="access_code">
                            <button type="submit" class="pure-button pure-button-primary">
                                <?php echo _('Sign in'); ?>
                            </button>
                        </fieldset>
                    </form>
                </p>
            </div>
        </div>
    </div>


<?php echo $foot; ?>
