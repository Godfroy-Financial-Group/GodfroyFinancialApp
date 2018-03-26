<?php

/**
 * Newsletter short summary.
 *
 * Newsletter description.
 *
 * @version 1.0
 * @author andre
 */
class ApplicationSetting
{
    public $ID;
    public $Name;
    public $Grouping;
    public $Value;

    public function __construct() { }

    public static function FromAll(?int $id, string $name, string $group, string $value) : ApplicationSetting{
        $appSetting = new ApplicationSetting();
        $appSetting->ID = $id;
        $appSetting->Name = $name;
        $appSetting->Grouping = $group;
        $appSetting->Value = $value;
        return $appSetting;
    }
}

?>