<?php


namespace App\Models;


use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Locations extends Model
{
    /** @var string */
    protected $table = 'owid_locations';

    /** @var bool */
    public $timestamps = false;

    /** @var string[] */
    protected $casts = [
        'code'  =>  'string',
        'name'  =>  'string',
    ];

    /** @var null */
    protected $primaryKey = null;

    /** @var bool */
    public $incrementing = false;

    /**
     * Get all locations from database.
     *
     * @return array
     */
    public static function getLocations(): array
    {
        return DB::select(
            "SELECT `code`, `name` FROM `owid_locations`"
        );
    }

    /**
     * Get the latest details.
     *
     * @return array
     */
    public static function getGlobalDetails(): array
    {
        $areas = Areas::getAreas();
        $details = [];

        foreach ($areas as $area) {
            $t = 'owid_details_'.$area->name;

            $s = "`country_code`,`name`,`last_updated`,`total_cases`,`new_cases`," .
                 "`total_deaths`,`new_deaths`,`icu_patients`,`hosp_patients`,`total_tests`," .
                 "`total_vaccinations`,`people_vaccinated`,`people_fully_vaccinated`";

            $ad = DB::select("SELECT " . $s . " FROM `".$t."` AS T1 " .
                            "INNER JOIN `owid_locations` ON T1.`country_code` = `owid_locations`.`code`" .
                            "WHERE `last_updated` = (SELECT MAX(`last_updated`) " .
                            "FROM `".$t."` AS T2 ".
                            "WHERE `T1`.`country_code` = `T2`.`country_code`)");
            $details = array_merge($details, $ad);
        }

        return $details;
    }

    /**
     * Get latest details for a specific location.
     *
     * @param $location
     * @return object|null
     */
    public static function getAllDetailsForLocation($location): ?object
    {
        $area = Areas::getAreaForLocation($location);
        if (!$area) {
            return null;
        }

        $table = sprintf("owid_details_%s", $area->name);

        $details = DB::selectOne(
            "SELECT * FROM `".$table."` AS T1 ".
            "WHERE T1.`country_code` = '".$location."' AND ".
            "T1.`last_updated` = (SELECT MAX(`last_updated`) FROM `".$table."` AS T2) "
        );
        if (!$details) {
            return null;
        }

        return $details;
    }

    /**
     * Get all details for a specific location.
     *
     * @param $location
     * @return array|null
     */
    public static function getHistoryForLocation($location): ?array
    {
        $area = Areas::getAreaForLocation($location);
        if (!$area) {
            return null;
        }

        $table = sprintf("owid_details_%s", $area->name);

        $history = DB::select(
            "SELECT * FROM `".$table."`".
            "WHERE `country_code` = '".$location."'"
        );
        if (count($history) == 0) {
            return null;
        }

        return $history;
    }
}
