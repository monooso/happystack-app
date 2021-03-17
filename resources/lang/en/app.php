<?php

$clientNotification = <<<MESSAGE
Hi there,

One of the services your website relies on is having a few issues.

You don’t need to do anything. I just wanted to let you know that we’re aware of the problem, and are working to resolve it.

Best regards,
:sender_name
MESSAGE;


return [
    'client_notification' => $clientNotification,
    'component_errors'    => '{0} No errors|{1} 1 error|[2,*] :count errors',
    'component_warnings'  => '{0} No warnings|{1} 1 warning|[2,*] :count warnings',
    'last_updated'        => 'Last updated :time_interval',
    'selected_components' => '{0} No components selected|{1} 1 component selected|[2,*] :count components selected',
];
