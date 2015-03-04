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
to handle this is to edit */etc/init.d/dnsmasq* and add the following
line to the end:
    
    ipset create fb hash:ip

Then, edit */etc/dnsmasq.conf* and tell dnsmasq to store any IPs for
Facebook in the **fb** ipset. Add this to end of the file:

    ipset=/facebook.com/fbcdn.net/akamaihd.net/fb

Finally, allow the **fb** ipset in the firewall. Edit */etc/firewall.user* and
add this:

    iptables -A WiFiDog_br-lan_AuthServers -m set --match-set fb dst -j ACCEPT

Start wifidog and reload the firewall:

    /etc/init.d/dnsmasq restart
    /etc/init.d/wifidog start
    fw3 reload

Once you've made sure everything works, you can make wifidog start on boot:

    /etc/init.d/wifidog restart

That's it!









