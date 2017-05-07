<?php

/**
 * File:        keepalive.php
 *
 * Abstract:    a post to this page will keep the session active.
 *
 * Author:      Lucy Linder <lucy.derlin@gmail.com>
 * Date:        June 2015
 *
 */
session_start();
echo "keep alive";