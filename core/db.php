<?php
/**
 * 数据库类
 *
 * @author LincolnZhou<875199116@qq.com>
 * @copyright LincolnZhou<875199116@qq.com>
 * @date 2016-1-10
 */
class Db
{
    /**
     * 数据库连接信息
     * @var PDO
     */
    private $pdo = false;

    /**
     * Db constructor.
     */
    public function __construct()
    {
        try {
            $config = $GLOBALS['config']['db'];
            $this->pdo = new \PDO('mysql:host=' . $config['host'].';port='.$config['port'].';dbname='.$config['dbname'].';charset='.$config['charset'], $config['username'], $config['password']);

            return $this->pdo;
        } catch (Exception $e) {
            //TODO 写入日志
            echo 'catch connection exception, info : ' . $e->__toString();

            return false;
        }
    }

    /**
     * 获取PDO实例
     * @return DB
     */
    public static function getInstance()
    {
        $key = getmypid(); //获取进程Id
        $instance = array();

        if (empty($instance[$key])) {
            $instance[$key] = new self();
        }

        return $instance[$key];
    }


    /**
     * 执行SQL语句
     * @param string $sql SQL语句
     * @param array $param 绑定参数
     * @return mixed
     */
    public function query($sql, $param = array())
    {
        $statement = $this->pdo->prepare($sql);

        if (!empty($param)) {
            foreach ($param as $key => $item) {
                $statement->bindParam(':' . $key, $item);
            }
        }

        $statement->execute();

        return $statement->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * 插入数据
     * @param string $table 数据表名
     * @param array $data 数据
     * @return bool|string
     */
    public function insert($table, $data)
    {
        $columns = array();
        $replace = array();
        $params = array();

        foreach ($data as $field => $value) {
            $columns[] = '`' . $field . '`';
            $replace[] = ':' . $field;
            $params[':' . $field] = $value;
        }

        $columns = '(' . implode(',', $columns) . ')';
        $replace = '(' . implode(',', $replace) . ')';
        $sql = implode(' ', array(
            'INSERT INTO',
            $table,
            $columns,
            'VALUES',
            $replace
        ));

        $statement = $this->pdo->prepare($sql);
        foreach ($params as $key => $item) {
            $statement->bindValue($key, $item);
        }

        $result = $statement->execute();

        if ($result == false) {
            //TODO 写入日志
            return false;
        }

        return $this->pdo->lastInsertId();
    }
}