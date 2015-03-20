    <div class="pure-g centered-row footer-menu">
        <div class="pure-u-1 pure-u-md-1">
            <div class="l-box">
                <div class="pure-menu pure-menu-horizontal">
                    <span class="pure-menu-heading">Menu</span>
                    <ul class="pure-menu-list">
                        <li class="pure-menu-item">
                        <?php
                            echo '<a class="pure-menu-link" href="' . $privacy_url .  '">' . _('Privacy') . '</a>';
                        ?>
                        </li>
                        <li class="pure-menu-item">
                        <?php
                            echo '<a class="pure-menu-link" href="' . CODE_URL .  '"><i class="fa fa-code"></i> ' . _('Source Code') . '</a>';
                        ?>
                        </li>
                        <?php if (! empty($imprint_url)) {
                            echo '<li class="pure-menu-item">';
                            echo '<a class="pure-menu-link" href="' . $imprint_url . '">' . _('Imprint') . '</a>';
                            echo '</li>';
                        }
                        ?>
                    </ul>
                </div>
            </div>
        </div>
    </div>
    </body>
</html>
