<?php

declare(strict_types=1);

namespace App\Models;


use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Areas extends Model
{
    /**
     * Table name.
     *
     * @var string
     */
    protected $table = 'owid_areas';

    /** @var bool */
    public $timestamps = false;

    /** @var string[] */
    protected $casts = [
        'name'  =>  'string'
    ];

    /**
     * PK
     *
     * @var string
     */
    protected $primaryKey = 'id';

    /** @var string[] */
    protected $fillable = [
        'name'
    ];

    /**
     * Get a list of Areas in the database.
     *
     * @return array
     */
    public static function getAreas(): array
    {
        return DB::select(
            "SELECT `name` FROM `owid_areas`"
        );
    }

    /**
     * Get the area for a specific location.
     *
     * @param $location
     * @return mixed
     */
    public static function getAreaForLocation($location)
    {
        return DB::selectOne(
            "SELECT `owid_areas`.`name` FROM `owid_areas` " .
            "INNER JOIN `owid_locations` ON `owid_areas`.`id` = `owid_locations`.`continent_table` " .
            "AND `owid_locations`.`code` = '" . $location ."'"
        );
    }
}
