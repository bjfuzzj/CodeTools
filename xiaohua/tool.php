<?php

class Tools
{
    public static function getUTF($result)
    {
        $res = '' ;
        if(is_array($result))
        {
            foreach($result as $key => $value) 
            {
                if(is_array($result[$key])) 
                {
                    $res[$key] = self::getUTF($value);
                }
                else 
                {
                    $res[$key] = @iconv("GBK", "UTF-8//IGNORE", $value);
                }
            }
        }
        else 
        {
            $res = @iconv("GBK", "UTF-8//IGNORE", $result);
        }
        return $res;
    } 
}

