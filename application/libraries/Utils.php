<?php

class Utils {

    public function getData() {
        $CI = get_instance();
        $stream_clean = $CI->security->xss_clean($CI->input->raw_input_stream);
        return json_decode($stream_clean, true);
    }

    public function returnData($data) {
        ob_end_clean();
        header("Content-Type: application/json");
        header($data['status']);
        unset($data['status']);
        die(json_encode($data));
    }
}
?>