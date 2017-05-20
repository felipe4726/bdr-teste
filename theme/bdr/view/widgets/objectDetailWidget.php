<div class="detail-widget">
    <table class="col-md-10">
        <tbody>
        <?php foreach ($campos as $field) { ?>
            <tr>
                <?php if (!empty($field['label'])) { ?>
                    <td class="gray detail-label">
                        <label><?php echo $field['label']; ?>:</label>
                    </td>
                <?php } else { ?>
                    <td></td>
                <?php } ?>
                <td class="<?php echo $field['class']; ?> detail-value">
                    <?php
                    if (isset($field['form']) && !empty($field['form'])) {
                        foreach ($field['form'] as $form) {
                            eval($form . ';');
                        }
                    } else if (isset($field['data']) && !empty($field['data'])) {
                        eval('echo ' . $field['data'] . ';');
                    } else if (isset($field['attribute']) && !empty($field['attribute'])) {
                        echo $model->$field['attribute'];
                    } ?>
                </td>
            </tr>
        <?php } ?>
        </tbody>
    </table>
</div>
