<?php
/**
 * Manage client index view
 * perhaps one of my most messy works
 *
 * TODO: Make this file less messy and less redundant
 *
 * @author Shitiz Garg <mail@dragooon.net>
 */

$this->setPageTitle('Manage Client Info');
$this->renderPartial('/layouts/tabs/client', array('active' => 'manage', 'manage' => true, 'id' => $data->businessId));

$form = $this->beginWidget('bootstrap.widgets.TbActiveForm', array(
    'method' => 'post',
    'action' => $this->createUrl('manage/update', array('id' => $data->businessId)),
));

$id = $data->businessId;

// This is a really lousy way to save typing space
function additionalInfoRadio($label, $attribute, $form, $data)
{
    echo $form->label($data, $attribute, array('label' => $label)), '<br />';
    echo $form->radioButtonList($data, $attribute, array(0 => 'No', 1 => 'Yes')), '<br />';
}

$additional_fields = array(
    'credit_cards' => 'Accept credit cards',
    'wheelchair_accessible' => 'Wheelchair Accessible',
    'insurance' => 'Insurance',
    'appointment_only' => 'Applointment Only',
    'waiter_service' => 'Waiter Service',
    'delivery' => 'Delivery',
    'takeout' => 'Takeout',
    'reservation' => 'Reservation',
    'outdoor_seating' => 'Outdoor Seating',
    'dogs_allowed' => 'Are dogs allowed',
    'caters' => 'Caters',
    'happy_hour' => 'Happy Hour',
    'coat_check' => 'Coat Check',
    'parking' => 'Parking',
    'alcohol' => 'Alcohol',
    'wifi' => 'Wifi',
    'smoking' => 'Smoking',
);
?>

<div id="main-content" class="row-fluid gran-data">
    <div id="ch-content" class="span9">
        <dl>
            <dt>Basic business information&nbsp;&nbsp;<a class="btn btn-mini btn-info edit-business">Edit</a></dt>
            <dd>
                <div class="business-info">
            <?php
                echo $data->info['basic_info']['name']['biz_name'];

                if (!empty($data->info['basic_info']['categories'])) {
                    $categories = array();
                    foreach ($data->info['basic_info']['categories'] as $category) {
                        $categories[] = $category['name'];
                    }
            ?>
                    <br />Categories: <?php echo implode(', ', $categories) ?><br /><br />
            <?php
                }
                if (!empty($data->info['basic_info']['address']))
                {
            ?>
                    <address>
                        <span class="address-line1"><?php echo $data->info['basic_info']['address']['line1'] ?></span>
                        <span class="address-line2"><?php echo !empty($data->info['basic_info']['address']['line2']) ? '<br />' . $data->info['basic_info']['address']['line2'] : '' ?></span>
                        <?php echo '<br /><span class="address-city">' . $data->info['basic_info']['address']['city'] . '</span>, <span class="city-state">' . $data->info['basic_info']['address']['state'] . '</span>' ?>
                        <span class="address-zip"><?php echo '&nbsp;' . $data->info['basic_info']['address']['zip'] ?></span>
                        <?php echo !empty($data->info['basic_info']['phone']) ? '<br /><br /><abbr title="Phone">P:</abbr> <span class="phone">' . $data->info['basic_info']['phone'] . '</span>' : '' ?>
                        <?php echo !empty($data->info['basic_info']['website']) ? '<br /><a href="' . $data->info['basic_info']['website'] . '" class="website">' . $data->info['basic_info']['website'] . '</a>' : '' ?>
                        <?php echo !empty($data->info['basic_info']['menu_url']) ? '<br /><a href="' . $data->info['basic_info']['menu_url'] . '" class="menu">Menu</a>' : '' ?>
                    </address>
            <?php
                }
            ?>
                </div>
                <div class="business" style="display: none;">
            <?php
                $basic_info = $data->info['basic_info'];
                $locked = !empty($basic_info['lockedAttributes']) ? $basic_info['lockedAttributes'] : array();

                echo $form->textField($data, 'info[basic_info][address][line1]', array('placeholder' => 'Address line 1', 'disabled' => in_array('address.line1', $locked) ? 'disabled' : '')), '<br />';
                echo $form->textField($data, 'info[basic_info][address][line2]', array('placeholder' => 'Address line 2', 'disabled' => in_array('address.line2', $locked) ? 'disabled' : '')), '<br />';
                echo $form->textField($data, 'info[basic_info][address][city]', array('placeholder' => 'City', 'disabled' => in_array('address.city', $locked) ? 'disabled' : '')), '<br />';
                echo $form->textField($data, 'info[basic_info][address][state]', array('placeholder' => 'State', 'disabled' => in_array('address.state', $locked) ? 'disabled' : '')), '<br />';
                echo $form->textField($data, 'info[basic_info][address][zip]', array('placeholder' => 'Zip', 'disabled' => in_array('address.zip', $locked) ? 'disabled' : '')), '<br />';
                echo $form->textField($data, 'info[basic_info][phone]', array('placeholder' => 'Phone', 'disabled' => in_array('phone', $locked) ? 'disabled' : '')), '<br />';


                if (!empty($basic_info['website'])) {
                    echo $form->textField($data, 'info[basic_info][website]', array('placeholder' => 'Website', 'disabled' => in_array('website', $locked) ? 'disabled' : '')), '<br />';
                }
                if (!empty($basic_info['menu_url'])) {
                    echo $form->textField($data, 'info[basic_info][menu_url]', array('placeholder' => 'Menu URL', 'disabled' => in_array('menu_url', $locked) ? 'disabled' : '')), '<br />';
                }
            
                $this->widget('bootstrap.widgets.TbButton', array('buttonType' => 'submit', 'type' => 'success', 'label' => 'Save'));
            ?>
                </div>
            </dd>
        </dl>
<?php
if (!empty($data->info['basic_info']['additional_info'])) {
?>
        <dl>
            <dt>Additional fields&nbsp;&nbsp;<a class="btn btn-mini btn-info edit-additional">Edit</a></dt>
            <dd>
                <div class="additional-info">
    <?php
    foreach ($additional_fields as $field => $label) {
        if (!array_key_exists($field, $data->info['basic_info']['additional_info'])) {
            continue;
        }

        echo '
                    <div>', $label, ':&nbsp;';
        if ($field == 'wifi') {
            $val = $data->info['basic_info']['additional_info']['wifi_detail'];
            echo $val['free'] ? 'Free' : ($val['paid'] ? 'Paid' : ($val['no'] ? 'No' : 'Not Sure'));
        }
        elseif ($field == 'parking') {
            $fields = array(
                'garage' => 'Garage',
                'lot' => 'Parking Lot',
                'street' => 'Street',
                'valet' => 'Valet',
                'validated' => 'Validated',
            );
            foreach ($fields as $k => $v) {
                if (!$data->info['basic_info']['additional_info']['parking_detail'][$k]) {
                    unset($fields[$k]);
                }
            }
            echo implode(', ', $fields);
        }
        elseif ($field == 'alcohol') {
            $val = $data->info['basic_info']['additional_info']['alcohol_detail'];
            echo $val['beer_and_wine'] ? 'Beer & Wine' : ($val['full_bar'] ? 'Full Bar' : ($val['no'] ? 'No' : 'Not Sure'));
        }
        elseif ($field == 'smoking') {
            $val = $data->info['basic_info']['additional_info']['smoking_detail'];
            echo $val['yes'] ? 'Yes' : ($val['no'] ? 'No' : ($val['outdoor'] ? 'Outdoor Area/Patio' : 'Not Sure'));
        }
        else {
            echo $data->info['basic_info']['additional_info'][$field] ? 'Yes' : 'No';
        }

        echo '
                    </div>';
    }
    ?>
                </div>
                <div class="additional" style="display: none;">
    <?php
                foreach ($additional_fields as $field => $label) {
                    if (!array_key_exists($field, $data->info['basic_info']['additional_info'])) {
                        continue;
                    }

                    $val = $data->info['basic_info']['additional_info'][$field];

                    if ($field == 'wifi') {
                        $val = $data->info['basic_info']['additional_info']['wifi_detail'];

                        echo '
                                <div>Wi-Fi:<br />
                                    <input type="radio" name="BizInfo[info][basic_info][additional_info][wifi_detail]"', $val['free'] ? ' checked' : '', ' value="free" /> Free&nbsp;
                                    <input type="radio" name="BizInfo[info][basic_info][additional_info][wifi_detail]"', $val['paid'] ? ' checked' : '', ' value="paid" /> Paid&nbsp;
                                    <input type="radio" name="BizInfo[info][basic_info][additional_info][wifi_detail]"', $val['no'] ? ' checked' : '', ' value="no" /> No&nbsp;
                                    <input type="radio" name="BizInfo[info][basic_info][additional_info][wifi_detail]"', $val['none'] ? ' checked' : '', ' value="none" /> Not Sure
                                </div>';
                    }
                    elseif ($field == 'parking') {
                        $val = $data->info['basic_info']['additional_info']['parking_detail'];

                        echo '
                                <div>Parking:<br />
                                    <input type="checkbox" name="BizInfo[info][basic_info][additional_info][parking_detail][garage]" value="1"', $val['garage'] ? ' checked': '', ' /> Garage&nbsp;
                                    <input type="checkbox" name="BizInfo[info][basic_info][additional_info][parking_detail][lot]" value="1"', $val['lot'] ? ' checked': '', ' /> Parking Lot&nbsp;
                                    <input type="checkbox" name="BizInfo[info][basic_info][additional_info][parking_detail][street]" value="1"', $val['street'] ? ' checked': '', ' /> Street&nbsp;
                                    <input type="checkbox" name="BizInfo[info][basic_info][additional_info][parking_detail][valet]" value="1"', $val['valet'] ? ' checked': '', ' /> Valet&nbsp;
                                    <input type="checkbox" name="BizInfo[info][basic_info][additional_info][parking_detail][validated]" value="1"', $val['validated'] ? ' checked': '', ' /> Validated
                                </div>';
                    }
                    elseif ($field == 'alcohol') {
                        $val = $data->info['basic_info']['additional_info']['alcohol_detail'];

                        echo '
                                <div>Alcohol:<br />
                                    <input type="radio" name="BizInfo[info][basic_info][additional_info][alcohol_detail]"', $val['beer_and_wine'] ? ' checked' : '', ' value="beer_and_wine" /> Beer & Wine&nbsp;
                                    <input type="radio" name="BizInfo[info][basic_info][additional_info][alcohol_detail]"', $val['full_bar'] ? ' checked' : '', ' value="full_bar" /> Full Bar&nbsp;
                                    <input type="radio" name="BizInfo[info][basic_info][additional_info][alcohol_detail]"', $val['no'] ? ' checked' : '', ' value="no" /> No&nbsp;
                                    <input type="radio" name="BizInfo[info][basic_info][additional_info][alcohol_detail]"', $val['none'] ? ' checked' : '', ' value="none" /> None
                                </div>';
                    }
                    elseif ($field == 'smoking') {
                        $val = $data->info['basic_info']['additional_info']['smoking_detail'];

                        echo '
                                <div>Smoking:<br />
                                    <input type="radio" name="BizInfo[info][basic_info][additional_info][smoking_detail]"', $val['yes'] ? ' checked' : '', ' value="yes" /> Yes&nbsp;
                                    <input type="radio" name="BizInfo[info][basic_info][additional_info][smoking_detail]"', $val['no'] ? ' checked' : '', ' value="no" /> No&nbsp;
                                    <input type="radio" name="BizInfo[info][basic_info][additional_info][smoking_detail]"', $val['outdoor'] ? ' checked' : '', ' value="outdoor" /> Outdoor Area/Patio&nbsp;
                                    <input type="radio" name="BizInfo[info][basic_info][additional_info][smoking_detail]"', $val['none'] ? ' checked' : '', ' value="none" /> Not Sure&nbsp;
                                </div>';
                    }
                    else {
                        echo '
                                <div>', $label, ':<br />
                                    <input type="radio" name="BizInfo[info][basic_info][additional_info][', $field, ']"', $val ? ' checked' : '', ' value="1" /> Yes&nbsp;
                                    <input type="radio" name="BizInfo[info][basic_info][additional_info][', $field, ']"', !$val ? ' checked' : '', ' value="0" /> No
                                </div>';
                    }
                }

                echo '<br />';
                $this->widget('bootstrap.widgets.TbButton', array('buttonType' => 'submit', 'type' => 'success', 'label' => 'Save'));
    ?>
                </div>
            </dd>
        </dl>
<?php
}
    if (isset($data->info['hours'])) {
?>
        <dl>
            <dt>Hours&nbsp;&nbsp;<a class="btn btn-mini btn-info edit-hours">Edit</a>&nbsp;&nbsp;<a class="btn btn-mini btn-danger remove-hours" href="<?php echo $this->createUrl('manage/remove', array('id' => $id, 'element' => 'hours')) ?>">Remove</a></dt>
            <dd>
                <div class="hours-info">
            <?php
                foreach ($data->info['hours'] as $hour)
                {
            ?>
                    <div class="hour">
                        <?php echo $hour['day'] ?> <?php echo $hour['start_time'] ?> - <?php echo $hour['end_time'] ?>
                        <input type="hidden" name="hours_value[]" value="<?php echo $hour['value'] ?>" />
                        &nbsp;<a style="font-size: .8em; display: none;" class="remove" href="">Remove</a>
                    </div>
            <?php
                }
            ?>
                </div>
                <div class="hours" style="display: none;">
            <?php
                // Probably not a way to do this
                $hours = array();
                for ($i = 0; $i < 24; $i += 0.5)
                    $hours[(string) $i] = (($i > 12 ? (int) $i - 12 : (int) $i) == 0 ? 12 : ($i > 12 ? (int) $i - 12 : (int) $i)) . ':' . ((string) (int) $i == (string) $i ? '00' : '30') . ' ' . ($i > 12 ? 'pm' : 'am');

                echo CHtml::dropDownList('day', '', array('Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat', 'Sun'), array('style' => 'width: 100px; vertical-align: inherit')), ' ', CHtml::dropDownList('start', '', $hours, array('style' => 'width: 100px; vertical-align: inherit')), ' ', CHtml::dropDownList('end', '', $hours, array('style' => 'width: 100px; vertical-align: inherit'));
                echo '&nbsp;';

                $this->widget('bootstrap.widgets.TbButton', array('size' => 'normal', 'buttonType' => 'submit', 'type' => 'info', 'label' => 'Add'));
                echo '<br />';

                $this->widget('bootstrap.widgets.TbButton', array('buttonType' => 'submit', 'type' => 'success', 'label' => 'Save'));
            ?>
                </div>
            </dd>
        </dl>
    <?php
    }
    else {
    ?>
        <dl>
            <dt>Hours&nbsp;&nbsp;<a class="btn btn-mini btn-success add-hours" href="<?php echo $this->createUrl('manage/add', array('id' => $id, 'element' => 'hours')) ?>">Add</a></dt>
        </dl>
    <?php
    }
    if (!empty($data->info['specialties'])) {
    ?>
        <dl>
            <dt>Specialties&nbsp;&nbsp;<a class="btn btn-mini btn-info edit-specialties">Edit</a>&nbsp;&nbsp;<a class="btn btn-mini btn-danger remove-specialties" href="<?php echo $this->createUrl('manage/remove', array('id' => $id, 'element' => 'specialties')) ?>">Remove</a></dt>
            <dd>
                <div class="speciality-info"><?php echo nl2br($data->info['specialties']['speciality']) ?></div>
                <div class="speciality" style="display: none;">
            <?php
                echo $form->textArea($data, 'info[specialties][speciality]', array('placeholder' => 'Type your speciality here...', 'class' => 'input-block-level', 'style' => 'height: 100px;')), '<br />';
                $this->widget('bootstrap.widgets.TbButton', array('buttonType' => 'submit', 'type' => 'success', 'label' => 'Save'));
            ?>
                </div>
            </dd>
        </dl>
    <?php
    }
    else {
    ?>
        <dl>
            <dt>Specialties&nbsp;&nbsp;<a class="btn btn-mini btn-success add-specialties" href="<?php echo $this->createUrl('manage/add', array('id' => $id, 'element' => 'specialties')) ?>">Add</a></dt>
        </dl>
    <?php
    }
    if (!empty($data->info['history'])) {
    ?>
        <dl>
            <dt>History&nbsp;&nbsp;<a class="btn btn-mini btn-info edit-history">Edit</a>&nbsp;&nbsp;<a class="btn btn-mini btn-danger remove-history" href="<?php echo $this->createUrl('manage/remove', array('id' => $id, 'element' => 'history')) ?>">Remove</a></dt>
            <dd>
                <div class="history-info">
            <?php
                if (!empty($data->info['history']['year_established']))
                    echo '<em>Established in ', $data->info['history']['year_established'], '</em>&nbsp;';
                if (!empty($data->info['history']) && !empty($data->info['history']['history'])) {
                    echo nl2br($data->info['history']['history']);
                }
            ?>
                </div>
                <div class="history" style="display: none;">
             <?php
                  echo 'Established in: ', $form->textField($data, 'info[history][year_established]', array('style' => 'width: 40px;', 'maxlength' => 4)), '<br />';
                  echo $form->textArea($data, 'info[history][history]', array('placeholder' => 'Type your history here...', 'class' => 'input-block-level', 'style' => 'height: 100px;'));
                  $this->widget('bootstrap.widgets.TbButton', array('buttonType' => 'submit', 'type' => 'success', 'label' => 'Save'));
             ?>
                </div>
            </dd>
        </dl>
    <?php
    }
    else {
    ?>
        <dl>
            <dt>History&nbsp;&nbsp;<a class="btn btn-mini btn-success add-history" href="<?php echo $this->createUrl('manage/add', array('id' => $id, 'element' => 'history')) ?>">Add</a></dt>
        </dl>
    <?php
    }
    if (!empty($data->info['owner_info'])) {
    ?>
        <dl>
            <dt>Meet the <?php echo $data->info['owner_info']['role'] ?> - <?php echo $data->info['owner_info']['first_name'], ' ', $data->info['owner_info']['last_initial'] ?>&nbsp;&nbsp;<a class="btn btn-mini btn-info edit-owner_info">Edit</a>&nbsp;&nbsp;<a class="btn btn-mini btn-danger remove-owner" href="<?php echo $this->createUrl('manage/remove', array('id' => $id, 'element' => 'owner_info')) ?>">Remove</a></dt>
            <dd>
                <div class="owner-info"><?php echo $data->info['owner_info']['bio'] ?></div>
                <div class="owner" style="display: none;">
                <?php
                    echo 'Role: ', $form->dropDownList($data, 'info[owner_info][role]', array('' => '', 'owner' => 'Owner', 'manager' => 'Manager'), array('style' => 'font-size: 0.8em; height: 20px;')), '<br />';
                    echo 'First: ', $form->textField($data, 'info[owner_info][first_name]', array('style' => 'width: 80px;')), '&nbsp;Last initial: ', $form->textField($data, 'info[owner_info][last_initial]', array('maxlength' => 1, 'style' => 'width: 15px;')), '<br />';
                    echo $form->textArea($data, 'info[owner_info][bio]', array('placeholder' => 'Type your bio here...', 'class' => 'input-block-level', 'style' => 'height: 100px;')), '<br />';
                    $this->widget('bootstrap.widgets.TbButton', array('buttonType' => 'submit', 'type' => 'success', 'label' => 'Save'));
                ?>
                </div>
            </dd>
        </dl>
    <?php
    }
    else {
    ?>
        <dl>
            <dt>Owner Info&nbsp;&nbsp;<a class="btn btn-mini btn-success add-owner-info" href="<?php echo $this->createUrl('manage/add', array('id' => $id, 'element' => 'owner_info')) ?>">Add</a></dt>
        </dl>
    <?php
    }
    ?>
    </div>
</div>
<script type="text/javascript">
    jQuery('.edit-business').click(function()
    {
        jQuery('.business-info').hide();
        jQuery(this).hide();
        jQuery('.business').show();
    });
    jQuery('.edit-additional').click(function()
    {
        jQuery('.additional-info').hide();
        jQuery(this).hide();
        jQuery('.additional').show();
    });
    jQuery('.edit-specialties').click(function()
    {
        jQuery('.speciality-info').hide();
        jQuery(this).hide();
        jQuery('.speciality').show();
    });
    jQuery('.edit-owner_info').click(function()
    {
        jQuery('.owner-info').hide();
        jQuery(this).hide();
        jQuery('.owner').show();
    });
    jQuery('.edit-history').click(function()
    {
        jQuery('.history-info').hide();
        jQuery(this).hide();
        jQuery('.history').show();
    });
    jQuery(document).on('click', '.hours-info .remove', function()
    {
        jQuery(this).parent().remove();
        return false;
    });
    jQuery('.hours button:first').click(function()
    {
        var $day = jQuery(this).parent().find('select').first();
        var $start = jQuery(this).parent().find('select:nth-child(2)');
        var $end = jQuery(this).parent().find('select:nth-child(3)');
        var $value = jQuery('<input type="hidden" name="hours_value[]" />').val($day.val() + ' ' + $start.val() + ' ' + $end.val());

        var $el = jQuery('<div class="hour">' + $day.find('option:selected').text() + ' ' + $start.find('option:selected').text() + ' - ' + $end.find('option:selected').text() + '</div>');
        $el.append($value);
        $el.append(jQuery('<a href="" style="font-size: .8em;" class="remove">&nbsp;&nbsp;Remove</a>'));

        $el.appendTo(jQuery('.hours-info'));
        return false;
    });
    jQuery('.edit-hours').click(function()
    {
        jQuery(this).hide();
        jQuery('.hours').show();
        jQuery('.remove').show();
    })

    if (document.location.hash && document.location.hash.length > 0) {
        window.scrollTo(0, jQuery('.' + document.location.hash.substr(1)).first().offset().top);
        jQuery('.' + document.location.hash.substr(1)).click();
    }
</script>
<?php
$this->endWidget();
?>