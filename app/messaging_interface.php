<?php
if (isset($_POST['my_user']) && isset($_POST['f_user'])) {
    $my_user = $_POST['my_user'];
    $f_user = $_POST['f_user'];
    echo "<script type='text/javascript'>console.log('$my_user has started a chat session with $f_user.');</script>";
}
?>
<!DOCTYPE html>
<html>

<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <title>BunqsApp</title>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script src="https://momentjs.com/downloads/moment.js"></script>
    <script src="https://cdn.tutorialjinni.com/livestamp/1.1.2/livestamp.min.js"></script>
    <style>
        body {
            background-image: url('../public_html/assets/bunq-bg-img.png');
        }

        div.msg-bg {
            background-color: white;
            margin: auto;
            width: 50%;
            border: 3px solid black;
            padding: 10px;
        }

        .msg {
            position: fixed;
            bottom: 0;
            right: 20%;
        }
    </style>
</head>

<body>
    <h1 style="text-align: center; font-size:35;"><b>BunqsApp</b></h1>
    <div class="container">
        <div class="chat" id="chat">
            <div class="stream" id="cstream">

            </div>
        </div>
        <div class="msg flex-container">
            <form method="post" id="msg_box" action="">
                <textarea style="width:600px;height:80px" name="msg" id="msg_panel"></textarea>
                <input type="hidden" name="my_user" value="<?php echo $my_user; ?>">
                <input type="hidden" name="f_user" value="<?php echo $f_user; ?>">
                <input type="submit" value="Send" id="submit-btn">
            </form>
        </div>
        <div id="dataHelper" last-id=""></div>
    </div>
    <script type="text/javascript">
        $(document).keyup(function(e) {
            if (e.keyCode == 13) {
                if ($('#msg_box textarea').val().trim() == "") {
                    $('#msg_box textarea').val('');
                } else {
                    $('#msg_box textarea').attr('readonly', 'readonly');
                    $('#submit-btn').attr('disabled', 'disabled'); // Disable submit button
                    sendMsg();
                }
            }
        });

        $(document).ready(function() {
            $('#msg_panel').focus();
            $('#msg_box').submit(function(e) {
                $('#msg_box textarea').attr('readonly', 'readonly');
                $('#submit-btn').attr('disabled', 'disabled'); // Disable submit button
                sendMsg();
                e.preventDefault();
            });
        });

        function sendMsg() {
            $.ajax({
                type: 'post',
                url: 'message_handling.php?req=new',
                data: $('#msg_box').serialize(),
                dataType: 'json',
                success: function(resp) {
                    $('#msg_box textarea').removeAttr('readonly');
                    $('#submit-btn').removeAttr('disabled'); // Enable submit button
                    if (resp.status == 0) {
                        alert(resp.msg);
                    } else if (resp.status == 1) {
                        $('#msg_box textarea').val('');
                        $('#msg_box textarea').focus();
                        $conversation_layout = '<div class="float-fix">' +
                            '<div class="m-rply">' +
                            '<div class="msg-bg">' +
                            '<div class="sender">' +
                            resp.from +
                            '<hr>' +
                            '</div>' +
                            '<div class="msgA">' +
                            resp.msg +
                            '<hr>' +
                            '<div class="">' +
                            '<div class="msg-time time-' + resp.last_id + '"></div>' +
                            '<div class="myrply-i"></div>' +
                            '</div>' +
                            '</div>' +
                            '</div>' +
                            '</div>' +
                            '</div>';
                        $('#cstream').append($conversation_layout);

                        $('.time-' + resp.last_id).livestamp();
                        $('#dataHelper').attr('last-id', resp.last_id);
                        $('#chat').scrollTop($('#cstream').height());
                    }
                },
                error: function(xhr, status, error) {
                    console.log(xhr.responseText);
                }
            });
        }


        function checkStatus() {
            $f_user = '<?php echo $f_user; ?>';
            $my_user = '<?php echo $my_user; ?>';
            $.ajax({
                type: 'post',
                url: 'message_handling.php?req=checkMsg',
                data: {
                    f_user: $f_user,
                    my_user: $my_user,
                    last_id: $('#dataHelper').attr('last-id')
                },
                dataType: 'json',
                cache: false,
                success: function(resp) {

                    if (resp.status == 0) {
                        return false;
                    } else if (resp.status == 1) {
                        getMsg();
                    }
                },
                error: function(xhr, status, error) {
                    console.log(xhr.responseText);
                }
            });
        }

        // Check for latest message
        setInterval(function() {
            checkStatus();
        }, 200);

        function initializeChat() {
            $f_user = '<?php echo $f_user; ?>';
            $my_user = '<?php echo $my_user; ?>';
            $.ajax({
                type: 'post',
                url: 'message_handling.php?req=fetchMsg&reboot=1',
                data: {
                    f_user: $f_user,
                    my_user: $my_user
                },
                dataType: 'json',
                success: function(res) {
                    // if (resp.status == 0) {
                    //     alert(resp.msg);
                    // } else if (resp.status == 1) {
                        res.forEach(function(resp) {
                            $conversation_layout = '<div class="float-fix">' +
                                '<div class="f-rply">' +
                                '<div class="msg-bg">' +
                                '<div class="msgA">' +
                                resp.msg +
                                '<div class="">' +
                                '<div class="msg-time time-' + resp.last_id + '"></div>' +
                                '<div class="myrply-f"></div>' +
                                '</div>' +
                                '</div>' +
                                '</div>' +
                                '</div>' +
                                '</div>';
                            $('#cstream').append($conversation_layout);
                            $('.time-' + resp.last_id).livestamp();
                            $('#dataHelper').attr('last-id', resp.last_id);
                        })
                        $('#chat').scrollTop($('#cstream').height());
                },
                error: function(xhr, status, error) {
                    console.log(xhr.responseText);
                }
            });
        }

        initializeChat();

        function getMsg() {
            $f_user = '<?php echo $f_user; ?>';
            $my_user = '<?php echo $my_user; ?>';
            $.ajax({
                type: 'post',
                url: 'message_handling.php?req=fetchMsg',
                data: {
                    f_user: $f_user,
                    my_user: $my_user
                },
                dataType: 'json',
                success: function(res) {
                    res.forEach(function(resp) {
                            $conversation_layout = '<div class="float-fix">' +
                                '<div class="f-rply">' +
                                '<div class="msg-bg">' +
                                '<div class="msgA">' +
                                resp.msg +
                                '<div class="">' +
                                '<div class="msg-time time-' + resp.last_id + '"></div>' +
                                '<div class="myrply-f"></div>' +
                                '</div>' +
                                '</div>' +
                                '</div>' +
                                '</div>' +
                                '</div>';
                            $('#cstream').append($conversation_layout);
                            $('.time-' + resp.last_id).livestamp();
                            $('#dataHelper').attr('last-id', resp.last_id);
                        })
                        $('#chat').scrollTop($('#cstream').height());
                },
                error: function(xhr, status, error) {
                    console.log(xhr.responseText);
                }
            });
        }
    </script>
</body>

</html>