<?php

$pdf .='<div class="footer_area" style="padding-top:20px;padding-bottom:20px">
        <table width="100%">
            <tr>
                <th width="20%">GST Summary</th>
                <th width="20%">Amount ('.$invoice_main->currencies_code.')</th>
                <th width="20%">Amount ('.$usd->code.')</th>
                <th width="20%">GST ('.$invoice_main->currencies_code.')</th>
                <th width="20%">GST ('.$usd->code.')</th>
            </tr>';

        foreach($item_val['total_price_on_tax'] as $key => $val){
            $pdf .='
                <tr>
                    <td align="center">'.$key.'</td>
                    <td align="center">'.$currencies->format($val).'</td>
                    <td align="center">'.$currencies->format($val*$usd->value).'</td> 
                    <td align="center">'.$currencies->format($item_val['total_rate_percentage'][$key]).'</td>
                    <td align="center">'.$currencies->format($item_val['total_rate_percentage'][$key]*$usd->value).'</td>
                </tr>
            ';
        }
        
$pdf .= '</table></div>';
    
?>