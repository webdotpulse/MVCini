<?php
namespace App\Controllers;

use App\Core\Controller;
use App\Core\I18n;

class ToolsController extends Controller
{
    /**
     * Main tools demo page
     */
    public function index()
    {
        $this->render('tools/index', [
            'title' => I18n::get('tools.title') ?? 'Tools'
        ]);
    }

    /**
     * Calculator sub-page
     */
    public function calculator()
    {
        $result = '';
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $num1 = $_POST['num1'] ?? 0;
            $num2 = $_POST['num2'] ?? 0;
            $op = $_POST['op'] ?? '+';

            switch ($op) {
                case '+': $result = $num1 + $num2; break;
                case '-': $result = $num1 - $num2; break;
                case '*': $result = $num1 * $num2; break;
                case '/': $result = $num2 != 0 ? $num1 / $num2 : 'Div/0 Error'; break;
            }
        }

        $this->render('tools/calculator', [
            'result' => $result,
            'title' => I18n::get('tools.calculator') ?? 'Calculator'
        ]);
    }

    /**
     * Battery sizer sub-page
     */
    public function batterySizer()
    {
        $result = '';
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $power = $_POST['power'] ?? 0; // Watts
            $hours = $_POST['hours'] ?? 0; // Hours
            $voltage = $_POST['voltage'] ?? 12; // Volts

            if ($power > 0 && $hours > 0 && $voltage > 0) {
                // capacity in Ah = (Power * Hours) / Voltage
                // Assuming 20% efficiency loss
                $capacity = ($power * $hours) / $voltage;
                $recommended = $capacity * 1.2;
                $result = round($recommended, 2) . ' Ah';
            }
        }

        $this->render('tools/battery_sizer', [
            'result' => $result,
            'title' => I18n::get('tools.battery_sizer') ?? 'Battery Sizer'
        ]);
    }
}
