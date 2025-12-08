<?php
class SimpleXLSXGen {
    public static function fromArray($arr) {
        $obj = new self();
        $obj->data = $arr;
        return $obj;
    }
    public function downloadAs($name) {
        // output as CSV for lightweight alternative
        header('Content-Type: text/csv; charset=utf-8');
        header('Content-Disposition: attachment; filename="' . $name . '"');
        $out = fopen('php://output', 'w');
        foreach($this->data as $row) {
            fputcsv($out, $row);
        }
        fclose($out);
        exit;
    }
}
?>