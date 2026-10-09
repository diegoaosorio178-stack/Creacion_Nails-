<?php
$ch = curl_init();
curl_setopt($ch, CURLOPT_URL,"http://new-creacion-nails.test/index.php?url=admin/aprobarCita");
curl_setopt($ch, CURLOPT_POST, 1);
curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query(array('id_cita' => 3)));
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_HEADER, true);
$server_output = curl_exec($ch);
curl_close($ch);
echo $server_output;
