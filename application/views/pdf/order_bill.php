<?php
error_reporting(0);
function amt_in_word($number)
{
    $no = round($number);
    $point = round($number - $no, 2) * 100;
    $hundred = null;
    $digits_1 = strlen($no);
    $i = 0;
    $str = array();
    $words = array('0' => '', '1' => 'one', '2' => 'two',
    '3' => 'three', '4' => 'four', '5' => 'five', '6' => 'six',
    '7' => 'seven', '8' => 'eight', '9' => 'nine',
    '10' => 'ten', '11' => 'eleven', '12' => 'twelve',
    '13' => 'thirteen', '14' => 'fourteen',
    '15' => 'fifteen', '16' => 'sixteen', '17' => 'seventeen',
    '18' => 'eighteen', '19' =>'nineteen', '20' => 'twenty',
    '30' => 'thirty', '40' => 'forty', '50' => 'fifty',
    '60' => 'sixty', '70' => 'seventy',
    '80' => 'eighty', '90' => 'ninety');
    $digits = array('', 'hundred', 'thousand', 'lakh', 'crore');
    while ($i < $digits_1) 
    {
        $divider = ($i == 2) ? 10 : 100;
        $number = floor($no % $divider);
        $no = floor($no / $divider);
        $i += ($divider == 10) ? 1 : 2;
        if ($number) 
        {
            $plural = (($counter = count($str)) && $number > 9) ? 's' : null;
            $hundred = ($counter == 1 && $str[0]) ? ' and ' : null;
            $str [] = ($number < 21) ? $words[$number] .
            " " . $digits[$counter] . $plural . " " . $hundred
            :
            $words[floor($number / 10) * 10]
            . " " . $words[$number % 10] . " "
            . $digits[$counter] . $plural . " " . $hundred;
        } 
        else $str[] = null;
    }
    $str = array_reverse($str);
    $result = implode('', $str);
    return $result;
}
?>
<!DOCTYPE html>
<html lang="en">
    <head>
        <!-- META SECTION -->
        <title>Order-Bill</title>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
        <meta http-equiv="X-UA-Compatible" content="IE=edge" />
        <meta name="viewport" content="width=device-width, initial-scale=1" />
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
          <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
          <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.16.0/umd/popper.min.js"></script>
          <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    </head>
    <body>
        <table width="100%" style="font-size:12px;text-align:center;" cellspacing="0">
            <tr>
                <th width="15%">
                    <img src="img/logo.jpg" height="100px" width="100px">            
                </th>
                <td width="85%" style="text-align:right">
                    <strong style="font-size: 20px;color:red">Herbal India</strong><br>
                    <i style="font-size: 14px;">Jawahar market, church road, <br>Ambikapur, Chhattisgarh</i><br>
                    <i style="font-size: 14px;">Email: herbalindia@gmail.com</i>
                </td>
            </tr>
        </table>
        <hr>
        <table width="100%" style="font-size:12px;padding:20;" cellspacing="0">
            <tr>
                <td width="60%" rowspan="7">
                    <p><i>Billed To - </i></p>
                    <p>
                        <b style="font-size:16px;"><i><?=strtoupper($user['name'])?></i></b><br>
                        <i><?=ucfirst($user_address['address'])?></i><br>
                        <i>Mobile :<?=$user['mobile']?></i>
                    </p>
                </td>
                <td width="18%">Order ID</td>
                <td width="2%">:</td>
                <td width="20%">BA<?=sprintf("%03d",$orders['order_id'])?></td>
            </tr>
            <tr>
                <td width="18%">Order Date</td>
                <td width="2%">:</td>
                <td width="20%"><?=$orders['date']?></td>
            </tr>
            <tr>
                <td>Order By</td>
                <td>:</td>
                <td><?=$user['name']?></td>
            </tr>
            <tr>
                <td>Delivery Address</td>
                <td>:</td>
                <td><?=$user_address['address']?></td>
            </tr>
            <tr>
                <td>Payment Mode</td>
                <td>:</td>
                <td><?=$orders['payment_mode']?></td>
            </tr>
            <tr>
                <td>Payable Amount:</td>
                <td>:</td>
                <td>&#8377; <?=$orders['txn_amount']?></td>
            </tr>
        </table>
        
        <table width="100%" class="table">
            <tr>
                <th width="5%">S.No.</th>
                <th width="35%">Item Name</th>
                <th width="15%">Size</th>
                <th width="15%">Price (Rs.)</th>
                <th width="15%">Quantity (Nos)</th>
                <th width="15%">Amount (Rs.)</th>
            </tr>
            <?php
            $i =0;
            $total_amt = 0;
            foreach($order_cart as $r)
            {
                $i++;
                $total_amt += ($r->price*$r->quantity);
                ?>
            <tr>
                <td><?=$i?></td>
                <td><?=$this->base->product_name($r->product_id)?></td>
                <td><?=$this->base->product_size($r->product_id)?></td>
                <td><?=$r->price?></td>
                <td><?=$r->quantity?></td>
                <td><?=bcdiv($r->price*$r->quantity,1,2)?></td>
            </tr>
            <?php
            }
            $delivery_charge = $orders['delivery_charge'] ? $orders['delivery_charge'] : 0;
            $payable_amount = $total_amt+$delivery_charge;
            ?>
            <tr>
                <td colspan="5" class="text-right">Total</td>
                <td><?=bcdiv($total_amt,1,2)?></td>
            </tr>
            <tr>
                <td colspan="5" class="text-right">Delivery Charge</td>
                <td><?=bcdiv($delivery_charge,1,2)?></td>
            </tr>
            <tr>
                <th colspan="5" class="text-right">Payable Amount</th>
                <th><?=bcdiv($payable_amount,1,2)?></th>
            </tr>
            <tr>
                <td colspan="6" class="text-right">
                    <i>In Words: <b><?=ucfirst(amt_in_word($payable_amount))?> </b>Rupees Only</i>
                </td>
            </tr>
            <tr>
                <td colspan="6" style="padding:8px;border-top:solid 1px black;text-align:right;">
                    <i style="font-size:10px">This is computer generated bill:(<?=date('Y-m-d H:i:s')?>)?></i>
                </td>
            </tr>
            <tr>
                <td colspan="6" style="padding:8px;border-top:dashed 2px black;"></td>
            </tr>
        </table>
    </body>
</html>