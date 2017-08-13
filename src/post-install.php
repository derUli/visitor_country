<?php
$migrator = new DBMigrator("module/visitor_country", ModuleHelper::buildModuleRessourcePath("visitor_country", "sql/up"));
$migrator->migrate();