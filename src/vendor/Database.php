<?php

namespace Bdr\Vendor;

use PDO, PDOException, PDOStatement;

$dbvar = ""; // guarda dados sobre a utilização da base de dados
$dbc = 0; // SQL count
$pdo = null;

class Database
{

    //Inicio do banco de dados
    public static function init(\Bdr\Sistema $sistema)
    {
        global $pdo;
        try {
            $pdo = new PDO('mysql:dbname=' . $sistema->database . ';host=localhost;charset=utf8', $sistema->db_user, $sistema->db_pass);
            $pdo->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
            $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch (PDOException $e) {
            echo '<h3>Não foi possível conectar com o banco de dados</h3>';
            return false;
        }
        return true;
    }

    /**
     * @param $sql
     * @param PDOStatement $result
     * @param int $numrows
     * @param int $abortonerror
     * @return bool
     */
    public static function dbaction($sql, &$result, &$numrows, $abortonerror = 0, $maintablename = '')
    {
        global $dbvar, $dbc, $pdo;
        $dbc++;
        $dbvar .= $sql . "\n";
        try {
            $result = $pdo->query($sql);
            if ($result !== false) {
                if (strpos(" " . $sql, "SELECT") > 0) {
                    if (strpos($sql . ' ', 'LIMIT 1 ') > 0) {
                        return true; //Selects de uma linha retornam true sem setar $numrows porque não importa o tamanho total
                    }
                    if (strpos(preg_replace("/\([^)]+\)/", "", $sql), 'ORDER BY')) {
                        $sql = substr($sql, 0, strrpos($sql, "ORDER BY"));
                    } elseif (strpos($sql, 'LIMIT') > 0) {
                        $sql = substr($sql, 0, strpos($sql, 'LIMIT'));
                    }
                    $groupPos = 0;
                    if (strpos(preg_replace("/\([^)]+\)/", "", $sql), 'GROUP BY')) //se existe group by fora de parenteses pois pode ser na subquery
                        $groupPos = strrpos($sql, 'GROUP BY');
                    $fromPos = strpos($sql, str_replace("  ", " ", "FROM " . $maintablename . " "));
                    if ($groupPos > 0) {
                        $sql = substr($sql, 0, 7) . " COUNT(DISTINCT " . substr($sql, $groupPos + 9) . " ) " . substr($sql, $fromPos, $groupPos - $fromPos);
                    } else {
                        $sql = substr($sql, 0, 7) . " COUNT(*) " . substr($sql, $fromPos);
                    }
                    $rowCountQuery = $pdo->query($sql);
                    $dbvar .= $sql;
                    $numrows = $rowCountQuery->fetchColumn(); //Nesse caso vai retornar o total de registros possíveis removendo o LIMIT da query se tiver
                } else {
                    $numrows = $result->rowCount(); //aqui mostra quantas linha foram afetadas
                }
                return true;
            } else if ($abortonerror) {
                $err = print_r($result->errorInfo(), true);
                Database::reporterror($err . "\nEm: " . $sql);
                die ($err . "\n" . $sql);
            } else {
                Database::reporterror(print_r($result->errorInfo(), true) . "\nEm: " . $sql);
                $dbvar .= "ERR\n";
                return false;
            }
        } catch (PDOException $e) {
            echo "Erro na query:" . $sql;
            echo "<br> <br>";
            echo $e->getMessage();
            echo "Query History Stack Trace: " . $dbvar;
        }
    }

    public static function dbactionf($sql, $abortonerror = 0)
    {
        global $dbvar, $dbc, $pdo;
        $dbc++;
        $dbvar .= $sql . "\n";
        $exec = $pdo->exec($sql);
        if ($exec !== false) {
            return true;
        } elseif ($abortonerror) {
            $err = print_r($exec->errorInfo(), true);
            Database::reporterror($err . "\nEm: " . $sql);
            die($err . "\n" . $sql . "\n" . print_r($exec, true) . "\n" . print_r($pdo));
        } else {
            Database::reporterror(print_r($exec->errorInfo(), true) . "\nEm: " . $sql);
            $dbvar .= "ERR\n";
            return false;
        }
    }

    public static function lastID()
    {
        global $pdo;
        return $pdo->lastInsertId();
    }

    public static function reporterror($erro)
    {
        Log::erro($erro);
        Mail::reportError($erro);
    }

    public static function logout()
    {
        if (isset($_SERVER['HTTP_COOKIE'])) {
            $cookies = explode(';', $_SERVER['HTTP_COOKIE']);
            foreach ($cookies as $cookie) {
                $parts = explode('=', $cookie);
                $name = trim($parts[0]);
                setcookie($name, '', time() - 1000);
                setcookie($name, '', time() - 1000, '/');
            }
        }
        $app = \Bdr\Sistema::app();
        $router = Router::getRouter();
        @session_unset();
        @session_destroy();
        @session_start();
        $_SESSION['router'] = $router;
        $_SESSION['app'] = $app;
        sleep(1); // força cookies expirarem
    }

    public static function close()
    {
        global $pdo;
        unset($pdo);
    }

    function dbactionsr($sql, $index = 0, $abortonerror = 0)
    {
        global $dbvar, $dbc, $debugmode, $pdo;
        $dbc++;
        $dbvar .= $sql . "\n";
        $result = $pdo->query($sql);

        if ($result && $result->rowCount($result) > 0) {
            return $result->fetchColumn($index);
        } else if ($abortonerror) {
            $err = print_r($result->errorInfo());
            Database::reporterror($err . "\n\nEm: " . $sql);
            die ($err . "\n" . $sql);
        }
        return "";
    }

    public static function get_num_queries()
    {
        global $dbc;
        return $dbc;
    }
}

?>