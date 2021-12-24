<?php
/**
 * -----------| Insert 插入记录SQL语句 |-----------
 * @category betterlife
 * @package core.db.sql.util.crud
 * @author skygreen
 */
class Crud_Sql_Insert extends Crud_SQL {
    /**
     * @var array 表所有列名数组 
     */
    private $columns;
    /**
     * @var array 表所有列值数组，与列名数组元素一一对应
     */
    private $values;
    /**
     * 创建Insert 表名字符串
     * @param string $tableorclassName 表名|类名[映射表]
     * @return Crud_Sql_Insert 
     */
    public function insert($tableorclassName) {
        if ( class_exists($tableorclassName) ) {
            $this->tableName = Config_Db::orm( $tableorclassName );
        } else {
            $this->tableName = $tableorclassName;
        }
        return $this;
    }

    /**
     * 创建values Insert语句子字符串
     * 
     * @param mixed $values values Insert语句子字符串数组
     * 未使用预处理SQL语句
     * 
     * 1. set("id=1","name='sky'")
     * 2. set(array("id"=>"1","name"=>"sky"))
     * 
     * 使用预处理SQL语句
     * 
     * 1. set("id=?","name='?'")
     * 2. set(array("id"=>"1","name"=>"sky"))
     * 
     * @param $type_rep 替代符的类型。1:$, 其他:?
     * @return Crud_Sql_Insert 其中:values Insert语句子字符串
     */
    public function values($values, $type_rep = null) {
        $count = 1;
        foreach ($values as $key => $value) {
            $this->columns .= $key . ",";
            if ( $this->isPreparedStatement ) {
                if ( $type_rep && $type_rep == 1 ) {
                    $this->values .= '$' . ($count++) . ',';
                } else {
                    $this->values .= "?,";
                }
            } else {
                $this->values .= "'" . $value . "',";
            }
        }
        $this->columns = substr($this->columns, 0, strlen($this->columns) - 1);
        $this->values  = substr($this->values, 0, strlen($this->values) - 1);
        return $this;
    }

    /**
     * 生成需要的完整的SQL语句
     * @return string SQL完整的语句
     */
    public function result() {
        $this->query  = self::SQL_INSERT . $this->tableName;
        $this->query .= " (" . $this->columns . ")";
        $this->query .= self::SQL_INSERT_VALUE . "(" . $this->values . ")";
        return $this->query;
    }
    
    /**
     * 从插入Insert Sql 语句中获取表名
     * 
     * 算法说明: 
     * 
     *     Insert SQl语句的形式如下: 
     * 
     *       1. INSERT INTO Store_Information (store_name, Sales, Date) SELECT store_name, Sales, Date FROM Sales_Information WHERE Year(Date) = 1998
     *       2. INSERT INTO Persons VALUES ('Gates', 'Bill', 'Xuanwumen 10', 'Beijing')
     * 
     *     表名都是第三个单词，单词之间可能因为人为会有一到多个空格。
     * 
     *     所以从插入Insert Sql 语句中获取第三个单词即可。
     * @param type $sqlstring 插入Insert Sql 语句
     * @return string 表名
     */
    public static function tablename($sqlstring) {
        if ( isset($sqlstring) ) {
            $sql_need = UtilString::word_trim( $sqlstring, 3 );
            if ( isset($sql_need) ) {
                $tablenamepart = preg_split("/[\s]+/", $sql_need);
                if ( count($tablenamepart) == 3 ) {
                    $tablename = $tablenamepart[2];
                    return $tablename;
                }
            }
        }
        return null;
    }
}
?>
