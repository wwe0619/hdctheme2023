<?php
header( "HTTP/1.1 301 Moved Permanently" );
header( "location: " . home_url() );
exit;
?>
