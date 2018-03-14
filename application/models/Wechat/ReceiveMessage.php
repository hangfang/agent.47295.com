<?php
defined('BASE_PATH') OR exit('No direct script access allowed');

class Wechat_ReceiveMessageModel extends BaseModel {
    public static $_table = 'receive_message';
    public static $_database = 'wechat';
}