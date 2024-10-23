<?php

namespace App\Helpers;

use Illuminate\Support\Facades\Auth;

class LocaleHelper
{
    /**
     * Get the localized field based on the current locale.
     *
     * @param $model
     * @param string $fieldBaseName - The base name of the field (e.g., 'name', 'city', 'address').
     * @return string|null
     */
    public static function getLocalizedField($model, string $fieldBaseName)
    {
        $locale = app()->getLocale();
        $localizedField = $locale === 'ar' ? $fieldBaseName . '_ar' : $fieldBaseName . '_en';

        return $model->{$localizedField} ?? null;
    }

    /**
     * Get the localized fields based on the current locale and also provide both fields for admins.
     *
     * @param $model
     * @param array $fields - Array of field base names.
     * @return array<string, mixed>
     */
    public static function getLocalizedFields($model, array $fields, $isTwoLang = false): array
    {
        $isAdmin = (Auth::check() && Auth::user()->hasRole('admin')) || $isTwoLang;
        $dataFields = [];

        foreach ($fields as $field) {
            $dataFields[$field] = self::getLocalizedField($model, $field);

            if ($isAdmin) {
                $dataFields[$field . '_en'] = $model->{$field . '_en'} ?? null;
                $dataFields[$field . '_ar'] = $model->{$field . '_ar'} ?? null;
            }
        }

        return $dataFields;
    }
}
