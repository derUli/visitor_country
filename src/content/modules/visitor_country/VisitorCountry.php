<?php

class VisitorCountry extends Controller
{

    private $moduleName = "visitor_country";

    public function beforeHttpHeader()
    {
        $controller = ModuleHelper::getMainController("geoPlugin");
        if (! $controller) {
            return;
        }
        $data = $controller->getGeoLocation();
        $country = $data["geoplugin_countryName"];
        if (is_null($this->getCountryValue($val))) {
            $this->createCountry($country);
        }
        $this->countryPlus1($country);
    }

    protected function createCountry($name)
    {
        Database::pQuery("INSERT INTO {prefix}visitor_countries
                         (name, value) values(?, ?)", array(
            strval($name),
            0
        ), true);
    }

    protected function getCountryValue($val)
    {
        $sql = "select value from {prefix}visitor_countries where name = ?";
        $args = array(
            strval($name)
        );
        $query = Database::pQuery($sql, $args, true);
        if (! Database::any($query)) {
            return null;
        }
        $result = Database::fetchObject($query);
        return $result->value;
    }

    public function getAllCountries($order = "name")
    {
        $sql = "select * from {prefix}visitor_countries order by $order";
        $query = Database::query($sql, true);
        $result = array();
        while ($row = Database::fetchObject($query)) {
            $result[] = $row;
        }
        return $result;
    }

    protected function countryPlus1($name)
    {
        $sql = "Update {prefix}visitor_countries set value = value + 1 where name = ?";
        $args = array(
            strval($name)
        );
        Database::pQuery($sql, $args, true);
    }
}