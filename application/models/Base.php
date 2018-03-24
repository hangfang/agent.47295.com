<?php
defined('BASE_PATH') OR exit('No direct script access allowed');

class BaseModel
{
    public static $_error = '';
    public static $_table = '';
    public static $_database = '';

    public static function error()
    {
        return static::$_error;
    }

    /**
     * @todo 请求远程接口
     * @return json
     * @author fanghang@fujiacaifu.com
     */
    public static function call($method, $args)
    {
        $api = get_var_from_conf('api');
        if (!isset($api[$method])) {
            $error = get_var_from_conf('error');
            lExit(404, $error[404]['message']);
            return false;
        }
        // var_dump($args);die;
        $param = array_merge($api[$method], $args);
        return http($param);
    }

    /**
     * 返回单条记录
     * @param array $where 查询条件
     * @param string $field 返回字段
     * @param mixed $limit 分页
     * @param mixed $order 排序
     * @param mixed $group 分组
     * @return mixed
     */
    public static function getRow($where=[], $field = '*', $limit=[], $order = '', $group = '')
    {
        $method = 'getList';
        if (method_exists(get_called_class(), $method)) {
            $result = self::$method($where, $field, $limit, $order, $group);    // 不使用 static
            return empty($result) ? [] : $result[0];
        }

        log_message('error', get_called_class() . '->' . $method . ' not exists!');
        return false;
    }
    /**
     * 更新数据库记录
     * @param array $update 要更新的数据
     * @param array $where 更新数据的条件
     * @return boolean
     */
    public static function update($update, $where)
    {
        if (empty(static::$_database) || empty(static::$_table)) {
            return [];
        }
        
        BaseModel::$_database = static::$_database;
        BaseModel::$_table = static::$_table;
        $columns = BaseModel::columns();
        $columns = array_column($columns, 'COLUMN_NAME');
        foreach($update as $key=>$value){
            if(!in_array($key, $columns)){
                unset($update[$key]);
            }
        }

        if (empty($update)) {
            log_message('error', 'sql: update ' . static::$_database . '.' . static::$_table . ' failed, param '. print_r($update, true) .' empty.');
            return false;
        }

        $db = Database::getInstance(static::$_database);

        !empty($where) && $db->where($where);
        $query = $db->set($update)->update(static::$_table);
        if ($query === false) {
            self::$_error = 501;
            return false;
        }

        return $query;
    }

    /**
     * 删除数据库记录
     * @param array $where 删除数据的条件
     * @return boolean
     */
    public static function delete($where=[])
    {
        if (empty(static::$_database) || empty(static::$_table)) {
            return [];
        }

        $db = Database::getInstance(static::$_database);
        $where && $db->where($where);
        $query = $db->delete(static::$_table);
        if ($query === false) {
            static::$_error = 501;
            return false;
        }

        return $query;
    }

    /**
     * 替换数据库记录
     * @param array $data 要替换的数据
     * @return boolean
     */
    public static function replace($data)
    {
        if (empty(static::$_database) || empty(static::$_table)) {
            return [];
        }
        
        BaseModel::$_database = static::$_database;
        BaseModel::$_table = static::$_table;
        $columns = BaseModel::columns();
        $columns = array_column($columns, 'COLUMN_NAME');
        foreach($data as $key=>$value){
            if(!in_array($key, $columns)){
                unset($data[$key]);
            }
        }

        if (empty($data)) {
            log_message('error', 'sql: replace into ' . static::$_database . '.' . static::$_table . ' failed, param '. print_r($data, true) .' empty.');
            return false;
        }
        
		$db = Database::getInstance(static::$_database);
        $query = $db->replace(static::$_table, $data);
        if ($query === false) {
            static::$_error = 501;
            return false;
        }

        return $query;
    }

    /**
     * 插入数据库记录
     * @param array $data 要插入的数据
     * @return mixed
     */
    public static function insert($data)
    {
        if (empty(static::$_database) || empty(static::$_table)) {
            return [];
        }

        BaseModel::$_database = static::$_database;
        BaseModel::$_table = static::$_table;
        $columns = BaseModel::columns();
        $columns = array_column($columns, 'COLUMN_NAME');
        foreach($data as $key=>$value){
            if(!in_array($key, $columns)){
                unset($data[$key]);
            }
        }
        if (empty($data)) {
            log_message('error', 'sql: insert into ' . static::$_database . '.' . static::$_table . ' failed, param '. print_r($data, true) .' empty.');
            return false;
        }

        $db = Database::getInstance(static::$_database);
        $query = $db->insert(static::$_table, $data);
        if ($query === false) {
            static::$_error = 501;
            return false;
        }

        return $query;
    }

    /**
     * 批量插入
     * @param array $fields ['f1', 'f2', ...]               或   [ ['f1'=>$v1, 'f2'=>$v2], ['f1'=>$v1, 'f2'=>$v2], ... ]
     * @param mixed $data [ [$v1, $v2], [$v1, $v2], ... ]   或   null
     * @param string $insertOrUpdateKey 用于执行批量更新（如果记录不存在会插入数据！）
     * @return array|bool|mixed
     */
    public static function batchInsert(array $fields, $data = null, $insertOrUpdateKey = '')
    {
        if (empty(static::$_database) || empty(static::$_table)) {
            return [];
        }

        if (empty($fields) || !is_array($fields)) {
            log_message('error', 'sql: insert into ' . static::$_database . '.' . static::$_table . ' failed, param '. print_r($fields, true) .' error.');
            return false;
        }

        /** @var Database_Drivers_Mysqli $db */
        $db = Database::getInstance(static::$_database);

        BaseModel::$_database = static::$_database;
        BaseModel::$_table = static::$_table;
        $columns = BaseModel::columns();
        $columns = array_column($columns, 'COLUMN_NAME');
        if (empty($data)) {
            if(!empty($fields[0])){
                $fieldDiff = array_diff(array_keys($fields[0]), $columns);
                foreach ($fields as &$item) {
                    foreach($fieldDiff as $_columnName){
                        unset($item[$_columnName]);
                    }
                }
            }else{
                $fieldDiff = array_diff(array_keys($fields), $columns);
                foreach($fieldDiff as $_columnName){
                    unset($fields[$_columnName]);
                }
            }
        }else{
            $diffIndexes = [];
            foreach ($fields as $_index=>$_fieldName) {
                if(!in_array($_fieldName, $columns)){
                    $diffIndexes[] = $_index;
                }
            }
            
            foreach($data as &$_data){
                foreach($diffIndexes as $_index){
                    unset($_data[$_index]);
                }
            }
        }

        $query = $db->batchInsert(static::$_table, $fields, $data, $insertOrUpdateKey);
        if ($query === false) {
            static::$_error = 501;
            return false;
        }

        return $query;
    }

    /**
     * 查询数据库记录
     * @param array $where 查询条件
     * @param mixed $field 返回字段名
     * @param mixed $limit 分页字段
     * @param mixed $order 排序字段
     * @param mixed $group 分组字段
     * @return boolean|array
     */
    public static function getList($where = [], $field = '*', $limit = array(), $order = '', $group = '')
    {
        if (empty(static::$_database) || empty(static::$_table)) {
            return [];
        }

        $db = Database::getInstance(static::$_database);
        !empty($where) && self::__where($where, $db);

        if (!empty($group)) {
            if (is_array($group)) {
                $group = implode(',', $group);
            }

            $db->groupBy($group);
        }

        if (!empty($order)) {
            if (is_array($order)) {
                $group = implode(',', $order);
            }

            $db->orderBy($order);
        }

        if (!empty($limit)) {
            if (!is_array($limit)) {
                $limit = explode(',', $limit);
            }

            if (isset($limit['limit']) && isset($limit['offset'])) {
                $db->limit($limit['limit'], $limit['offset']);
            }

            if (isset($limit[0]) && isset($limit[1])) {
                $db->limit($limit[1], $limit[0]);
            }
        }

        if ($field !== '*') {
            if (is_array($field)) {
                $field = implode(',', $field);
            }

            $db->select($field);
        }

        $query = $db->get(static::$_table);
        if ($query === false) {
            static::$_error = 501;
            return false;
        }

        return $db->resultArray();
    }

    /**
     * 执行一条sql语句
     * @param string $sql 要执行的sql语句
     * @return mixed
     */
    public static function query($sql)
    {
        if (empty(static::$_database)) {
            return [];
        }

        if (empty($sql)) {
            log_message('error', '$sql is empty.');
            return false;
        }

        $db = Database::getInstance(static::$_database);

        $query = $db->query($sql);
        if ($query === false) {
            static::$_error = 501;
            return false;
        }

        return $query;
    }

    /**
     * @todo 查询表字段定义
     * @return array|boolean
     */
    public static function columns()
    {
        if (empty(static::$_table)) {
            return [];
        }

        $db = Database::getInstance('information_schema');
        $where = ['`TABLE_NAME`' => static::$_table, '`TABLE_SCHEMA`' => static::$_database];

        $rt = $db->where($where)->get('`COLUMNS`');
        if ($rt === false) {
            static::$_error = 501;
            return false;
        }

        return $rt->resultArray();
    }

    /**
     * @todo 查询表定义
     * @return array|boolean
     */
    public static function tables($table)
    {
        if (empty(static::$_database)) {
            return [];
        }

        $db = Database::getInstance('information_schema');
        $where = ['TABLE_SCHEMA' => static::$_database];

        !empty($table) && $where['TABLE_NAME'] = $table;

        $rt = $db->where($where)->get('`TABLES`');
        if ($rt === false) {
            static::$_error = 501;
            return false;
        }

        return $rt->resultArray();
    }

    /**
     * 判断当前接口的uri是否在白名单(不鉴权)
     * @param string $uri 当前接口的uri
     * @return boolean
     */
    public static function whiteList($uri)
    {
        $white = [
            'shop_activity_index',
            'shop_activity_product',
            'shop_login_index',
            'shop_login_register',
            'shop_login_dologin',
            'shop_index_succ',
            'shop_index_index',
            'shop_product_detail',
            'shop_product_index',
            'shop_product_search',
            'wechat_auth_index',
            'wechat_auth_bind',
            'wechat_auth_code',
            'wechat_auth_doregister',
            'wechat_auth_index',
            'wechat_auth_login',
            'wechat_auth_register',
        ];

        return in_array($uri, $white);
    }

    /**
     * 统计
     * @param array $where
     * @param string $field
     * @param string $funcName
     * @return int
     */
    public static function count($where = [], $field = '*', $funcName = 'count', $limit=[], $order='', $group='')
    {
        $fieldName = 'fieldAggregate';
        $rt = self::getRow($where, "$funcName($field) as $fieldName", $limit, $order, $group);
        return empty($rt) ? 0 : $rt[$fieldName];
    }
    
    /**
     * 查询数据列表,$index作下标
     * @param array $where 查询条件
     * @param string $index 作为下标的字段名
     * @param string $field 返回字段名
     * @param mixed $limit 分页
     * @param mixed $order 排序
     * @param mixed $group 分组
     * @return array
     */
    public static function getIndexedList($where, $index='', $field='*', $limit='', $order='', $group=''){
        if($field!=='*' && strpos($index, $field)===false){
            $field .= ','.$index;
        }
        
        $list = static::getList($where, $field, $limit, $order, $group);
        if(empty($list)){
            return [];
        }

        if(empty($index) || !isset($list[0][$index])){
            return [];
        }
        
        $result = [];
        foreach($list as $_list){
            if(!isset($result[$_list[$index]])){
                $result[$_list[$index]] = [];
            }

            $result[$_list[$index]][] = $_list;
        }

        return $result;
    }
    
    private static function __where($where, $db){
        $isOr = false;//or查询
        foreach($where as $_index=>$_where){
            $_tmp = str_replace(' ', '', strtolower($_index));
            if($_tmp==='or'){
                $isOr = true;
                break;
            }
        }
        
        $firstLoop = true;
        foreach($where as $_index=>$_where){
            if($isOr){
                $firstLoop ? $db->groupStart() : $db->orGroupStart();
                $firstLoop = false;
                self::__where($_where, $db);
                $db->groupEnd();
                continue;
            }
            
            if(is_numeric($_index)){
                $firstLoop ? $db->groupStart() : $db->orGroupStart();
                self::__where($_where, $db);
                $db->groupEnd();
            }else{
                $db->where($_index, $_where);
            }
        }
    }
    
    public static function isAdmin(){
        return !empty($_SESSION['user']['user_type']) && $_SESSION['user']['user_type']==='admin';
    }
}
