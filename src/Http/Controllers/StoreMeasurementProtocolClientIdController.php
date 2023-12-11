<?php

namespace Label84\TagManager\Http\Controllers;

class StoreMeasurementProtocolClientIdController
{
    public function __invoke(): void
    {
        session([
            config('tagmanager.measurement_protocol_client_id_session_key') => request('client_id'),
        ]);
    }
}
