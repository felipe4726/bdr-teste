<?php
$randId = uniqid();
$cleanCampos = array();
$rows = 0;
?>
<div id="<?php echo $randId; ?>" class="lista-widget">
    <button id="show-filters" class="btn btn-primary pull-right">Filtros</button>
    <form onsubmit="return false;">
        <table>
            <thead>
            <?php foreach ($campos as $field) { ?>
                <?php
                $field = array(
                    'label' => (isset($field['label'])) ? $field['label'] : '',
                    'attribute' => (isset($field['attribute'])) ? $field['attribute'] : '',
                    'filter' => (isset($field['filter'])) ? $field['filter'] : '',
                    'selectOptions' => (isset($field['selectOptions'])) ? $field['selectOptions'] : '',
                    'data' => (isset($field['data'])) ? $field['data'] : '',
                    'class' => (isset($field['class'])) ? $field['class'] : ''
                );
                ?>
                <th class="lista-cabecalho">
                    <div class="lista-order"
                         order='<?php echo $field['attribute']; ?>'><?php echo $field['label']; ?></div>
                    <div class="lista-filter">
                        <?php
                        $attribute = $field['attribute'];
                        switch ($field['filter']) {
                            case 'text':
                                echo '<input type="text" name="' . get_class($model) . '[' . $attribute . ']" value="' . $model->$attribute . '" placeholder="' . $field['label'] . '" />';
                                break;
                            case 'select':
                                echo '<select name="' . get_class($model) . '[' . $attribute . ']">';
                                foreach ($field['selectOptions'] as $options) {
                                    $selected = ($options['id'] == $model->$attribute) ? 'selected="selected"' : '';
                                    echo '<option value="' . $options['id'] . '" ' . $selected . '>' . $options['value'] . '</option>';
                                }
                                echo '</select>';
                                break;
                            case '':
                                break;
                        }
                        ?>
                    </div>
                </th>
                <?php $cleanCampos[] = array('label' => addslashes($field['label']), 'attribute' => addslashes($field['attribute']), 'filter' => addslashes($field['filter']), 'selectOptions' => $field['selectOptions'], 'class' => addslashes($field['class']), 'data' => addslashes($field['data'])); ?>
            <?php } ?>
            </thead>
            <tbody>
            <?php if ($object = $model->search($this->criteria)) { ?>
                <?php foreach ($object as $data) { ?>
                    <tr>
                        <?php foreach ($campos as $field) { ?>
                            <td class="<?php echo $field['class']; ?>"><?php eval('echo ' . $field['data'] . ';'); ?></td>
                        <?php } ?>
                    </tr>
                <?php } ?>
            <?php } else { ?>
                <tr>
                    <td>nenhum resultado</td>
                </tr>
            <?php } ?>
            </tbody>
        </table>
    </form>
    <?php
    if (isset($object[0]))
        $rows = $object[0]->numRows;

    $pagination = new \Bdr\Vendor\ListaWidgetPagination($randId, $criteria, $rows, $cleanCampos); ?>
    <script>
        $('#show-filters').on('click', function () {
            $(".lista-filter").show();
        });
    </script>
</div>
