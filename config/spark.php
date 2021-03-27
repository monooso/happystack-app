<?php

use App\Constants\NotificationChannel;
use App\Constants\SubscriptionPlan;
use App\Models\Team;

return [

    /*
    |--------------------------------------------------------------------------
    | Spark Path
    |--------------------------------------------------------------------------
    |
    | This configuration option determines the URI at which the Spark billing
    | portal is available. You are free to change this URI to a value that
    | you prefer. You shall link to this location from your application.
    |
    */

    'path' => 'billing',

    /*
    |--------------------------------------------------------------------------
    | Spark Middleware
    |--------------------------------------------------------------------------
    |
    | These are the middleware that requests to the Spark billing portal must
    | pass through before being accepted. Typically, the default list that
    | is defined below should be suitable for most Laravel applications.
    |
    */

    'middleware' => ['web', 'auth'],

    /*
    |--------------------------------------------------------------------------
    | Branding
    |--------------------------------------------------------------------------
    |
    | These configuration values allow you to customize the branding of the
    | billing portal, including the primary color and the logo that will
    | be displayed within the billing portal. This logo value must be
    | the absolute path to an SVG logo within the local filesystem.
    |
    */

    'brand' => [
        'logo'  => realpath(__DIR__ . '/../public/svg/billing-logo.svg'),
        'color' => 'bg-indigo-700',
    ],

    /*
    |--------------------------------------------------------------------------
    | Proration Behavior
    |--------------------------------------------------------------------------
    |
    | This value determines if charges are prorated when making adjustments
    | to a plan such as incrementing or decrementing the quantity of the
    | plan. This also determines proration behavior if changing plans.
    |
    */

    'prorates' => true,

    /*
    |--------------------------------------------------------------------------
    | Spark Billables
    |--------------------------------------------------------------------------
    |
    | Below you may define billable entities supported by your Spark driven
    | application. You are free to have multiple billable entities which
    | can each define multiple subscription plans available for users.
    |
    | In addition to defining your billable entity, you may also define its
    | plans and the plan's features, including a short description of it
    | as well as a "bullet point" listing of its distinctive features.
    |
    */

    'billables' => [
        'team' => [
            'model'      => Team::class,
            'trial_days' => 14,
            'plans'      => [
                [
                    'archived'          => false,
                    'name'              => SubscriptionPlan::SMALL,
                    'short_description' => 'For freelancers or small agencies',
                    'monthly_id'        => env('SPARK_SMALL_MONTHLY_PLAN'),
                    'yearly_id'         => env('SPARK_SMALL_YEARLY_PLAN'),
                    'yearly_incentive'  => 'Save over 20%',
                    'features'          => [
                        '5 projects',
                        '1 team member',
                        'Monitor unlimited services per project',
                        'Email notifications',
                        'Slack notifications (coming soon)',
                    ],
                    'options' => [
                        'channels' => [NotificationChannel::MAIL],
                        'members'  => 1,
                        'projects' => 5,
                    ],
                ],
                [
                    'archived'          => false,
                    'name'              => SubscriptionPlan::MEDIUM,
                    'short_description' => 'For growing agencies',
                    'monthly_id'        => env('SPARK_MEDIUM_MONTHLY_PLAN'),
                    'monthly_incentive' => 'Most popular',
                    'yearly_id'         => env('SPARK_MEDIUM_YEARLY_PLAN'),
                    'yearly_incentive'  => 'Save over 20%',
                    'features'          => [
                        '15 projects',
                        '5 team members',
                        'Monitor unlimited services per project',
                        'Email notifications',
                        'Slack notifications (coming soon)',
                    ],
                    'options' => [
                        'channels' => [NotificationChannel::MAIL],
                        'members'  => 5,
                        'projects' => 15,
                    ],
                ],
                [
                    'archived'          => false,
                    'name'              => SubscriptionPlan::LARGE,
                    'short_description' => 'For larger agencies',
                    'monthly_id'        => env('SPARK_LARGE_MONTHLY_PLAN'),
                    'yearly_id'         => env('SPARK_LARGE_YEARLY_PLAN'),
                    'yearly_incentive'  => 'Save over 20%',
                    'features'          => [
                        '50 projects',
                        'Unlimited team members',
                        'Monitor unlimited services per project',
                        'Email notifications',
                        'Slack notifications (coming soon)',
                        'Zapier integration (coming soon)',
                    ],
                    'options' => [
                        'channels' => [NotificationChannel::MAIL],
                        'members'  => 1000000,
                        'projects' => 50,
                    ],
                ],
            ],
        ],
    ],
];
