<?php
// Connection
$db = new SQLite3('../db/messages_db.db');
$json = '';
if (isset($_GET['req'])) :
    switch ($_GET['req']):
        case 'new':
            $f_user = $_POST['f_user'];
            $my_user = $_POST['my_user'];
            $msg = $_POST['msg'];
            if (empty($msg)) {
                $json = array('status' => 0, 'msg' => 'Enter your message!.');
            } else {
                $msg_ins_stm = $db->prepare('INSERT INTO messages (recipient, "from", msg, status) VALUES ( ?,?,?,"1")');
                $msg_ins_stm->bindParam(1, $f_user);
                $msg_ins_stm->bindParam(2, $my_user);
                $msg_ins_stm->bindParam(3, $msg);
                $query_result = $msg_ins_stm->execute();
                if ($query_result != false) {
                    $last_id_stm = $db->prepare('SELECT * FROM messages WHERE id = ?');
                    $last_id_stm->bindParam(1, $last_row_id);
                    $last_row_id = $db->lastInsertRowID();
                    $res = $last_id_stm->execute();

                    while ($get_msgs_with_last_id = $res->fetchArray()) {
                        $json = array('status' => 1, 'msg' => $get_msgs_with_last_id['msg'], 'from' => $get_msgs_with_last_id['from'], 'last_id' => $last_row_id, 'time' => $get_msgs_with_last_id['time']);
                    }
                } else {
                    $json = array('status' => 0, 'msg' => 'Unable to process request.');
                }
            };
            break;
        case 'checkMsg':
            $my_user = $_POST['my_user'];
            $f_user = $_POST['f_user'];
            $last_id = $_POST['last_id'];
            if (empty($my_user)) {
                $json = array('status' => 0, 'msg' => 'No messages to retrieve.');
            } else {
                $check_msg_select_stm = $db->prepare('SELECT * FROM messages WHERE recipient = ? AND "from" = ? AND status=1');
                $check_msg_select_stm->bindParam(1, $my_user);
                $check_msg_select_stm->bindParam(2, $f_user);

                $query_result = $check_msg_select_stm->execute();
                if ($query_result != false) {
                    $json = array('status' => 1);
                } else {
                    $json = array('status' => 0);
                }
            };
            break;
        case 'fetchMsg':
            $my_user = $_POST['my_user'];
            $f_user = $_POST['f_user'];
            
            if (isset($_GET['reboot'])) {
                $fetch_msg_select_stm = $db->prepare('SELECT * FROM messages WHERE recipient = ? AND "from" = ? OR recipient = ? AND "from" = ? ORDER BY id asc');
                $fetch_msg_select_stm->bindParam(1, $my_user);
                $fetch_msg_select_stm->bindParam(2, $f_user);
                $fetch_msg_select_stm->bindParam(3, $f_user);
                $fetch_msg_select_stm->bindParam(4, $my_user);

            } else {
                $fetch_msg_select_stm = $db->prepare('SELECT * FROM messages WHERE recipient = ? AND "from" = ? AND status=1 ORDER BY id desc LIMIT 1');
                $fetch_msg_select_stm->bindParam(1, $my_user);
                $fetch_msg_select_stm->bindParam(2, $f_user);
            }

            $query_result = $fetch_msg_select_stm->execute();
            $json = array();
            while ($row = $query_result->fetchArray()) {
                $temp = array('status' => 1, 'msg' => '<div class="sender">' .
                $row['from'] . '<hr>' .
                '</div>' . '<div>' . $row['msg'] . '</div>' . '<hr>', 'last_id' => $row['id'], 'time' => $row['time']);
                $json[] = $temp;
                
            };
            // update status
            $update_msg_stm = $db->prepare('UPDATE messages SET status = 0 WHERE recipient = ? AND "from" = ?');
            $update_msg_stm->bindParam(1, $my_user);
            $update_msg_stm->bindParam(2, $f_user);
            $query_result = $update_msg_stm->execute();

            break;
    endswitch;
endif;

$db->close();
header('Content-type: application/json');
echo json_encode($json);
