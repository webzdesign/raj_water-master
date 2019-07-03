<?php

   ini_set( 'display_errors', 1 );

   error_reporting( E_ALL );

   $from = "east@rajwater.in";

   //$to = "mailhostingserver@gmail.com";
   $to = "pratikdonga.ap@gmail.com";

   $subject = "PHP Mail Test script";

   $message = "This is a test to check the PHP Mail functionality";

   $headers = "From:" . $from;

  $sent =  mail($to,$subject,$message, $headers);

   if($sent == 1)
   {
	echo "Test email sent";
   }
   else{
	echo "Test email Not sent";
   }
?>