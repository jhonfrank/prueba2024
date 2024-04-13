<?php

declare(strict_types=1);

namespace Src\Shared\Infrastructure\Persistence;

use PDO;
use PDOException;
use Exception;

/**
 * Clase PDO mysql
 */
class MySQLDatabase
{

    protected $pdo;

    /**
     * Constructor de la clase.
     */
    public function __construct()
    {
        try {
            $this->pdo = new PDO("mysql:host=" . $_ENV['DB_HOST'] . ";dbname=" . $_ENV['DB_NAME'] . ";port=" . $_ENV['DB_PORT'] . ";charset=" . $_ENV['DB_CHARSET'], $_ENV['DB_USER'], $_ENV['DB_PASSWORD']);
            $this->pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        }
        catch (PDOException $e) {
            throw new Exception($e->getMessage());
        }
    }
    
    /**
     * Ejecutar select query.
     * 
     * @param string $sql
     * @param array|null $params
     * 
     * @return array
     */
    public function select(string $sql, array $params = null): array
    {
        $stmt = $this->pdo->prepare($sql);

        if (!is_null($params)) {
            foreach ($params as $key => $val) {
                $stmt->bindValue($key, $val[0], $val[1]);
            }
        }

        try {
            $stmt->execute();
            $result = $stmt->fetchall(PDO::FETCH_ASSOC);
            return $result;
        }
        catch (PDOException $e) {
            throw new Exception($e->getMessage());
        }
    }

    /**
     * Ejecutar command sql.
     * 
     * @param string $sql
     * @param array|null $params
     * 
     * @return bool
     */
    public function exec(string $sql, array $params = null): bool
    {
        $stmt = $this->pdo->prepare($sql);

        if (!is_null($params)) {
            foreach ($params as $key => $val) {
                $stmt->bindValue($key, $val[0], $val[1]);
            }
        }

        try {
            $stmt->execute();
            return true;
        }
        catch (PDOException $e) {
            throw new Exception($e->getMessage());
        }
    }

    /**
     * Iniciar una transacción.
     */
    public function begin_transaction()
    {
        $this->pdo->setAttribute(PDO::ATTR_AUTOCOMMIT, 0);
        $this->pdo->beginTransaction();
    }

    /**
     * Ejecutar commit.
     */
    public function commit()
    {
        $this->pdo->commit();
        $this->pdo->setAttribute(PDO::ATTR_AUTOCOMMIT, 1);
    }

    /**
     * Ejecutar rollback.
     */
    public function rollback()
    {
        $this->pdo->rollBack();
        $this->pdo->setAttribute(PDO::ATTR_AUTOCOMMIT, 1);
    }
}