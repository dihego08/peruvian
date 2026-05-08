<?php

use Greenter\Ws\Services\SunatEndpoints;
use Greenter\See;

$see = new See();
$see->setCertificate(file_get_contents(__DIR__ . '/certificate_pv_2024.pem'));

//$see->setService(SunatEndpoints::FE_BETA);
$see->setService(SunatEndpoints::FE_PRODUCCION);

$see->setClaveSOL('20455175781', 'PERUVI11', 'Omcipier11');

return $see;
