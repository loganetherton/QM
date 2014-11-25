<?php
/**
 * Controller for managing individual business info
 *
 * @author Shitiz Garg <mail@dragooon.net>
 */

class ManageController extends AmController
{
    /**
     * Shows the basic info for this user
     *
     * @access public
     * @param int $id
     * @return void
     */
    public function actionIndex($id)
    {
        $model = $this->loadModel($id, 'BizInfo');

        $this->render('index', array(
            'data'=>$model,
        ));
    }

    /**
     * Updates an individual client's information
     *
     * @access public
     * @param int $id
     * @return void
     * @throws CException
     */
    public function actionUpdate($id)
    {
        $info = $this->loadModel($id, 'BizInfo');

        if (empty($_POST['BizInfo'])) {
            throw new CException('Empty post data');
        }

        if (!empty($_POST['hours_value'])) {
            $hours = $_POST['hours_value'];
            $_POST['BizInfo']['info']['hours'] = array();

            foreach ($hours as $hour)
            {
                list ($day, $start, $end) = explode(' ', $hour);
                $days = array('Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat', 'Sun');

                $h = array();
                $h['day'] = $days[$day];

                $i = $start;
                $h['start_time'] = (($i > 12 ? (int) $i - 12 : (int) $i) == 0 ? 12 : ($i > 12 ? (int) $i - 12 : (int) $i)) . ':' . ((string) (int) $i == (string) $i ? '00' : '30') . ' ' . ($i > 12 ? 'pm' : 'am');

                $i = $end;
                $h['end_time'] = (($i > 12 ? (int) $i - 12 : (int) $i) == 0 ? 12 : ($i > 12 ? (int) $i - 12 : (int) $i)) . ':' . ((string) (int) $i == (string) $i ? '00' : '30') . ' ' . ($i > 12 ? 'pm' : 'am');

                $h['value'] = $hour;

                $_POST['BizInfo']['info']['hours'][] = $h;
            }
        }

        if (!empty($_POST['BizInfo']['info']['basic_info']['additional_info'])) {
            $additional_info = $info->info['basic_info']['additional_info'];
            $post_info = &$_POST['BizInfo']['info']['basic_info']['additional_info'];

            foreach ($additional_info as $field => $value) {
                if (is_bool($value) || (!is_bool($value) && empty($value) && array_key_exists($field, $post_info))) {
                    $post_info[$field] = !empty($post_info[$field]);
                }
                elseif (is_array($value) && isset($post_info[$field]) && !is_array($post_info[$field])) {
                    $val = $post_info[$field];
                    $post_info[$field] = array();
                    foreach ($additional_info[$field] as $k => $v) {
                        $post_info[$field][$k] = $val == $k ? true : false;
                    }
                }
                elseif (is_array($additional_info[$field])) {
                    foreach ($additional_info[$field] as $k => $v) {
                        if (!isset($post_info[$field][$k])) {
                            $post_info[$field][$k] = 0;
                        }
                    }
                }
                else {
                    $post_info[$field] = $additional_info[$field];
                }
            }
        }

        $data = $info->info;

        if (!empty($_POST['hours_value']))
            $data['hours'] = $_POST['BizInfo']['info']['hours'];

        $data = array_replace_recursive($data, $_POST['BizInfo']['info']);

        $info->updateInfo($data);

        $this->redirect(array('manage/index', 'id' => $id));
    }

    /**
     * Removes a specific element from the info
     *
     * @access public
     * @param int $id
     * @param string $element
     * @return void
     */
    public function actionRemove($id, $element)
    {
        $info = $this->loadModel($id, 'BizInfo');

        if ($element != 'basic_info' && isset($info->info[$element])) {
            $info->removeInfoAttribute($element);
        }

        $this->redirect(array('manage/index', 'id' => $id));
    }

    /**
     * Adds a specific element to the info array
     *
     * @access public
     * @param int $id
     * @param string $element
     * @return void
     */
    public function actionAdd($id, $element)
    {
        $data = $this->loadModel($id, 'BizInfo');

        // We don't add this to update Queue since generally the user will edit
        // the new element further
        if (isset(YelpInfo::$info_base[$element])) {
            $info = $data->info;
            $info[$element] = YelpInfo::$info_base[$element];
            $data->info = $info;

            if (!in_array($element, $data->originalNodes)) {
                $originalNodes = $data->originalNodes;
                $originalNodes[] = $element;
                $data->originalNodes = $originalNodes;
            }

            $data->save();
        }

        $url = $this->createUrl('manage/index', array('id' => $id)) . '#edit-' . $element;
        $this->redirect($url);
    }
}