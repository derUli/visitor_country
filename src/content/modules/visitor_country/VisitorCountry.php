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
        if (isset($_COOKIE["visitor_country"])) {
            return;
        }
        $data = $controller->getGeoLocation();
        if ($data) {
            $country = $data["geoplugin_countryName"];
            if (StringHelper::isNotNullOrWhitespace($country)) {
                if (is_null($this->getCountryValue($country))) {
                    $this->createCountry($country);
                }
                $this->countryPlus1($country);
                setcookie("visitor_country", "1", time() + (60 * 60 * 24 * 5));
            }
        }
    }

    public function getSettingsHeadline()
    {
        return get_translation("visitor_countries");
    }

    public function getSettingsLinkText()
    {
        return get_translation("view");
    }

    public function uninstall()
    {
        $migrator = new DBMigrator("module/visitor_country", ModuleHelper::buildModuleRessourcePath("visitor_country", "sql/down"));
        $migrator->rollback();
    }

    public function settings()
    {
        return Template::executeModuleTemplate($this->moduleName, "list.php");
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