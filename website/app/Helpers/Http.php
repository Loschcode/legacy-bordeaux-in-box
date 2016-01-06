<?php

if ( ! function_exists('subdomain'))
{
  function subdomain($sub) {

    dd(siteurl());

    $base_domain = Request::getHost();
    $full_domain = "$sub.$base_domain";

    dd($full_domain);

  }
}