<?php
//Alters the params in a URL
function change_url_parameter($url, $parameter, $parameterValue)
{
  $url = parse_url($url);
  parse_str($url["query"] ?? '', $parameters);
  unset($parameters[$parameter]);
  $parameters[$parameter] = $parameterValue;
  return  $url["path"] . "?" . http_build_query($parameters);
}