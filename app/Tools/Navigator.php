<?php
/**
 * Created by PhpStorm.
 * User: D070369
 * Date: 11.12.2018
 * Time: 21:48
 */

namespace App\Tools;


class Navigator {

    const REASON_UNAUTHORIZED          = 0;
    const REASON_NOT_FOUND             = 1;
    const REASON_INVALID_REQUEST       = 2;
    const REASON_INTERNAL_SERVER_ERROR = 3;

    static function die($iReason = self::REASON_UNAUTHORIZED) {
        switch ($iReason) {

            // UNAUTHORIZED ACTIONS
            case self::REASON_UNAUTHORIZED:
                if (Check::isLoggedIn()) {
                    abort(403, __('auth.access_denied'));
                } else {
                    // give the user a chance to login if he is not
                    abort(302, '', ['location' => route('login')]);
                }
                break;
            // INVALID REQUESTS -> hacking attempts?!
            case self::REASON_INVALID_REQUEST:
                abort(403, __('auth.access_denied'));
                break;

            // UNAUTHORIZED ACTIONS
            case self::REASON_NOT_FOUND:
                abort(404, __('auth.not_found'));
                break;

            // UNAUTHORIZED ACTIONS
            case self::REASON_INTERNAL_SERVER_ERROR:
                abort(500, __('auth.server_error'));
                break;
        }

    }
}