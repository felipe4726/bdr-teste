<?php
$prePage = "";
$pagination = "";
if ($numRows > 0) {
    $pagination = "<nav class='text-center mar-top' id='nav_pagination'>";
    $pagination .= "<div class='pagination-totals'>Exibindo do " . (($criteria->page - 1) * $criteria->limit) . " ao " . (($numRows > (($criteria->page - 1) * $criteria->limit) + $criteria->limit) ? ((($criteria->page - 1) * $criteria->limit) + $criteria->limit) : $numRows) . " do total de " . $numRows . "</div>";
    $pagination .= "<ul class='pagination'>";
    $count = 0;
    if ($criteria->page > 5)
        $pagination .= "<li><a class='pagination-item' page='1'>&lt;&lt;</a></li>";
    if ($criteria->page > 1)
        $pagination .= "<li><a class='pagination-item' page='" . ($criteria->page - 1) . "'>&lt;</a></li>";

    while ($criteria->page - $count > 1 && $count < 4) {
        $count++;
        $prePage = "<li><a class='pagination-item' page='" . ($criteria->page - $count) . "'>" . ($criteria->page - $count) . "</a></li>" . $prePage;
    }
    $pagination .= $prePage;
    for ($i = $criteria->page; $i - 1 < ($numRows / $criteria->limit); $i++) {
        $count++;
        if ($criteria->page == $i) {
            $pagination .= "<li class='active'><a href='#'>" . ($i) . "</a></li>";
        } else {
            $pagination .= "<li><a class='pagination-item' page='" . ($i) . "'>" . ($i) . "</a></li>";
        }
        if ($count >= 9) {
            $i = 999999;
            $pagination .= "<li><a class='pagination-item' page='" . ($criteria->page + 1) . "'>&gt;</a></li>";
            if ($criteria->page + 4 < round(($numRows / $criteria->limit) + 0.5, 0, PHP_ROUND_HALF_UP))
                $pagination .= "<li><a class='pagination-item' page='" . round(($numRows / $criteria->limit) + 0.5, 0, PHP_ROUND_HALF_UP) . "'>&gt;&gt;</a></li>";
        }
    }
    if ($criteria->page < ($numRows / $criteria->limit) && $count < 9) {
        $pagination .= "<li><a class='pagination-item' page='" . ($criteria->page + 1) . "'>&gt;</a></li>";
        if ($criteria->page + 4 < round(($numRows / $criteria->limit) + 0.5, 0, PHP_ROUND_HALF_UP))
            $pagination .= "<li><a class='pagination-item' page='" . round(($numRows / $criteria->limit) + 0.5, 0, PHP_ROUND_HALF_UP) . "'>&gt;&gt;</a></li>";
    }
}
echo $pagination . '</ul></nav>';
?>
<div class="text-right">
    Limite de Resultados por tela:
    <select id="lista-limit" name="limit">
        <option value="20" <?php echo ($criteria->limit == 20) ? 'selected="selected" ' : ""; ?>>20</option>
        <option value="50" <?php echo ($criteria->limit == 50) ? 'selected="selected" ' : ""; ?>>50</option>
        <option value="100" <?php echo ($criteria->limit == 100) ? 'selected="selected" ' : ""; ?>>100</option>
        <option value="200" <?php echo ($criteria->limit == 200) ? 'selected="selected" ' : ""; ?>>200</option>
    </select>
</div>
<style>
    .pagination-item {
        cursor: pointer;
    }
</style>
<script>
    $('.pagination-item').on('click', function () {
        var page = $(this).attr('page');
        refreshList(page, '<?php echo $criteria->order; ?>', '<?php echo $criteria->limit; ?>');
    });

    $('#lista-limit').on('change', function () {
        var limit = $(this).val();
        refreshList(1, '<?php echo $criteria->order; ?>', limit);
    });

    $('.lista-order').on('click', function () {
        var order = $(this).attr('order');
        <?php
        if (strpos($criteria->order, ',')) {
            $orderEquivalence = trim(substr($criteria->order, strpos($criteria->order, ',') + 1));
        } else {
            $orderEquivalence = $criteria->order;
        }
        ?>
        if (order == '<?php echo $orderEquivalence; ?>') {
            order = order + ' DESC';
        }
        refreshList(<?php echo $criteria->page; ?>, order, '<?php echo $criteria->limit; ?>');
    });

    $('#<?php echo $randomId; ?> input').on('blur', function () {
        refreshList(<?php echo $criteria->page; ?>, '<?php echo $criteria->order; ?>', '<?php echo $criteria->limit; ?>');
    });

    function refreshList(page, order, limit) {
        var data = $('#<?php echo $randomId; ?> form').serializeArray();
        data.push({name: 'campos', value: '<?php echo json_encode($campos); ?>'});
        data.push({name: 'page', value: page});
        data.push({name: 'order', value: order});
        data.push({name: 'limit', value: limit});
        $.ajax({
            type: 'POST',
            dataType: 'html',
            data: data,
            url: "<?php echo \Bdr\Vendor\Router::getRouter()->createUrl(\Bdr\Vendor\Router::getRouter()->controller, \Bdr\Vendor\Router::getRouter()->action); ?>",
            success: function (data) {
                $('#<?php echo $randomId; ?>').replaceWith(data);
            }
        });
    }
</script>