<?php
defined('BASE_PATH') OR exit('No direct script access allowed');
/**
 * 数据库单例，用来实例化指定的Mysql驱动
 * @author fangh@me.com
 */
class Database{
    /**
     * @var Database_Drivers_Pdo_Mysql
     */
    public static $_instance;
    private function __construct(){}
    private function __clone(){}

    /**
     * 获取Mysql驱动类的实例
     * @param string $default_group 数据库组名
     * @return Database_Drivers_Pdo_Mysql Mysql驱动类的实例
     */
    public static function getInstance($default_group='agent'){
        if(PHP_ENV==='online_test' && strpos($default_group, 'information_schema')===false){
            $default_group = 'test_'.$default_group;
        }
        
        if(! $config = Yaf_Registry::get('db_config')){
            $config = new Yaf_Config_Ini(BASE_PATH . '/conf/database.ini', ini_get('yaf.environ'));
            $config = $config->toArray();
            $config = $config['database'][$default_group][rand(0,count($config)-1)];
        }

        $key = 'db_instance_'.$default_group;
        if(self::$_instance = Yaf_Registry::get($key)){
            if(self::$_instance === false){
                lExit(1025);
            }

            //self::$_instance->selectDb($config['database']);
            return self::$_instance;
        }

        $dbdriver = strtolower($config['dbdriver']);
        if($dbdriver==='mysqli'){
            $driverName = ucfirst($config['dbdriver']);
            $driver = 'Database_Drivers_'.$driverName;
            if (class_exists($driver) ){
                try{
                    self::$_instance = new $driver($config, $default_group);
                }catch(Exception $e){
                    log_message('error', 'mysqli: 数据库连接失败!');
                    self::$_instance = false;
                }
            }else{
                log_message('error', 'class not found: '. $driver);
                self::$_instance = false;
            }
        }else if($dbdriver==='pdo'){
            if(preg_match('/([^:]+):/', $config['dsn'], $matches)){
                $subdriver = isset($matches[1]) ? $matches[1] : 'mysql';
            }
            $subdriver = empty($subdriver) ? 'mysql' : strtolower($subdriver);
            $driver = 'Database_Drivers_Pdo_'. ucfirst($subdriver);
            // Check for a subdriver
            if (class_exists($driver) ){
                try{
                    self::$_instance = new $driver($config, $default_group);
                }catch(Exception $e){
                    log_message('error', 'pdo: 数据库连接失败!');
                    self::$_instance = false;
                }
            }else{
                log_message('error', 'class not found: '. $driver);
                self::$_instance = false;
            }
        }else{
            log_message('error', 'database driver was not surported, need mysqli or pdo');
            self::$_instance = false;
        }

        if(!is_cli()){
            if(self::$_instance === false){
                lExit(1025);
            }
        }

        //self::$_instance->selectDb($config['database']);
        Yaf_Registry::set($key, self::$_instance);
        return self::$_instance;
    }
}