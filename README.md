# FB WLAN Hotspot #

A simple, easy social wlan hotspot. This script works as an auth
server for Wifidog. If a user checks in to your business' location
on Facebook, they get free wifi in exchange.


## Features ##

* Lets users exchange a Facebook check-in for Wifi
* No Facebook, no problem: give out the access code
* Lightweight: does one thing only
* Uses the [Flight framework](http://flightphp.com/)
* Based on [Pure.css](http://purecss.io/)
* Compatible with [Wifidog (Protocol V1)](http://dev.wifidog.org/wiki/doc/developer/WiFiDogProtocol_V1)
  - Possibly CoovaChilli via [*chilli_proxy*](http://coova.org/CoovaChilli/Proxy))

## Why another auth server? ##

Quite simply because there are no other working solutions.
Some scripts which claim to use Facebook for hotspot authentication
are available in the wild. For [Authpuppy](http://www.authpuppy.org/),
there is a [third-party plugin for Facebook authentication](https://code.launchpad.net/~alliancecsf-dev/authpuppy/apAuthFacebookPlugin).
Authpuppy itself is quite unmaintained and uses the outdated Symfony 1.x
framework. Additionally, there is no check-in functionality out of the box.

There's also the [Wifidog auth server](https://github.com/wifidog/wifidog-auth)
which requires PostgreSQL. I don't have a web host capable of PostgreSQL, so
that was not acceptable either. The Wifidog auth server also does
not support Facebook.

There are more attempts at integrating Facebook login into a open-source
hotspot. [Kikiauth](https://github.com/hongquan/KikiAuth) is promising, yet
abandoned by its author. The problem here is themultitude of IP addresses
used by Facebook which makes it hard to whitelist all ressources necessary.
I solve this problem with the ipset feature of [Dnsmasq](http://www.thekelleys.org.uk/dnsmasq/docs/dnsmasq-man.html)
based on a recommendation by [jow on the OpenWRT forums](https://forum.openwrt.org/viewtopic.php?pid=235631#p235631).

The [socialwifi project by mengning](https://github.com/mengning/socialwifi)
requires tomcat and other java stuff.

[social-hotspot](https://github.com/acanthus2000/social-hotspot) comes quite
close to my requirements. It connects to Facebook and either asks the user
to like a page or to check in. On the gateway, NoCatSplash captures the user.
The problem here is that the Facebook app provides no real security: the user
can always log in by [POST-ing the correct form](https://github.com/acanthus2000/social-hotspot/blob/master/index.php#L37)
to NoCatSplash. Although this is unlikely to pose a real problem as there
are unlikely to be any security implications, I didn't like this way
of handling authentication client-side.

In my implementation, the gateway (Wifidog) verifies that the
Facebook app (this script) actually granted access to the user.

Several commercial implementations are also available. Facebook themselves
offer [Facebook Wifi](https://www.facebook.com/help/126760650808045/). The
offer looks good on paper, with affordable devices like the D-Link DIR-865L
and the Netgear R6300 (v2). Some research reveals that
the Facebook Wifi implementation
[always enables HTTPS](http://forum1.netgear.com/showpost.php?p=493554&postcount=12).
This means that many smartphone apps will always work and the user might not
even notice there is captive portal.

Other vendors charge unreasonable monthly fees for their services.


## Limitations ##

Social hotspots typically rely on an [AAA (Authentication, Authorization
and Accounting) server](http://en.wikipedia.org/wiki/RADIUS#Protocol_components).
Quite simply, there is no **Accounting**. Although Wifidog will occasionally
update this script with bandwidth usage information, the data is simply discarded.

There is also no real **Authentication**: the script does not remember who used
the hotspot. Facebook is only used to post a message and no details such as user
names or emails are retained.

Regarding **Authorization**: an user is authorized to access the internet
once they successfully check in via Facebook or if they provide the access
code.

In some jurisdictions, you must keep track of who uses your network. In this
case, this script is probably not for you. (Pull requests welcome!)

## Requirements ##

For this script:

* Webhost with some space
* PHP 5.4 (or maybe 5.3)
* 1 MySQL database
* lftp client

For the gateway:

* Router capable of running
  - [OpenWRT](http://www.openwrt.org) or possibly DD-WRT
  - [Wifidog](http://dev.wifidog.org/)

## Install ##

Copy the example config file to config.php and edit to suit your needs.

    cp config-example.php config.php
    vim config.php

The entries should be self-explanatory. To use this app, you need to create
an app in Facebook. During the app creation process, select "Website" as
platform and select "create app id". Proceed to enter the domain where this
script will be hosted as app domain. Copy the app id and the app secret
to *config.php*. For testing, this is good enough. Once you have verified
that everything works, you need to [submit your app for review](https://developers.facebook.com/docs/apps/review).
Facebook recently introduced this review process for apps which post
on behalf of the user.

If you have lftp installed, you can use the upload script. Create a file with
login details:
    
    cat <<EOF > upload_creds.sh 
    USER="my-ftp-user"
    PASS="my-ftp-pass"
    SITE="sftp://my-ftp-host/dir/"
    EOF

Run the upload script:

    bash upload.sh

If you do not have lftp, simply upload the files manually with your FTP
client of choice. See *upload.lftp* for details. In particular, make sure
to rename *htaccess* to *.htaccess*.

Once you have the files uploaded, you can test the script by opening
the website in your browser. If you want to test the Facebook integration,
provide some fake gateway details like this:

    http://example.xyz/login?gw_id=foo&gw_address=localhost&gw_port=8080

Once you went through the login, you will be redirected to http://localhost:8080/.
Don't be scared by the error message (you probably have no server running there!),
it means everything is working.

If you get a HTTP 500 error, a possible reason is related to .htaccess. For my
Apache 2.4 server, I had to adjust some RewriteRules. If these don't work for
you, refer to the [original .htaccess for Flight](http://flightphp.com/install).
If that doesn't work, consult the error logs of your webserver.

## Configuring Wifidog ##

Wifidog lives on the gateway/router and intercepts requests made by
clients.

The following instructions assume you have already configured the network
on your gateway. Typically, you have a wlan interface running without
encryption called "MyPlace Guest". Isolating clients from each other is
probably a good idea. See the [OpenWRT wiki for details.](http://wiki.openwrt.org/doc/uci/wireless)

The script is a drop-in replacement for the Wifidog auth server.
Make sure to set up **GatewayInterface** and **ExternalInterface**
in */etc/wifidog.conf*. The **AuthServer** directive is set up as follows
if the script is installed on http://example.xyz/fbwlan/:

    AuthServer {
        Hostname example.xyz
        Path /fbwlan/
    }

Make sure to set the correct hostname and path!

## Allowing Access to Facebook ##

As described above, Facebook uses many different IP addresses. Due to the way
the content distribution networks work, the same host name may resolve to
different addresses. This is why it's impractical to just whitelist
individual IP addresses. However, [http://ipset.netfilter.org/](ipsets)
together with dnsmasq solve this problem nicely.

On OpenWRT 14.07 (Barrier Breaker), the default dnsmasq version does not
support ipset. Install dnsmasq-full instead

    opkg update
    opkg install dnsmasq-full

On boot, we need to create the ipset where we store the IP addresses.
This must happen before dnsmasq can populate them. A simple way
to handle this is to edit */etc/firewall.user* and add the following
line to the end:
    
    ipset create fb hash:ip

Then, edit */etc/dnsmasq.conf* and tell dnsmasq to store any IPs for
Facebook in the **fb** ipset. Add this to end of the file:

    ipset=/facebook.com/fbcdn.net/akamaihd.net/fb

Finally, allow the **fb** ipset in the firewall. Add this under the
**FirewallRuleSet unknown-users** section in */etc/wifidog.conf*

    FirewallRule allow to-ipset fb

### Testing the setup ###
Start wifidog and reload the firewall:

    fw3 reload
    /etc/init.d/dnsmasq restart
    /etc/init.d/wifidog start
    sleep 10
    /etc/init.d/wifidog-fw-extra

Open any non-HTTPS website in your browser and you should be redirected to
the captive portal.

### Starting Wifidog automatically & reliably ###

In my testing on Barrier Breaker, the default wifidog init script failed
to bring up Wifidog. Apparently, Wifidog starts before the interfaces are up
and quits. However, we can (re-)start wifidog automatically on Wifi changes.
I took the opportunity to rewrite the Wifidog init script to use the new
[procd](http://wiki.openwrt.org/inbox/procd-init-scripts) init system.
The distinct advantage here is the process supervision:
if Wifidog crashes, it is automatically restarted. I originally hoped
to reload Wifidog automatically on interface changes via the **netdev**
param, but that didn't work.

    cat <<EOF > /etc/init.d/wifidog
    #!/bin/sh /etc/rc.common
    # Copyright (C) 2006 OpenWrt.org
    START=65

    USE_PROCD=1

    EXTRA_COMMANDS="status"
    EXTRA_HELP="        status Print the status of the service"

    start_service() {
        procd_open_instance
        # -s: log to syslog
        # -f: run in foreground
        procd_set_param command /usr/bin/wifidog -s -f
        procd_set_param respawn # respawn automatically if something died
        procd_set_param file /etc/wifidog.conf
        procd_close_instance
        # wait for firewall rules to be setup
        /etc/init.d/wifidog-fw-extra enabled && /etc/init.d/wifidog-fw-extra restart &

    }
    # TODO: wdctl supports reload without disconnecting users
    EOF
    chmod +x /etc/init.d/wifidog

Note that the script backgrounds the call to  *wifidog-fw-extra*. Otherwise, the firewall
will be set up before Wifidog which will then promptly discard the rules.

To ensure that Wifidog is restarted on interface changes, we create the
following hotplug script:

    cat <<EOF >/etc/hotplug.d/iface/30-wifidog
    #!/bin/sh
    # Based on firewall.hotplug
    [ "$ACTION" = ifup -o "$ACTION" = ifupdate ] || exit 0
    [ "$ACTION" = ifupdate -a -z "$IFUPDATE_ADDRESSES" -a -z "$IFUPDATE_DATA" ] && exit 0

    /etc/init.d/wifidog enabled || exit 0
    logger -t wifidog "Reloading wifidog due to $ACTION of $INTERFACE ($DEVICE)"
    /etc/init.d/wifidog restart
    EOF
    chmod +x /etc/hotplug.d/iface/30-wifidog

Note that you can see the logger output with the  *logread* command.

Now enable the init scripts to make Wifidog start on boot:

    /etc/init.d/wifidog enable
    /etc/init.d/wifidog-fw-extra enable

The downside to this method is that Wifidog is restarted multiple times. In
addition, the firewall is called repeatedly and slows down the boot process
due to the *sleep 10* call. The upside is that it works.







## License ##

FBWLAN is licensed under the AGPL. The files in views/* bear no
copyright notice for practical reasons, but they carry the same
license. 
