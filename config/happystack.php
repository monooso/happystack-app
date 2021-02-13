<?php

declare(strict_types=1);

use App\Constants\Status;

$statusPageStatusMap = [
    'operational'          => Status::OKAY,
    'degraded_performance' => Status::WARN,
    'partial_outage'       => Status::WARN,
    'major_outage'         => Status::DOWN,
];

return [
    // Logos?
    // Public status page? Other links?
    'services' => [
        'manifest' => [
            'name'        => 'Manifest',
            'description' => 'Test account',
            'statuses'    => $statusPageStatusMap,
            'components'  => [
                [
                    'id'          => 'gy9xsn9gwzx1',
                    'key'         => 'alfa',
                    'name'        => 'Alfa',
                    'description' => 'Component Alfa',
                ],
                [
                    'id'          => 'js4jb7cc85bl',
                    'key'         => 'bravo',
                    'name'        => 'Bravo',
                    'description' => 'Component Bravo',
                ],
                [
                    'id'          => '3bb4g3xgzwsf',
                    'key'         => 'charlie',
                    'name'        => 'Charlie',
                    'description' => 'Component Charlie',
                ],
            ],
        ],
        'mailgun' => [
            'name'        => 'Mailgun',
            'description' => 'Transactional email service',
            'statuses'    => $statusPageStatusMap,
            'components'  => [
                [
                    'id'          => 'bwm7lcxpmygd',
                    'key'         => 'api',
                    'name'        => 'API',
                    'description' => 'Mailgun HTTP API',
                ],
                [
                    'id'          => 'q5pjqhp4nsyj',
                    'key'         => 'control_panel',
                    'name'        => 'Control Panel',
                    'description' => '',
                ],
                [
                    'id'          => 'msj99l1ctybs',
                    'key'         => 'email_validation',
                    'name'        => 'Email Validation',
                    'description' => '',
                ],
                [
                    'id'          => 'qsn97w35tlf7',
                    'key'         => 'events_logs',
                    'name'        => 'Events and Logs',
                    'description' => 'Mailgun Events API and Logs UI',
                ],
                [
                    'id'          => 'fn54lc3ntr17',
                    'key'         => 'inbound_email_processing',
                    'name'        => 'Inbound Email Processing',
                    'description' => '',
                ],
                [
                    'id'          => 'tnhtt5q8vklm',
                    'key'         => 'inbox_placement',
                    'name'        => 'Inbox Placement',
                    'description' => '',
                ],
                [
                    'id'          => '3x8cldj79lmg',
                    'key'         => 'outbound_delivery',
                    'name'        => 'Outbound Delivery',
                    'description' => 'Mailgun outbound SMTP delivery',
                ],
                [
                    'id'          => 'pdjtfylt9rv1',
                    'key'         => 'smtp',
                    'name'        => 'SMTP',
                    'description' => 'Mailgun SMTP API',
                ],
            ],
        ],
    ],
];
