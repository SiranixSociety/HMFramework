<?php

namespace SiranixSociety\HMFramework\Traits;

use Carbon\Carbon;
use Illuminate\Support\Facades\Config;

trait HMSettingsHelper {
    /*
     * Config Functions
     */
    public function HMHasConfigSetting($SettingPath){
        return Config::has('HelperModels.Settings.'.$SettingPath);
    }
    public function HMGetConfigSetting($SettingPath){
        return config('HelperModels.Settings.'.$SettingPath);
    }


    /*
     * Limitation Functions
     */

    // Use Time

    // Basics
    public function HMGetLimitationMode($Limitation){
        if(array_has($Limitation, 'Mode')){
            return array_get($Limitation, 'Mode');
        }
        return 0;
    }
    public function HMGetLimitationAmount($Limitation){
        if(array_has($Limitation, 'Amount')){
            return array_get($Limitation, 'Amount');
        }
        return 0;
    }

    // Time
    public function HMGetLimitationUseTime($Limitation){
        if(array_has($Limitation, 'UseTime')){
            return array_get($Limitation, 'UseTime');
        }
        return false;
    }
    public function HMLimitationUsesTime($Limitation){
        return $this->HMGetLimitationUseTime($Limitation);
    }
    public function HMGetLimitationTime($Limitation){
        $Time = $Limitation['Time'];
        $Years = $this->HMGetLimitationTimePart($Time, 'Years');
        $Months = $this->HMGetLimitationTimePart($Time, 'Months');
        $Weeks = $this->HMGetLimitationTimePart($Time, 'Weeks');
        $Days = $this->HMGetLimitationTimePart($Time, 'Days');
        $Hours = $this->HMGetLimitationTimePart($Time, 'Hours');
        $Minutes = $this->HMGetLimitationTimePart($Time, 'Minutes');
        $Seconds = $this->HMGetLimitationTimePart($Time, 'Seconds');

        return Carbon::now()->subYears($Years)->subMonths($Months)->subWeeks($Weeks)->subDays($Days)->subHours($Hours)->subMinutes($Minutes)->subSeconds($Seconds);
    }
    public function HMGetLimitationTimePart($Time, $Part){
        if(array_has($Time, $Part)){
            return array_get($Time, $Part);
        }
        return 0;
    }

    /*
     * Reference Functions
     */

    // Override
    public function HMGetReferenceOverride($OverrideName){
        return $this->HMGetConfigSetting('OverrideReferences.'.$OverrideName);
    }
    public function HMHasReferenceOverride($OverrideName){
        return $this->HMHasConfigSetting('OverrideReferences.'.$OverrideName);
    }

    // Filling
    public function HMFillReferences($Settings){
        // Takes care of Reference Settings
        $Filtered = array_where(array_dot($Settings), function($Value, $Key) {
            if(strpos($Key, 'ReferenceSetting') !== false){
                return true;
            }
            return false;
        });

        foreach($Filtered as $Reference => $ReferenceName){
            array_forget($Settings, $Reference);

            if($this->HMHasReferenceSetting($ReferenceName)){
                $ReferenceSettings = array_dot($this->HMGetReferenceSetting($ReferenceName));
                if(strpos($Reference, '.') === false){
                    $ReferenceLocation = '';
                } else {
                    $ReferenceLocation = substr($Reference, 0, strrpos($Reference, '.')).'.';
                }
                foreach($ReferenceSettings as $ReferenceSettingName => $ReferenceSetting){
                    data_fill($Settings, $ReferenceLocation.$ReferenceSettingName, $ReferenceSetting);
                }
            }
        }

        // Takes care of Reference Limitations
        $Filtered = array_where(array_dot($Settings), function($Value, $Key) {
            if(strpos($Key, 'ReferenceLimitation') !== false){
                return true;
            }
            return false;
        });

        foreach($Filtered as $Reference => $ReferenceName){
            array_forget($Settings, $Reference);

            if($this->HMHasReferenceLimitation($ReferenceName)){
                $ReferenceSettings = array_dot($this->HMGetReferenceLimitation($ReferenceName));
                if(strpos($Reference, '.') === false){
                    $ReferenceLocation = '';
                } else {
                    $ReferenceLocation = substr($Reference, 0, strrpos($Reference, '.')).'.';
                }
                foreach($ReferenceSettings as $ReferenceSettingName => $ReferenceSetting){
                    data_fill($Settings, $ReferenceLocation.$ReferenceSettingName, $ReferenceSetting);
                }
            }
        }

        // Takes care of Reference Overrides
        $Filtered = array_where(array_dot($Settings), function($Value, $Key) {
            if(strpos($Key, 'ReferenceOverride') !== false){
                return true;
            }
            return false;
        });

        foreach($Filtered as $Reference => $ReferenceName){
            array_forget($Settings, $Reference);

            if($this->HMHasReferenceOverride($ReferenceName)){
                $ReferenceSettings = array_dot($this->HMGetReferenceOverride($ReferenceName));
                if(strpos($Reference, '.') === false){
                    $ReferenceLocation = '';
                } else {
                    $ReferenceLocation = substr($Reference, 0, strrpos($Reference, '.')).'.';
                }
                foreach($ReferenceSettings as $ReferenceSettingName => $ReferenceSetting){
                    array_forget($Settings, $ReferenceLocation.$ReferenceSettingName);
                    data_fill($Settings, $ReferenceLocation.$ReferenceSettingName, $ReferenceSetting);
                }
            }
        }
        return $Settings;
    }
}