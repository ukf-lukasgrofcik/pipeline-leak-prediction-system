<?php

require_once __DIR__ . '/../config/mail.php';

function sendMail($MONITOR_THRESHOLD, $expectedRE, $avgRE): void
{
    $message = "Odchýlka neurónovej siete presiahla limit $MONITOR_THRESHOLD\n"
        . "Očakávaná odchýlka: $expectedRE\n"
        . "Momentálna odchýlka: $avgRE\n"
        . "Prosím vykonajte potrebné kroky pre pretrénovanie modelu.";

    mail(MAIL_CONFIG['monitoring-recipient'], "Monitorovacia notifikácia", $message, MAIL_CONFIG['headers']);
}