<?php

namespace Bdr\Migrate;

use \Bdr\Migrate\Install, PDO, PDOException, Exception;

class Migrate
{

    public static function install(\Bdr\Sistema $sistema)
    {
        global $pdo;
        try {
            $pdo = new PDO('mysql:dbname=' . $sistema->database . ';host=localhost;charset=utf8', $sistema->db_user, $sistema->db_pass);
            $pdo->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
            $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch (PDOException $e) {
            try {
                $pdo = new PDO('mysql:host=localhost;charset=utf8', $sistema->db_user, $sistema->db_pass);
                $pdo->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
                $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                if (!\Bdr\Vendor\Database::dbactionf("CREATE DATABASE " . $sistema->database . ";", 1))
                    throw new Exception('Não foi possível criar o banco de dados');
            } catch (PDOException $e) {
                throw new Exception("Não há conexão disponivel ou as credenciais estão incorretas... O sistema não tem para onde ir daqui...");
            }
        }
        unset($pdo);
        \Bdr\Vendor\Database::init($sistema);
        if (!Migrate::checkTable('migrations')) {
            echo "<h3> Installando as classes de instalação do sistema</h3>";
            $installed = array();
            foreach (glob(__DIR__ . "/install/*.php") as $filename) {
                require_once $filename;
                $named = substr($filename, strrpos($filename, '/') + 1, -4);
                if (class_exists($named)) {
                    $migrate = new $named();
                    if (method_exists($migrate, 'up') && is_callable(array($migrate, 'up'))) {
                        $migrate->up();
                        $installed[] = $named;
                    }
                }
            }
            $Migrated = new Migrate();
            foreach ($installed as $table_name) {
                $Migrated->insert('migrations', array('filename' => $table_name));
            }
        }
        Migrate::migrateUp();
        echo "<script>window.location = '" . \Bdr\Config::APPURL . "';</script>";
    }

    /**
     * @param String $tableName
     * @param array $tableFields
     */
    public function create($tableName, $tableFields = array(array('name' => '', 'type' => '', 'default' => '', 'pk' => false)))
    {
        $sql = "CREATE TABLE {$tableName} (";
        foreach ($tableFields as $field) {
            $sql .= "`" . $field['name'] . "` " . $field['type'] . " " . @$field['default'] . ((isset($field['pk']) && $field['pk'] == true) ? " PRIMARY KEY" : "") . ",";
        }
        \Bdr\Vendor\Database::dbactionf(substr($sql, 0, -2) . ");", 1);
    }

    public function insert($tableName, $fieldValues = array(), $multipleInserts = false)
    {
        if ($multipleInserts) {
            // In this case, fieldValues must be an array of FieldValues
            foreach ($fieldValues as $fieldRow) {
                $fields = "";
                $values = "";
                foreach ($fieldRow as $key => $value) {
                    $fields .= "`" . $key . "`, ";
                    $values .= "'" . $value . "', ";
                }
                $sql = "INSERT INTO {$tableName} (" . substr($fields, 0, -2) . ") VALUES (" . substr($values, 0, -2) . ");";
                \Bdr\Vendor\Database::dbactionf($sql, 1);
            }
        } else {
            $fields = "";
            $values = "";
            foreach ($fieldValues as $key => $value) {
                $fields .= "`" . $key . "`, ";
                $values .= "'" . $value . "', ";
            }
            $sql = "INSERT INTO {$tableName} (" . substr($fields, 0, -2) . ") VALUES (" . substr($values, 0, -2) . ");";
            \Bdr\Vendor\Database::dbactionf($sql, 1);
        }
    }

    public static function checkMigrateUp($condition)
    {
        try {
            if (\Bdr\Vendor\Database::dbaction("SELECT filename FROM migrations WHERE `filename` = '" . $condition . "' LIMIT 1", $r, $n))
                if ($rs = $r->fetch())
                    return true;

            return false;
        } catch (Exception $e) {
            return false;
        }
    }

    public static function checkTable($table_name)
    {
        try {
            if (\Bdr\Vendor\Database::dbaction("SHOW TABLES LIKE '" . $table_name . "'", $r, $n))
                if ($rs = $r->fetch())
                    return true;

            return false;
        } catch (Exception $e) {
            return false;
        }
    }

    public static function migrateUp()
    {
        foreach (glob(__DIR__ . "/migrate/*.php") as $filename) {
            $named = substr($filename, strrpos($filename, '/') + 1, -4);
            if (!Migrate::checkMigrateUp($named)) {
                include $filename;
                $migrate = new $named();
                $migrate->up();
                \Bdr\Vendor\Database::dbactionf("INSERT INTO migrations (`filename`) VALUES ('" . $named . "')");
            }
        }
    }

}