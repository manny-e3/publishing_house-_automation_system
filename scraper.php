<?php
$html = file_get_contents('https://docs.google.com/forms/d/e/1FAIpQLSd35IkdcKL3Qm64D4RM0YPpOXmXwv45Xy-gXgmD7TAw6wBI9Q/viewform');
preg_match('/FB_PUBLIC_LOAD_DATA_ = (.*?);<\/script>/s', $html, $matches);
if (!empty($matches[1])) {
    $data = json_decode($matches[1], true);
    if (isset($data[1][1])) {
        foreach ($data[1][1] as $field) {
            $id = $field[0] ?? '';
            $title = $field[1] ?? '';
            $type = $field[3] ?? '';
            $typeMap = [0=>'Short Answer', 1=>'Paragraph', 2=>'Multiple Choice', 3=>'Dropdown', 4=>'Checkboxes', 5=>'Linear Scale', 7=>'Grid', 9=>'Date', 10=>'Time', 11=>'File Upload'];
            $typeName = $typeMap[$type] ?? "Type $type";
            if ($title) {
                echo "- $title ($typeName)\n";
                if (isset($field[4][0][1])) {
                    foreach ($field[4][0][1] as $opt) {
                        $val = $opt[0] ?? '';
                        echo "   * $val\n";
                    }
                }
            }
        }
    } else {
        echo "Could not find fields array.";
    }
} else {
    echo "Could not load data variable.";
}
