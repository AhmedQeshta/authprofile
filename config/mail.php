<?php
return [
  "driver" => "smtp",
  "host" => "smtp.mailtrap.io",
  "port" => 2525,
  "from" => array(
      "address" => "from@example.com",
      "name" => "Example"
  ),
  "username" => "21bbcc3fd64628",
  "password" => "560d1ee07d5d38",
  "sendmail" => "/usr/sbin/sendmail -bs"
];