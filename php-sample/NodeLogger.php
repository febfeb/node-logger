<?php
namespace app\components;

/**
 * Class NodeLogger
 * @package app\components
 *
 * @author febfeb
 * @see http://github.com/febfeb/node-logger
 */

class NodeLogger {
    public static function sendLog($message){
        if(!is_string($message)){
            $message = NodeLogger::renderJSON($message);
        }
        
        $fields = array(
            'message' => $message
        );

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, "http://localhost:1337/ws");
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1); 
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($fields));
        $output = curl_exec($ch); 
        curl_close($ch);
    }
    
    private static function renderJSON($jsonObj) {
        $json = \yii\helpers\Json::encode($jsonObj);
        $result = '';
        $level = 0;
        $prev_char = '';
        $in_quotes = false;
        $ends_line_level = NULL;
        $json_length = strlen($json);

        for ($i = 0; $i < $json_length; $i++) {
            $char = $json[$i];
            $new_line_level = NULL;
            $post = "";
            if ($ends_line_level !== NULL) {
                $new_line_level = $ends_line_level;
                $ends_line_level = NULL;
            }
            if ($char === '"' && $prev_char != '\\') {
                $in_quotes = !$in_quotes;
            } else if (!$in_quotes) {
                switch ($char) {
                    case '}': case ']':
                        $level--;
                        $ends_line_level = NULL;
                        $new_line_level = $level;
                        break;

                    case '{': case '[':
                        $level++;
                    case ',':
                        $ends_line_level = $level;
                        break;

                    case ':':
                        $post = " ";
                        break;

                    case " ": case "\t": case "\n": case "\r":
                        $char = "";
                        $ends_line_level = $new_line_level;
                        $new_line_level = NULL;
                        break;
                }
            }
            if ($new_line_level !== NULL) {
                $result .= "\n" . str_repeat("\t", $new_line_level);
            }
            $result .= $char . $post;
            $prev_char = $char;
        }

        return $result;
    }
}