<?php

return [
    /*
     * Your Google Tag Manager ID.
     *
     * The GTM ID has the following format: GTM-XXXXXXX
     */
    'id' => env('GOOGLE_TAG_MANAGER_ID', ''),

    /*
     * Enable or disable the GTM script in your views.
     *
     * Enable = true
     * Disable = false
     */
    'enabled' => env('GOOGLE_TAG_MANAGER_ENABLED', true),

    /*
     * The key that is used to save the data to the session.
     *
     * You probably won't need to change this.
     */
    'session_name' => 'tagmanager',

    /*
     * User ID
     * https://developers.google.com/analytics/devguides/collection/ga4/user-id
     *
     * The key of the User model that will be used for the User-ID feature.
     *
     * The key cannot contain data that allows Google to personally identify an invidual,
     * such as: name, email, phone etc.
     * https://developers.google.com/analytics/devguides/collection/ga4/policy
     */
    'user_id_key' => env('GOOGLE_TAG_MANAGER_USER_ID_KEY', 'id'),

    /**
     * Measurement Protocol
     * https://developers.google.com/analytics/devguides/collection/protocol/ga4/
     *
     * The measurement ID associated with a stream.
     * Found in the Google Analytics UI under Admin > Data Streams > choose your stream > Measurement ID. The measurement_id isn't your Stream ID.
     */
    'measurement_id' => env('GOOGLE_MEASUREMENT_ID'),

    /**
     * The API SECRET generated in the Google Analytics UI.
     * Found in the Google Analytics UI under Admin > Data Streams > choose your stream > Measurement Protocol API Secrets.
     */
    'measurement_protocol_api_secret' => env('GOOGLE_MEASUREMENT_PROTOCOL_API_SECRET'),

    /**
     * The session key used to store the measurement protocol client id.
     */
    'measurement_protocol_client_id_session_key' => 'measurement-protocol-client-id'
];
