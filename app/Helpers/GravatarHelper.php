<?php

namespace App\Helpers;

class GravatarHelper
{
    public static function validate_gravatar($email)
    {
        $url = 'https://www.gravatar.com/avatar/' . md5($email) . '?d=404';

        $headers = @get_headers($url);

        if(!preg_match('|200|', $headers[0]))
        {
            $valid = FALSE;
        } else {
            $valid = TRUE;
        }

        return $valid;
    }

    public static function gravatar_image($email, $size=0, $d='')
    {
        $image_url = 'https://www.gravatar.com/avatar/' . md5($email) . '?s=' . $size . '&d=' . $d;

        return $image_url;
    }
}
