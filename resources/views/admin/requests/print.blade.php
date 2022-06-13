
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <title>Untitled Document</title>
</head>

<body>

@php

@endphp
<table width="100%" border="0" cellspacing="0" cellpadding="0">
    <tr>
        <td colspan="2"><img src="http://127.0.0.1:8000/images/logo.png" width="150"  /></td>
    </tr>
    <tr>
        <td colspan="2">&nbsp;</td>
    </tr>
    <tr>
        <td width="49%"><table width="100%" border="0" cellspacing="0" cellpadding="0">
                <tr>
                    <td><table width="100%" border="0" cellspacing="0" cellpadding="0">
                            <tr>
                                <td style="font-family:Verdana, Geneva, sans-serif; font-weight:600; font-size:15px;">Payment Receipt</td>
                            </tr>
                            <tr>
                                <td style="font-family:Verdana, Geneva, sans-serif; font-weight:300; font-size:13px;">Receipt Number: {{$order->consultation_number}}</td>
                            </tr>
                            <tr>
                                <td>&nbsp;</td>
                            </tr>
                            <tr>
                                <td style="font-family:Verdana, Geneva, sans-serif; font-weight:600; font-size:15px;">Customer Details </td>
                            </tr>
                            <tr>
                                <td style="font-family:Verdana, Geneva, sans-serif; font-weight:300; font-size:13px;"><strong>Full Name: </strong>{{ $order->customer_name }} </td>
                            </tr>
                            <tr>
                                <td style="font-family:Verdana, Geneva, sans-serif; font-weight:300; font-size:13px;"><strong>Email: </strong>{{ $order->user->email }}</td>
                            </tr>
                            <tr>
                                <td style="font-family:Verdana, Geneva, sans-serif; font-weight:300; font-size:13px;"><strong>Mobile Number: </strong>{{ $order->customer_phone }} </td>
                            </tr>
                    
                            <tr>
                                <td>&nbsp;</td>
                            </tr>

                            <tr>
                                <td style="font-family:Verdana, Geneva, sans-serif; font-weight:600; font-size:15px;">Lawyer Details </td>
                            </tr>
                            <tr>
                                <td style="font-family:Verdana, Geneva, sans-serif; font-weight:300; font-size:13px;"><strong>Full Name: </strong>{{ $order->lawyer_name }} </td>
                            </tr>
                            <tr>
                                <td style="font-family:Verdana, Geneva, sans-serif; font-weight:300; font-size:13px;"><strong>Email: </strong>{{ $order->lawyer->email }}</td>
                            </tr>
                            <tr>
                                <td style="font-family:Verdana, Geneva, sans-serif; font-weight:300; font-size:13px;"><strong>Mobile Number: </strong>{{ $order->lawyer_phone }} </td>
                            </tr>
                            <tr>
                                <td style="font-family:Verdana, Geneva, sans-serif; font-weight:300; font-size:13px;"><strong>Consultations Fees: </strong>{{ $order->lawyer->consultations_fees }} KWD </td>
                            </tr>                    
                            <tr>
                                <td>&nbsp;</td>
                            </tr>                            
                        </table></td>
                </tr>
            </table></td>
        <td width="51%" valign="top"><table width="100%" border="0" cellspacing="0" cellpadding="0">
                <tr>
                    <td align="right"><img src="logo.png" alt="" width="150"  /></td>
                </tr>
                <tr>
                    <td style="font-family:Verdana, Geneva, sans-serif; font-weight:300; font-size:13px;" align="right"></td>
                </tr>
                <tr>
                    <td style="font-family:Verdana, Geneva, sans-serif; font-weight:300; font-size:13px;"  align="right">&nbsp;</td>
                </tr>
                <tr>
                    <td style="font-family:Verdana, Geneva, sans-serif; font-weight:300; font-size:13px;"  align="right">Receipt Date : {{$order->created_at->format('d-m-Y')}}</td>
                </tr>
                <tr>
                    <td style="font-family:Verdana, Geneva, sans-serif; font-weight:600; font-size:15px;" align="right">MyLawyer</td>
                </tr>
                <tr>
                    <td style="font-family:Verdana, Geneva, sans-serif; font-weight:300; font-size:13px;" align="right">7405379159</td>
                </tr>
                <tr>
                    <td style="font-family:Verdana, Geneva, sans-serif; font-weight:300; font-size:13px;" align="right">mylawyer@gmail.com</td>
                </tr>
            </table></td>
    </tr>
    <tr>
        <td colspan="2">&nbsp;</td>
    </tr>
 
{{--    <tr>--}}
{{--        <td colspan="2">&nbsp;</td>--}}
{{--    </tr>--}}
{{--    <tr>--}}
{{--        <td style="font-family:Verdana, Geneva, sans-serif; font-weight:600; font-size:13px;" colspan="2">Please Note:</td>--}}
{{--    </tr>--}}
{{--    <tr>--}}
{{--        <td style="font-family:Verdana, Geneva, sans-serif; font-weight:300; font-size:13px;" colspan="2">Dear Consumer, the bill payment will reflect in next 48 hours or in the next billing cycle, at your service provider&rsquo;s end. Please  contact paytm customer support for any queries regarding this order.</td>--}}
{{--    </tr>--}}
    <tr>
        <td colspan="2">&nbsp;</td>
    </tr>

    <tr>
        <td colspan="2"><table width="100%" border="0" cellspacing="0" cellpadding="0">
                <tr>
                    <td style="font-family:Verdana, Geneva, sans-serif; font-weight:600; font-size:13px; border-top:1px solid #333; border-bottom:1px solid #333; border-left:1px solid #333; border-right:1px solid #333;" width="34%" height="32" align="center">Order Status</td>
                    <td style="font-family:Verdana, Geneva, sans-serif; font-weight:600; font-size:13px; border-top:1px solid #333; border-bottom:1px solid #333; border-right:1px solid #333;" width="26%" align="center">PayOut</td>
                    <td style="font-family:Verdana, Geneva, sans-serif; font-weight:600; font-size:13px; border-top:1px solid #333; border-bottom:1px solid #333; border-right:1px solid #333;" width="25%" align="center">Payment Method</td>
                    <td style="font-family:Verdana, Geneva, sans-serif; font-weight:600; font-size:13px; border-top:1px solid #333; border-bottom:1px solid #333; border-right:1px solid #333;" width="25%" align="center">Payment Status</td>
                    <td style="font-family:Verdana, Geneva, sans-serif; font-weight:600; font-size:13px; border-top:1px solid #333; border-bottom:1px solid #333; border-right:1px solid #333; border-right:1px solid #333;" width="15%" align="center">Total Amount</td>
                </tr>
                <tr>
                <td style="font-family:Verdana, Geneva, sans-serif; font-weight:300; font-size:13px; border-bottom:1px solid #333; border-left:1px solid #333; border-right:1px solid #333;" height="32" align="center">{{ $order->status->name }}</td>
                <td style="font-family:Verdana, Geneva, sans-serif; font-weight:300; font-size:13px; border-bottom:1px solid #333; border-left:1px solid #333; border-right:1px solid #333;" height="32" align="center">{{ $order->payout != 0? 'Payed': 'Not Payed' }}</td>
                    <td style="font-family:Verdana, Geneva, sans-serif; font-weight:300; font-size:13px; border-bottom:1px solid #333; border-right:1px solid #333;" align="center">{{ $order->payment->name }}</td>
                    <td style="font-family:Verdana, Geneva, sans-serif; font-weight:300; font-size:13px; border-bottom:1px solid #333; border-right:1px solid #333;" align="center">{{ optional($order->paymentStatus)->name_en }}</td>
                    <td style="font-family:Verdana, Geneva, sans-serif; font-weight:300; font-size:13px; border-bottom:1px solid #333; border-right:1px solid #333; border-right:1px solid #333;" align="center">{{ number_format($order->amount ,env('NUMBER_FORMAT')) }} KWD</td>
                </tr>
            </table></td>
    </tr>

    <tr>
        <td colspan="2">&nbsp;</td>
    </tr>

</table>
</body>
</html>


<script>
    window.print()

  function load() {
    $('#printBtn').click(function () {



  var divToPrint=document.getElementById('details');

  var newWin=window.open('','Print-Window');

  newWin.document.open();

  newWin.document.write('<html><body onload="window.print()">'+divToPrint.innerHTML+'</body></html>');

  newWin.document.close();

  setTimeout(function(){newWin.close();},10);


      }
    )
    ;
  }

  if (document.readyState === 'complete') {
    load();
  }
  else {
    $(document).ready(load);
  }
</script>
