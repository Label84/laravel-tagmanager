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
];
