<?php

namespace Bdr\Vendor;
class Form
{

    /**
     * @param $label
     * @param Loja $model
     * @param $fieldName
     * @param $formating
     * @param array() $htmlOptions
     * @param $placeholder
     */
    public static function textField($label, $model, $fieldName, $formating = false, $htmlOptions = array(), $placeholder = false, $style = array())
    {
        $required = Form::required($model, $fieldName);
        $placeholder = ($placeholder) ? ' placeholder="' . $placeholder . '" ' : '';
        $model->$fieldName = Form::format($model, $fieldName, $formating);
        $style = Form::styling($style);
        echo '
                <div class="row">
                    <div class="' . $style['label-column'] . '">
                        <div id="label-' . $fieldName . ' class="vcenter"> ' . $required . ' ' . $label . ' :</div>
                    </div>
                    <div class="' . $style['value-column'] . '">
                        <input type="text" ' . $placeholder . ' value="' . $model->$fieldName . '" ' . Form::htmlOptions($htmlOptions, $model, $fieldName) . '/>
                    </div>
                </div>
              ';

    }

    /**
     * @param $label
     * @param Loja $model
     * @param $fieldName
     * @param null $formating
     * @param array $htmlOptions
     */
    public static function passwordField($label, $model, $fieldName, $formating = null, $htmlOptions = array(), $placeholder = false, $style = array())
    {
        $required = Form::required($model, $fieldName);
        $placeholder = ($placeholder) ? ' placeholder="' . $placeholder . '" ' : '';
        $style = Form::styling($style);
        echo '  <div class="row">
                    <div class="' . $style['label-column'] . '">
                        <div id="label-' . $fieldName . '" class="vcenter"> ' . $required . ' ' . $label . ' :</div>
                    </div>
                    <div class="' . $style['value-column'] . '">
                        <input type="password" ' . $placeholder . ' value="' . $model->$fieldName . '" ' . Form::htmlOptions($htmlOptions, $model, $fieldName) . '/>
                    </div>
                </div>
              ';

    }

    /**
     * @param $label
     * @param Loja $model
     * @param $fieldName
     * @param array $options
     * @param array $htmlOptions
     */
    public static function checkboxField($label, $model, $fieldName, $options = array(), $htmlOptions = array(), $style = array())
    {
        $modelName = get_class($model);
        $required = Form::required($model, $fieldName);
        $errorClass = Form::handleError($model, $fieldName);
        $style = Form::styling($style);
        //@todo arrumar a parte de opções.
        echo '  <div class="row">
                    <div class="' . $style['label-column'] . '">
                        <div id="label-' . $fieldName . ' class="vcenter"> ' . $required . ' ' . $label . ' :</div>
                    </div>
                    <div class="' . $style['value-column'] . ' ' . $errorClass . '">
                        <input type="checkbox" ' . Form::htmlOptions($htmlOptions, $model, $fieldName) . '>
                    </div>
                </div>
              ';
    }

    public static function selectField($label, $model, $fieldName, $options = array(), $htmlOptions = array(), $optionId = 'id', $optionValue = 'value', $placeholder = false, $style = array())
    {
        $required = Form::required($model, $fieldName);
        $placeholder = ($placeholder) ? ' placeholder="' . $placeholder . '" ' : '';
        $style = Form::styling($style);
        echo '  <div class="row">
                    <div class="' . $style['label-column'] . '">
                        <div id="label-' . $fieldName . '" class="vcenter"> ' . $required . ' ' . $label . ' :</div>
                    </div>
                    <div class="' . $style['value-column'] . '">
                        <select ' . Form::htmlOptions($htmlOptions, $model, $fieldName) . '>
                                ' . $placeholder . '
             ';
        foreach ($options as $option) {
            $option = (array)$option;
            $checked = ($model->$fieldName == $option[$optionId]) ? 'selected' : '';
            echo '<option value="' . $option[$optionId] . '" ' . $checked . '>' . $option[$optionValue] . '</option>';
        }

        echo '        </select>
                    </div>
                </div>
              ';
    }

    /**
     *  SIMPLE FIELDS  sem Label, apenas o field.
     */

    /**
     * @param Loja $model
     * @param $fieldName
     * @param boolean $formating
     * @param array $htmlOptions
     * @param String $placeholder
     */
    public static function simpleTextField($model, $fieldName, $formating = false, $htmlOptions = array(), $placeholder = '')
    {
        $required = Form::required($model, $fieldName);
        $placeholder = ($placeholder) ? ' placeholder="' . $placeholder . '" ' : '';
        $model->$fieldName = Form::format($model, $fieldName, $formating);
        echo $required . '<input type="text" ' . $placeholder . ' value="' . $model->$fieldName . '" ' . Form::htmlOptions($htmlOptions, $model, $fieldName) . '/>';

    }

    /**
     * @param Loja $model
     * @param $fieldName
     * @param boolean $formating
     * @param array $htmlOptions
     * @param String $placeholder
     */
    public static function simplePasswordField($model, $fieldName, $formating = null, $htmlOptions = array(), $placeholder = '')
    {
        $required = Form::required($model, $fieldName);
        $placeholder = ($placeholder) ? ' placeholder="' . $placeholder . '" ' : '';
        echo $required . '<input type="password" ' . $placeholder . ' value="' . $model->$fieldName . '" ' . Form::htmlOptions($htmlOptions, $model, $fieldName) . '/>';

    }

    public static function simpleSelectField($model, $fieldName, $options = array(), $htmlOptions = array(), $optionId = 'id', $optionValue = 'value', $placeholder = false)
    {
        $required = Form::required($model, $fieldName);
        $placeholder = ($placeholder) ? '<option value="">' . $placeholder . '</option>' : '';
        echo $required . '<select ' . Form::htmlOptions($htmlOptions, $model, $fieldName) . '>' . $placeholder;
        foreach ($options as $option) {
            $option = (array)$option;
            $checked = ($model->$fieldName == $option[$optionId]) ? 'selected' : '';
            echo '<option value="' . $option[$optionId] . '" ' . $checked . '>' . $option[$optionValue] . '</option>';
        }
        echo '</select>';
    }

    public static function simpleMultiSelectField($model, $fieldName, $getValuesFuntion, $options = array(), $htmlOptions = array(), $optionId = 'id', $optionValue = 'value', $placeholder = false)
    {
        $preloaded = $model->$getValuesFuntion();
        $targetModelName = get_class($preloaded[0]);
        $loaded = array();
        foreach ($preloaded as $p) {
            $loaded[] = $p->getPrimaryKey();
        }
        $placeholder = ($placeholder) ? '<option value="">' . $placeholder . '</option>' : '';

        echo '<div class="input-group select2-bootstrap-append">';
        echo '<select class="form-control select2-multiple" multiple="" id="field-' . $fieldName . '" name="' . $fieldName . '[]" ' . Form::htmlOptions($htmlOptions) . '>' . $placeholder;
        foreach ($options as $option) {
            $option = (array)$option;
            $checked = (in_array($option[$optionId], $loaded) ? 'selected' : '');
            echo '<option value="' . $option[$optionId] . '" ' . $checked . '>' . $option[$optionValue] . '</option>';
        }
        echo '</select>';
        echo '</div>';
        echo '<script>
                $(document).ready(function(){
                    $("#field-' . $fieldName . '").select2({
                         minimumInputLength: 0,
                         placeholder: "Selecione ' . $targetModelName . '"
                     });
                });
            </script>';

    }

    /**
     * @param Loja $model
     * @param $fieldName
     * @param boolean $formating
     * @param array $htmlOptions
     */
    public static function simpleTextArea($model, $fieldName, $formating = false, $htmlOptions = array(), $ckeditor = false)
    {
        $required = Form::required($model, $fieldName);
        if ($formating) {
            if ($formating == 'Data' && !empty($model->$fieldName)) {
                $model->$fieldName = date('d/m/Y', strtotime($model->$fieldName));
            }
        }
        echo $required . '<textarea ' . Form::htmlOptions($htmlOptions, $model, $fieldName) . '>' . $model->$fieldName . '</textarea>';
        if ($ckeditor) {
            echo '<script>CKEDITOR.replace( "field-' . $fieldName . '" );</script>';
        }
    }

    /**
     * @param Loja $model
     * @param $fieldName
     * @param boolean $formating
     * @param array $htmlOptions
     */
    public static function simpleFileField($model, $fieldName, $htmlOptions = array(), $single = false)
    {
        $modelName = get_class($model);
        $required = Form::required($model, $fieldName);
        $errorClass = Form::handleError($model, $fieldName);
        $files = File::getFilesOfObject($model);
        if ($files) {
            echo "<ul id='sortable' class='row'>";
            foreach ($files as $file) {
                echo "<li id='item-" . $file->id . "' class='file col-md-3 ui-state-default'><img src='" . $file->getPreviewImageUrl(140, 140) . "' /><a class=\"btn btn-warning delete-button remove\" controller=\"file\" objId=\"" . $file->id . "\"><i class=\"fa fa-trash\"></i></a></li>";
            }
            echo "</ul>
                  <script>
                    $('#sortable').sortable({
                        update: function (event, ui) {
                            var data = $(this).sortable('serialize');
                            console.log(data);
                            // POST to server using $.post or $.ajax
                            $.ajax({
                                data: data,
                                type: 'POST',
                                url: '/adm/file/order/model/" . $file->object_model . "/id/" . $file->object_id . "'
                            });
                        }
                    });
                    $('#sortable').disableSelection();
                  </script>
            ";
        }
        if ($files && $single) {
            echo "<div class='input-file'></div>";
        } elseif ($single) {
            echo $required . '<input id="field-' . $fieldName . '" type="file" class="form-control ' . $errorClass . '" name="' . $fieldName . '" ' . Form::htmlOptions($htmlOptions) . '>';
        } else {
            echo $required . '<input id="field-' . $fieldName . '" type="file" class="form-control ' . $errorClass . '" name="' . $fieldName . '[]" ' . Form::htmlOptions($htmlOptions) . ' multiple>';
        }

    }

    public static function format($model, $fieldName, $format)
    {
        if (!empty($model->$fieldName) && $format != false) {
            switch ($format) {
                case 'date':
                    return date('d/m/Y', strtotime($model->$fieldName));
                    break;
                case 'datetime':
                    return date('d/m/Y h:i:s', strtotime($model->$fieldName));
                    break;
                case 'float':
                    return str_replace('.', ',', $model->$fieldName);
                    break;
                case 'money':
                    return str_replace('.', ',', $model->$fieldName);
                    break;
            }
        }
        return $model->$fieldName;
    }

    public static function formatClass($formating)
    {
        if ($formating != false) {
            switch ($formating) {
                case 'date':
                    return 'datepicker';
                    break;
                case 'datetime':
                    return 'datetimepicker';
                    break;
                case 'float':
                    return 'decimal';
                    break;
                case 'numerico':
                    return 'number';
                    break;
                case 'fone':
                    return 'fone';
                    break;
                case 'cnpj':
                    return 'cnpj';
                    break;
                case 'cpf':
                    return 'cpf';
                    break;
                case 'cep':
                    return 'cep';
                    break;
                case 'money':
                    return 'money';
                    break;
            }
        }
    }

    public static function htmlOptions($htmlOptions = array(), $model = '', $fieldName = '', $formating = false)
    {
        if (!empty($model)) {
            $modelName = get_class($model);
            Form::handleError($model, $fieldName);
        }
        $htmlOpt = "";
        if (isset($htmlOptions['class'])) {
            $htmlOptions['class'] .= ' form-control';
        } else {
            $htmlOptions['class'] = 'form-control';
        }
        $htmlOptions['class'] .= ' ' . Form::formatClass($formating);

        if (!isset($htmlOptions['id']) && !empty($fieldName)) {
            $htmlOptions['id'] = "field-{$fieldName}";
        }
        if (!isset($htmlOptions['name']) && !empty($fieldName) && isset($modelName)) {
            $htmlOptions['name'] = $modelName . '[' . $fieldName . ']';
        }
        foreach ($htmlOptions as $key => $value) {
            $htmlOpt .= "{$key}=\"{$value}\"";
        }
        return $htmlOpt;
    }


    public static function handleError($model, $fieldName)
    {
        if ($errorMsg = $model->getErrors($fieldName)) {
            echo '<div class="col-sm-5"></div><div class="col-sm-7 form-error-message"> * ' . $errorMsg . '</div>';
            return 'form-error';
        }

        return '';
    }

    public static function required($model, $fieldName)
    {
        $rules = $model->rules();
        if (isset($rules[$fieldName]['required']) && $rules[$fieldName]['required'] == true)
            return "<i class='required asterisco-required'>*</i>";

        return '';
    }

    public static function styling($style = array())
    {
        if (isset($style['label-column'])) {
            $style['label-column'] = $style['label-column'];
        } else {
            $style['label-column'] = 'col-sm-5';
        }
        if (isset($style['value-column'])) {
            $style['value-column'] = $style['value-column'];
        } else {
            $style['value-column'] = 'col-sm-5';
        }
    }
}