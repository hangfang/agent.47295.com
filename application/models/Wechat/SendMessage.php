<?php
defined('BASE_PATH') OR exit('No direct script access allowed');

class Wechat_SendMessageModel extends BaseModel {
    public static $_table = 'send_message';
    public static $_database = 'wechat';
}