<?php
/**
 * Created by PhpStorm.
 * User: Acer
 * Date: 2018/12/7
 * Time: 12:01
 */

namespace App\Components;


class ChartManager
{
    public static function newChart(string $type = "bar", array $data = [], array $options = [
        "animation" => true
    ], float $width = 400, float $height = 400)
    {
        if (count($options) == 0) {
            $options = [];
        }

        return view('admin.chart.chart', [
            "type" => $type,
            "data" => json_encode($data),
            "options" => json_encode($options),
            "width" => $width,
            "height" => $height,
        ]);
    }

    public static function line(array $labels, string $data_label, array $data, $canvas_id = "main", float $width = 400.0, float $height = 400.0)
    {
        return view('admin.echart.line', [
            "labels" => json_encode($labels),
            "data_label" => $data_label,
            "data" => json_encode($data),
            "canvas_id" => $canvas_id,
            "width" => $width,
            "height" => $height]);
    }
}