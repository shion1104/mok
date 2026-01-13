<?php

/**
 * Class SvilAppMembershipUtils
 */
class SvilAppMembershipUtils
{
    /**
     * @return array
     */
    public static function getLabels(): array
    {
        $metas = SwpmFbUtilsCustomFields::get_custom_data_field_headers();

        $result = [];

        foreach ($metas as $meta) {
            $key = sanitize_title($meta);

            $result[$key] = $meta;
        }

        return apply_filters('svilapp/membership-utils/labels', $result);
    }

    /**
     * @return array
     */
    public static function getMetas(): array
    {
        $metas = static::getCustomMetas();

        if (! $metas) {
            return [];
        }

        $results = [];

        foreach (static::getLabels() as $key => $label) {
            $results[$key] = mb_convert_case($metas[$label] ?? '', MB_CASE_TITLE, 'UTF-8');
        }

        return apply_filters('svilapp/membership-utils/metas', $results);
    }

    /**
     * @param string $key
     * @return string
     */
    public static function getMeta(string $key): string
    {
        if (! SwpmMemberUtils::is_member_logged_in()) {
            return $key;
        }

        $metas = static::getMetas();

        return $metas[$key] ?? '';
    }

    /**
     * @return array
     */
    public static function getCustomMetas(): array
    {
        if (! SwpmMemberUtils::is_member_logged_in()) {
            return [];
        }

        return SwpmFbUtilsCustomFields::get_custom_data_by_member_id(SwpmMemberUtils::get_logged_in_members_id());
    }
}