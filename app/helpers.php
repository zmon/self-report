<?php
/**
 * Need to add the follwoing to composer.json
 *      "autoload": {
-        "files": [
-            "app/helpers.php"
-        ],
 */

// Example usage:
//
//      Mail::to(lbv_set_to_email($invite->email))->queue(new InviteCreated($invite));
//

use Illuminate\Support\Arr;

if (! function_exists('lbv_set_to_email')) {
    function lbv_set_to_email($to_email)
    {
        if (Arr::get($_ENV, 'PRODUCTION_EMAIL', 'TEST') != 'PRODUCTION') {
            $to_email = Arr::get($_ENV, 'TEST_EMAIL_MAIL', 'paulb@barhamhouse.com');
        }

        return $to_email;
    }
}
