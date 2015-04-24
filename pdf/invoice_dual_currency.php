<?php
$usd = get_usd();
$pdf .= '
        <div class="invoice_heading">
          <b>TAX INVOICE</b>
        </div>
    
    <br/>
    <div style="width:100%;padding-bottom:10px">'.billto_shipto(TEMPLATE_SHIPTO_BOX, $customer_info, $shipping_info).invoice($invoice_main, $payment_due_date).'</div>

    <table width="100%" cellspacing="0" class="border">
        <tr bgcolor="#cccccc">
            <th>No.</th>
            '.item_code_col().'
			      <th class="item_left">Description</th>
            <th>Qty</th>
            <th>Unit Price ('.$invoice_main->currencies_code.')</th>
            '.discount_col($discount_col).'
            <th>Total Exclusive GST</th>
            <th>GST Amount</th>
            <th>Total Exclusive of GST ('.$invoice_main->currencies_code.')</th>
            <th>Total Inclusive of GST ('.$usd->code.')</th>
            <th>Tax Code</th>
             </tr>';

        
        $i = 1;
        $total_item_amount = "";
        foreach ($item_val['item'] as $item) {

            $u_price_item = floatval($item['debit_amount']) + floatval($item['credit_amount']);
          
            if(UOM_QTY == 1){
                  if($item['disc_percent'] > 0){
                    $u_price = (floatval($u_price_item) / floatval($item['qty'])) / (floatval($item['uom_qty']) !=0 ? $item['uom_qty'] : 1) + floatval($item['disc_percent']);
                  }else{
                    $u_price = (floatval($u_price_item) + floatval($item['discval'])) / (floatval($item['uom_qty']) !=0 ? $item['uom_qty'] : 1) / floatval($item['qty']);
                  }
              
              }else{
                if($item['disc_percent'] > 0){
                    $u_price = (floatval($u_price_item) / floatval($item['qty'])) + floatval($item['disc_percent']);
                }else{
                    $u_price = (floatval($u_price_item) + floatval($item['discval'])) / floatval($item['qty']);
                }
              }
            $total_item_amount = $total_item_amount + $u_price_item;
            
            $percent = $item['disc_percent'] > 0 ? $item['discval'].'%' : $currencies->format($item['discval']);
            
            $pdf .='
    
        <tr>
            <td valign="top" align="center" class="item_first">' . $i . '</td>
			      '.item_code_col2($item['sku']).'
			      <td valign="top" class="item_left" width="25%">' . $item['description'] . '</td>
            <td valign="top" align="center" class="item">' . $item['qty'] . '</td>
            <td valign="top" align="center" class="item">' . $currencies->format($u_price) . '</td>';
            $pdf .= discount_item($discount_col,$percent);
            $pdf .='<td valign="top" align="center" class="item">' . $item['description_short'] . '</td>';            
            $pdf .='<td valign="top" class="item">' . $currencies->format($item['debit_amount'] + $item['credit_amount']) . '</td>
                    <td valign="top" class="item">' . $currencies->format($item['debit_amount'] + $item['credit_amount']) . '</td>
                    <td valign="top" class="item">' . $currencies->format($item['debit_amount'] + $item['credit_amount']) . '</td>
                    <td valign="top" align="center" class="item">' . $item['description_short'] . '</td>
        </tr>
        ';
            $i++;
        }

        $pdf .='
       <tr>
            <td></td>
			      '.add_col(TEMPLATE_ITEM_CODE).'
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            '.add_col($discount_col).'
            <td></td>
            <td></td>
            <td></td>
            <td></td>';
            if(UOM == 1){
            $pdf .='<td></td>'; 
             }
              if(UOM_QTY == 1){
                $pdf .='<td></td>';   
              }
            $pdf .='</tr></table>';


            $pdf .='<div style="text-align:right">
                 <table width="100%">
                    <tr>
                        <td align="right" width="70%"><b>Sub Total</b></td>
                        <td>' . $currencies->format_full($total_item_amount, true, $invoice_main->currencies_code, $invoice_main->currencies_value) . '</td>
                        <td>'. $usd->code . $currencies->format($total_item_amount*$usd->value) . '</td>
                    </tr>';
                if (in_array($item_val['dsc'], $item_val)) {
                    $discount = $invoice_main->discount;
                    $pdf .='<tr>
                                <td align="right"><b>Discount </b></td>
                                <td>' . $currencies->format_full($discount, true, $invoice_main->currencies_code, $invoice_main->currencies_value) . '</td>
                                <td>'. $usd->code . $currencies->format($discount*$usd->value) . '</td>
                            </tr>';
                }
                if (in_array($item_val['frt'], $item_val)) {
                    $freight = $invoice_main->freight;
                    $pdf .='<tr>
                                <td align="right" align="right"><b>Freight </b></td>
                                <td>' . $currencies->format_full($freight, true, $invoice_main->currencies_code, $invoice_main->currencies_value) . '</td>
                                <td>'.$usd->code . $currencies->format($freight*$usd->value) . '</td>
                            </tr>';
                }
                //if (in_array($item_val['tax'], $item_val)) {
                    $tax = $invoice_main->sales_tax;
                    $pdf .='<tr>
                                <td align="right"><b>GST @ 6%</b></td>
                                <td>' . $currencies->format_full($tax, true, $invoice_main->currencies_code, $invoice_main->currencies_value) . '</td>
                                <td>' . $usd->code . $currencies->format($tax*$usd->value) . '</td>
                            </tr>';
               // }
                if (in_array($item_val['ttl'], $item_val)) {
                    $total = $invoice_main->total_amount;
                    $pdf .='<tr> 
                                <td align="right"><b>Net Total </b></td>
                                <td>' . $currencies->format_full($total, true, $invoice_main->currencies_code, $invoice_main->currencies_value) . '</td>
                                <td>' . $usd->code . $currencies->format($total*$usd->value) . '</td>
                            </tr>';
                }
                $pdf .='</table>
                </div>';

        $pdf .= add_line('25%');

?>