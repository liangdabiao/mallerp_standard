<?php

$head = array(
    'Sku',
    lang('total_refund_count'),
    lang('sale_order_count'),
    lang('refund_sale_100_percent'),
    lang('refund_1000_percent'),
    lang('total_feedback_count'),
    lang('feedback_1000_percent'),
    lang('total_num'),
    lang('type_100_percent'),
    lang('order_1000_percent'),
);

$data = array();
$total_refund_count = 0;
$total_sale_count = 0;
$total_feedback_count = 0;
foreach ($refund_feedback_skus as $sku) {

    $sys_remark_div = "sys_remark_{$sku}";
    $sys_remark_feek_div = "sys_feek_remark_{$sku}";

    $refund = empty($refunds[$sku]) ? 0 : $refunds[$sku];
    $feedback = empty($feedbacks[$sku]) ? 0 : $feedbacks[$sku];
    $sale_count = (empty($sku) OR empty($all_skus[$sku])) ? 0 : $all_skus[$sku];
    $sum = $refund + $feedback;


    $item_duty = rtrim(@$item_no[$sku], '----------<br />');
    $feedback_conten_duty = rtrim(@$feed_feedback_content[$sku], '----------<br />');
    $back = <<<LIKE
    <a href="#" title="click to see detail" onclick="$('$sys_remark_div').toggle();return false;">$refund</a> <div id='$sys_remark_div' style='display: none;border: 2px solid #F27B04;background-color: #F0FFF0;'>$item_duty</div>
LIKE;
    $feek = <<<FEEK
     <a href="#" title="click to see detail" onclick="$('$sys_remark_feek_div').toggle();return false;">$feedback</a> <div id='$sys_remark_feek_div' style='display: none;border: 2px solid #F27B04;background-color: #F0FFF0;'>$feedback_conten_duty</div>
FEEK;

    if ($sum == 0 OR $sku == null) {
        continue;
    } else {
        $data[] = array(
            $sku,
            $back,
            $sale_count,
            (empty($sku) OR empty($all_skus[$sku])) ? 0 : price($refund / $all_skus[$sku] * 100, 4) . '%',
            price(($refund) / $order_count * 1000, 4) . '‰',
            $feek,
            price(($feedback) / $order_count * 1000, 4) . '‰',
            $sum,
            price($sum / $refund_feedback_count * 100) . '%',
            price(($sum) / $order_count * 1000, 4) . '‰',
        );
        $total_refund_count += $refund;
        $total_sale_count += $sale_count;
        $total_feedback_count += $feedback;
    }
}
$data[] = array(
    lang('summary'),
    $total_refund_count,
    $total_sale_count,
    @price(($total_refund_count / $total_sale_count) * 100, 4) . '%',
    price(($total_refund_count) / $order_count * 1000, 4) . '‰',
    $total_feedback_count,
    price(($total_feedback_count) / $order_count * 1000, 4) . '‰',
    $total_refund_count + $total_feedback_count,
    price(($total_refund_count + $total_feedback_count) / $refund_feedback_count * 100) . '%',
    price(($total_refund_count + $total_feedback_count) / $order_count * 1000, 4) . '‰',
);

$title = lang('refund_resend_statistics') . "-" . lang('by_refund_sku');
echo block_header($title);
echo "<br>";
echo form_open(current_url());
echo lang('from') . ' ' . block_time_picker('begin_time', $begin_time) . '&nbsp;&nbsp;';
echo lang('to') . ' ' . block_time_picker('end_time', $end_time) . '&nbsp;&nbsp;';

$sortable = array(
    'default',
    'float',
    'float',
    'float',
    'float',
    'float',
    'float',
    'float',
    'float',
    'float',
);
$users = array(
    '-1' => lang('all_duty_user'),
);

$config = array(
    'name' => 'submit',
    'value' => lang('submit'),
    'type' => 'submit',
);
echo block_button($config);
echo form_close();
echo js_sortabl();
echo block_js_sortable_table($head, $data, $sortable, "width: 100%;border-collapse: collapse;");
echo '<h2>' . lang('total_order_count') . ': ' . $order_count . '</h2>';
?>
